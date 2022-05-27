<?php

class HomeController
{
    public function actionMain()
    {
        require_once ROOT . '/views/html/main.php';

        return true;
    }
}