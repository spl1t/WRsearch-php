<?php
    require_once 'connect.php';
    require_once 'functions.php';
    //Для парсинга стим фона
    include_once 'simple_html_dom.php';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>WRsearch</title>

    <!-- meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    
    <!-- CSS STYLE -->
    <link href="include/css/style.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <!-- Datatable CSS -->
    <link href='include/js/DataTables/datatables.min.css' rel='stylesheet' type='text/css'>
    <!-- Datatable JS -->
    <script src="include/js/DataTables/datatables.min.js"></script>

    <!-- MY SCRIPTS -->
    <script src="include/js/scripts.js"></script>

</head>
<body>

<div class="header">
    <a href="/"class="logo"><h6>tru://wr</h6></a>
    <div class="menu">
        <ul>
            <li><a href="">Recent WRs</a></li>
            <li><a href="/players.php">Top 100</a></li>
            <li><a href="/maplist.php">Map List</a></li>
            <li><a href="">Server List</a></li>
            <li><a href="">Sign Up</a></li>
        </ul>
        </div>    
</div>