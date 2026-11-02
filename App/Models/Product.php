<?php

namespace App\Models;

use App\Core\Database;

class Product extends Database
{
    private $id;
    public $name;
    private $price;
    private $category;

    // public function __construct($i,$n,$p,$c){
    //     $this->id = $i;
    //     $this->name = $n;
    //     $this->price = $p;
    //     $this->category = $c;
    // }

    public function getAll()
    {
        return $this->fetchAll(
            'SELECT id, name, price, category FROM products ORDER BY id'
        );
    }

    public function getById($id)
    {
        return $this->fetchOne(
            'SELECT id, name, price, category FROM products WHERE id = :id',
            ['id' => $id]
        );
    }

    // private function getIdProduct()
    // {
    //     $maxId = 0;

    //     foreach ($_SESSION['products'] as $product) {
    //         if ($product['id'] > $maxId) {
    //             $maxId = $product['id'];
    //         }
    //     }

    //     return $maxId + 1;
    // }

    public function create($info)
    {
        $product['id'] = $this->getIdProduct();
        $product['name'] = $info['name'];
        $product['price'] = $info['price'];
        $product['category'] = $info['category'];

        array_push($_SESSION['products'], $product);
        return $product;
    }

    public function update($id, $data)
    {
        foreach ($_SESSION['products'] as $index => $product) {

            if ($product['id'] == $id) {

                $_SESSION['products'][$index]['name'] = $data['name'];
                $_SESSION['products'][$index]['price'] = $data['price'];
                $_SESSION['products'][$index]['category'] = $data['category'];

                return true;
            }
        }

        return false;
    }

    public function delete($id)
    {
        foreach ($_SESSION['products'] as $key => $product) {

            if ($product['id'] == $id) {

                unset($_SESSION['products'][$key]);

                return true;
            }
        }

        return false;
    }
}
