<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.1
 * Time: 15.03
 */

require './src/App/App.php';
$app = new \Edvardas\Hyphenation\App\App();
$app->executeCommand();