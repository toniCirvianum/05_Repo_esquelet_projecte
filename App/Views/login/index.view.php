<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="card-title">Iniciar sessió</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <p class="card-text">Accedeix amb el teu compte de Google.</p>
                <a href="/auth/google" class="btn btn-outline-primary">
                    Entrar amb Google
                </a>
            </div>
        </div>
    </div>
</div>
