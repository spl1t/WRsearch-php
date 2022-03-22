<?php
	//Устанавливаем доступы к базе данных:
		$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
		$user = 'root'; //имя пользователя, по умолчанию это root
		$password = 'root'; //пароль, по умолчанию пустой
		$db_name = 'timer2'; //имя базы данных

	//Соединяемся с базой данных используя наши доступы:
		$link = mysqli_connect($host, $user, $password, $db_name);
	//Устанавливаем кодировку (не обязательно, но поможет избежать проблем):
		mysqli_query($link, "SET NAMES 'utf8'");


if(mysqli_connect_errno()){
	echo 'Ошибка подключения к базе данных ('. mysqli_connect_errno().'): '. mysqli_connect_error();
	exit();
}

$testMap = [
    '0' => 'bhop_gameexe', // 0
    '1' => 'bhop_aeria', // 1
    '2' => 'bhop_600', // 2
    '3' => 'bhop_eskay', // 3
];


$stylesAbrMap = [
    '0' => 'n', // 0
    '1' => 'sw', // 1
    '2' => 'w', // 2
    '3' => 'stam', // 3
    '4' => 'hsw', // 4
    '5' => 'legit', // 5
    '6' => 'cap', // 6
    '7' => 'ez', //7 
    '8' => 'pre', //8 
    '9' => 'autocap', //9 
    '10' => 'd', //10 
    '11' => 'tas', //11 
    '12' => 'lg', //12 
];

$styles = [
    'Normal', // 0
    'Sideways', // 1
    'W-Only', // 2
    'Stamina', // 3
    'Half-Sideways', // 4
    'Legit', // 5
    '400 CAP', // 6
    'EZhop', //7 
    'Prespeed', //8 
    'AUTO 400 CAP', //9 
    'D-Only', //10 
    'Tool-Assisted', //11 
    'Low-Grav', //12 
];



$stylesAbr = [
    'n', // 0
    'sw', // 1
    'w', // 2
    'stam', // 3
    'hsw', // 4
    'legit', // 5
    'cap', // 6
    'ez', //7 
    'pre', //8 
    'autocap', //9 
    'd', //10 
    'tas', //11 
    'lg', //12 
];
