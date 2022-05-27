<?php

class ProductController
{
//    public function actionGet($id)
//    {
//
//        $id = intval($id);
//
//        $product = Product::getProduct($id);
//
//        echo '<pre>';
//        var_dump(json_encode($product));
//        echo '</pre>';
//
//        return true;
//    }

    public function actionList()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        $result = Product::getAllProducts();

        $num = count($result);

        if ($num > 0) {
            echo json_encode($result);
        } else {
            echo json_encode(['message' => 'No Products Found']);
        }

        return true;
    }
}