# Repositori esquelet per al projecte UD2

Aquest repositori és el punt de partida per desenvolupar el projecte de la UD2. Inclou una aplicació mínima amb un *router*, una pàgina inicial que mostra productes i les eines necessàries per ampliar-ne les funcionalitats.

## Eines i funcionalitats incorporades

- **Docker Compose:** posa en marxa l'aplicació PHP, MySQL, phpMyAdmin i Mailpit.
- **Xdebug:** facilita la depuració del PHP. Ja està instal·lat al contenidor; per utilitzar-lo amb VS Code cal disposar de l'extensió **PHP Debug**.
- **PHPMailer i Mailpit:** permeten enviar correus des de PHP i consultar-los en un entorn local de proves. La classe `Mailer` ja està configurada per enviar-los a Mailpit.
- **DotEnv (`vlucas/phpdotenv`):** carrega les variables del fitxer `.env` a PHP.
- **Google API Client (`google/apiclient`):** dependència instal·lada per poder implementar l'autenticació amb Google. **El login amb Google encara no està implementat**; caldrà crear-ne les rutes, el controlador i les credencials OAuth 2.0.
- **PDO i MySQL:** accés a la base de dades mitjançant la classe `Database`, amb les classes `Schema` i `Seeder` per crear taules i inserir dades de prova.
- **Router i controlador base:** permeten definir rutes, executar controladors i renderitzar vistes amb un *layout* comú.

## Estructura de carpetes del projecte

```text
05_Repo_esquelet_projecte/
├── .vscode/
│   └── launch.json                  # Configuració de depuració a VS Code
├── App/
│   ├── Controllers/
│   │   └── HomeController.php       # Pàgina d'inici i resposta 404
│   ├── Core/
│   │   ├── Controller.php           # render() i redirect()
│   │   ├── Database.php             # Connexió i consultes PDO
│   │   ├── Mailer.php               # Enviament de correus amb PHPMailer
│   │   └── Router.php               # Registre i resolució de rutes
│   ├── Database/
│   │   ├── Schema.php               # Creació de l'estructura de taules
│   │   └── Seeder.php               # Inserció de dades inicials
│   ├── Models/
│   │   └── Product.php              # Accés als productes de MySQL
│   └── Views/
│       ├── errors/
│       │   └── 404.view.php         # Vista d'error 404
│       ├── home/
│       │   └── index.view.php       # Vista de inicial productes
│       └──layouts/
│       
├── vendor/                          # Llibreries instal·lades amb Composer (no publicar)
├── .env                             # Configuració local (no publicar)
├── .env.example                     # Plantilla de variables d'entorn
├── .gitignore                       # Fitxers exclosos de Git
├── .htaccess                        # Regles d'Apache
├── autoload.php                      # Càrrega automàtica de classes App\
├── composer.json                     # Dependències PHP (no publicar)
├── composer.lock                     # Versions fixades de les dependències (no publicar)
├── compose.yml                       # Serveis Docker i volum de MySQL
├── Dockerfile                        # Imatge PHP/Apache, PDO, Composer i Xdebug
├── index.php                         # Punt d'entrada, sessió i rutes
└── README.md                         # Aquesta documentació
```

### Funcionament MVC

1. **`index.php`** inicia la sessió, carrega les dependències i les variables d'entorn, registra les rutes i invoca el `Router`.
2. **`Router.php`** identifica la ruta i crida el mètode del controlador corresponent.
3. **`Controllers/`** conté la lògica de cada petició. Els controladors poden consultar els models i passar dades a les vistes.
4. **`Models/`** conté les operacions amb les dades, amb l'ajuda de `Core/Database.php`.
5. **`Views/`** genera el contingut HTML; `Core/Controller.php` integra la vista en el *layout* principal.
6. **`Database/Schema.php` i `Database/Seeder.php`** preparen l'estructura i les dades de prova de MySQL.

## Posar en marxa el projecte

### 1. Requisits

Cal tenir instal·lats **Docker** i **Docker Compose**. Es recomana **Visual Studio Code** per editar i depurar el projecte.

### 2. Configurar les variables d'entorn

Obre un terminal a la carpeta on hi ha `compose.yml` i, si no existeix `.env`, crea'l a partir de l'exemple:

```bash
cp .env.example .env
```

Revisa els ports i les credencials de MySQL. Cal definir les variables del `.env' abans d'aixevar el projecte.

### 3. Construir i iniciar els contenidors

```bash
docker compose up -d --build
```

### 4. Accedir als serveis

Amb els ports proposats al `.env.example` i el valor suggerit per a Mailpit:

| Servei | Adreça |
|---|---|
| Aplicació PHP | http://localhost:xxxx |
| phpMyAdmin | http://localhost:xxxx |
| Mailpit | http://localhost:xxxx |

A phpMyAdmin, utilitza el nom d'usuari i la contrasenya definits a `DB_USER` i `DB_PASSWORD`.

### 5. Inicialització de la base de dades

En visitar **`/`**, `HomeController` comprova si existeix la taula `products`. Si no existeix:

1. `Schema` crea la taula `products`.
2. `Seeder` hi insereix set productes de prova.
3. `Product` consulta els registres i la vista inicial els mostra en una taula.

En visites posteriors, si la taula ja existeix, **no es tornen a inserir** aquests set productes. 

## Aturar i tornar a iniciar el projecte

Per aturar i eliminar els contenidors **sense eliminar el volum de MySQL**:

```bash
docker compose down
```

Per tornar-los a iniciar:

```bash
docker compose up -d
```

La persistència de MySQL es gestiona amb el volum `mysql_data`, que rep el nom indicat a `DB_VOLUME_NAME`.

**Atenció:** la comanda següent elimina també els volums de Compose (si no són externs) i, per tant, pot **esborrar definitivament les dades de MySQL**:

```bash
docker compose down -v
```

## Comandes útils

```bash
# Consultar l'estat dels serveis
docker compose ps

# Consultar els registres dels contenidors
docker compose logs -f

# Aturar els contenidors sense eliminar-los
docker compose stop

# Tornar a iniciar els contenidors aturats
docker compose start

# Reconstruir la imatge després de modificar el Dockerfile
docker compose up -d --build
```

## Consideracions importants

- **No publiquis `.env`** al repositori: conté credencials locals. Ja apareix a `.gitignore`. Mantén `.env.example` sense credencials reals.
- **No cal pujar `vendor/` a Git:** ja apareix a `.gitignore`; les dependències es descriuen a `composer.json` i `composer.lock`.
- Si la base de dades ja s'ha inicialitzat, **canviar la contrasenya al `.env` no modifica automàticament la de l'usuari existent a MySQL**.
- **Mailpit** és una eina per provar correus localment, no un servidor per lliurar correus reals als destinataris.
- La llibreria de **Google API** està instal·lada, però per activar el login amb Google cal configurar les credencials OAuth 2.0 i desenvolupar el flux d'autenticació.
- Si modifiques el **Dockerfile**, reconstrueix el contenidor amb `docker compose up -d --build`.