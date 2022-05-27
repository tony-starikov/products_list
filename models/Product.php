<?php


class Product
{
    public static function getProduct($id)
    {
        $product = [];

        $db = Database::getConnection();

        $result = $db->query('SELECT id, name, price, quantity FROM products WHERE id=' . $id);

        $product = $result->fetchAll(PDO::FETCH_ASSOC);

        return $product;
    }

    public static function getAllProducts()
    {
        $products = [];

        $db = Database::getConnection();

        $result = $db->query('SELECT id, name, price, quantity FROM products ORDER BY id DESC');

        $products = $result->fetchAll(PDO::FETCH_ASSOC);

        return $products;
    }
}