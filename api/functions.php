<?php

function getInventory ($connect, $steamid) {
	//Запрос на выборку 6 слотов по параметру steamid
    $inventory = mysqli_query($connect, "SELECT `slot0`,`slot1`,`slot2`,`slot3`,`slot4`,`slot5` FROM `inventory` WHERE `steamid` = '$steamid'");
	//помещаем результирующую строку ответа в ассоциативный массив
    $inventory = mysqli_fetch_assoc($inventory);
	//конвертируем масив в json объект и возвращаем в качестве ответа
    echo json_encode($inventory);
}

function setInventory ($connect, $steamid, $body) {
	//Пытаемся найти уже существующую запись для данного steamid
    $inventory = mysqli_query($connect, "SELECT * FROM `inventory` WHERE `steamid` = '$steamid'");
    $inventory = mysqli_fetch_assoc($inventory);
	
	//Записываем в переменные значения полей из пейлоада (вовсе не обязательно и body можно использовать напрямую) 
    $slot0 = $body[0];
    $slot1 = $body[1];
    $slot2 = $body[2];
    $slot3 = $body[3];
    $slot4 = $body[4];
    $slot5 = $body[5];

	//Проверяем существует ли в базе данных запись с этим steamid
    if (is_null($inventory)){
		//Если не существует осуществляем вставку 
        $inventory = mysqli_query($connect, "INSERT INTO `inventory`(`steamid`, `slot0`, `slot1`, `slot2`, `slot3`, `slot4`, `slot5`) VALUES ('$steamid','$slot0','$slot1','$slot2','$slot3','$slot4','$slot5')" );
    }else{
		//Если существует осуществляем обновление
		//Пример использования объектного синтаксиса mysqli
        $stmt = $connect->prepare("UPDATE `inventory` SET `slot0`='$slot0',`slot1`='$slot1',`slot2`='$slot2',`slot3`='$slot3',`slot4`='$slot4',`slot5`='$slot5' WHERE `steamid` = '$steamid' ");
        $stmt->execute();
    }
}
