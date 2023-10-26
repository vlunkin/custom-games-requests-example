<?php
//Устанавливаем заголовки для ответов
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'connect.php';
require 'functions.php';

//Перед всеми действиями валидация ключа сервера  
$secret_key = ($_SERVER['HTTP_DEDICATED_SERVER_KEY'] == 'test123');
if ($secret_key) {

//Определяем метод запроса
$method = $_SERVER['REQUEST_METHOD'];

//Получаем payload запроса
$payload = json_decode(file_get_contents("php://input"), true);

//Определяем роут запроса
$rout = $params[0];

//Явно определяем значение параметра steamid
$steamid = $_GET['steamid'];

//В зависимости от метода и роута вызываем нужную функцию
if ($method === 'GET') {
    if ($rout === 'inventory'){
        getInventory($connect, $steamid);
    }
} elseif ($method === 'POST'){
    if ($rout === 'inventory') {
        setInventory($connect, $steamid, $payload);
    }
};

} else {
	//Если ключ не валиден
    echo 403;
}