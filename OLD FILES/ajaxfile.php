<?php
include 'connect.php';
include 'functions.php';



## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index ДЛЯ СОРТИНГА 
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name ДЛЯ СОРТИНГА 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc ДЛЯ СОРТИНГА 
$searchValue = $_POST['search']['value']; // Search value

## Custom Field value
$searchByMap = $_POST['searchByMap'];
$filterByStyle = $_POST['filterByStyle'];
$recordBySteamID = $_POST['recordBySteamID'];

## Search 
$searchQuery = " ";
if($searchByMap != ''){
    $searchQuery .= " and (MapName like '%".$searchByMap."%' ) ";
}
if($filterByStyle != ''){
    $searchQuery .= " and (style='".$filterByStyle."') ";
}
if($recordBySteamID != ''){
    $searchQuery .= " and (SteamID like '%".$recordBySteamID."%' ) ";
}
if($searchValue != ''){
    $searchQuery .= " and (User like '%".$searchValue."%' or 
        User like '%".$searchValue."%' or 
        User like'%".$searchValue."%' ) ";
}

## Total number of records without filtering ПРОПИСАТЬ КОРЕКТНЫЙ ЗАПРОС 
$sel = mysqli_query($link,"select count(*) as allcount from (SELECT times.PlayerID, players.User, players.SteamID, times.Time, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE 1 AND type = 0) as allcount");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering ПРОПИСАТЬ КОРЕКТНЫЙ ЗАПРОС 
$sel = mysqli_query($link,"select count(*) as allcount from (SELECT times.PlayerID, players.User, players.SteamID, times.Time, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE 1 ".$searchQuery." AND type = 0) as allcount");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];



## Fetch records
$empQuery = "SELECT times.PlayerID, players.User, players.SteamID, times.Time, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE 1 ".$searchQuery." AND type = 0 ORDER BY Timestamp DESC  limit ".$row.",".$rowperpage;
$result = mysqli_query($link, $empQuery);
    
    $qlastRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $data = bd_glue_api($qlastRecords);


$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
