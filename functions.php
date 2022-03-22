<?php

//Timestamp CONVERT
function formattoseconds($time)
{
    $iTemp = floor($time);

    $iHours = 0;

    if ($iTemp > 3600) {
        $iHours = floor($iTemp / 3600.0);
        $iTemp %= 3600;
    }

    $sHours = '';

    if ($iHours < 10) {
        $sHours = '0'.$iHours;
    } else {
        $sHours = $iHours;
    }

    $iMinutes = 0;

    if ($iTemp >= 60) {
        $iMinutes = floor($iTemp / 60.0);
        $iTemp %= 60;
    }

    $sMinutes = '';

    if ($iMinutes < 10) {
        $sMinutes = '0'.$iMinutes;
    } else {
        $sMinutes = $iMinutes;
    }

    $fSeconds = (($iTemp) + $time - floor($time));

    $sSeconds = '';

    if ($fSeconds < 10) {
        $sSeconds = '0'.number_format($fSeconds, 3);
    } else {
        $sSeconds = number_format($fSeconds, 3);
    }

    if ($iHours > 0) {
        $newtime = $sHours.':'.$sMinutes.':'.$sSeconds.'';
    } elseif ($iMinutes > 0) {
        $newtime = $sMinutes.':'.$sSeconds.'';
    } else {
        $newtime = number_format($fSeconds, 3).'';
    }

    return $newtime;
}


//Слияние массива игроков из БД с массивом из STEAMapi
//@param $Массив_игроков_из_БД, столбец STEAM ID обязателен 
function bd_glue_api($db_players){
    // Функция конвертации SteamID в ID64
     function getCommunityFromID($id)
        {
            $accountarray   =   explode(":", $id);
            $idnum          =   $accountarray[1];
            $accountnum     =   $accountarray[2];
            $constant       =   '76561197960265728';
            $number         =   bcadd(bcmul($accountnum, 2), bcadd($idnum, $constant)); // ($accountnum *2) + ($idnum + $constant)
            return $number;
        }
    // Функция конвертации SteamID в STEAM_0:0
    function getIDFromCommunity($id)
        {
            $idnum      =   '0';
            $accnum     =   '0';
            $constant   =   '76561197960265728';
            if(bcmod($id, '2')==0)
            {
                $idnum  =   '0';
                $temp   =   bcsub($id, $constant);
            }
            else
            {
                $idnum  =   '1';
                $temp   =   bcsub($id,bcadd($constant, '1'));
            }
            $accnum =   bcdiv($temp, '2');
            return      "STEAM_0:".$idnum.":".number_format($accnum, 0, '', '');
        }
    //Стили 
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

    //Перезначение значений
    foreach($db_players as $key => $child) {
            $db_players[$key]['SteamID'] = getCommunityFromID($db_players[$key]['SteamID']); 
            $db_players[$key] += ['SteamID64'=> $db_players[$key]['SteamID']];
            unset($db_players[$key]['SteamID']);
            $db_players[$key] += ['SteamID'=> getIDFromCommunity($db_players[$key]['SteamID64'])];

            if (array_key_exists('Time', $db_players[$key])) {
            $db_players[$key]['Style'] = $styles[$db_players[$key]['Style']]; 
            $db_players[$key]['Time'] = formattoseconds($db_players[$key]['Time']); 
            $db_players[$key]['Timestamp'] = gmdate("Y-m-d H:i",  $db_players[$key]['Timestamp']);
            }
 
    }

    //Выбираем только столбец SteamID
    $SteamIDArray = array_column($db_players, 'SteamID', 'PlayerID');
    //print_r($SteamIDArray);

    // Конвертируем SteamID в ID64
    $ID64Array = array_map('getCommunityFromID', $SteamIDArray);
    $ID64String = implode("<br>", $ID64Array);

    $players = array();

    $urljson = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=61A1DCD03CCC466727D2A893FA628810&steamids=$ID64String");
    $SteamProfiles = json_decode($urljson);
    //var_dump($SteamProfiles);
    
    // Complete our player array with additional details from the API
    foreach ($SteamProfiles->response->players as $player)
    {
        $players[$player->steamid]['steamid'] =  $player->steamid;
        $players[$player->steamid]['personaname'] = $player->personaname;
        $players[$player->steamid]['profileurl'] = $player->profileurl;
        $players[$player->steamid]['avatar'] = $player->avatar;
        $players[$player->steamid]['avatarmedium'] = $player->avatarmedium;
        $players[$player->steamid]['avatarfull'] = $player->avatarfull;
        $players[$player->steamid]['personastate'] = $player->personastate;
    }


    //Слияние Массива из БД с Массивом из APISTEAM 
    foreach ($db_players as $key => $row1) {
        $temp = $row1;
        if (array_key_exists($row1['SteamID64'], $players)) {
            $temp += $players[$row1['SteamID64']];
        }
        $data[] = $temp;
    }

    return $data;
}




function search_query(){

    global $link;
    global $stylesAbrMap;
    global $testMap;

    $playerlist = get_player_list();
    $maplist = get_maplist();
    $searchQuery = $_POST["query"];
    $searchWords = explode(" ", $searchQuery);


//var_dump($playerlist);



echo "<br><br>";
$resul = array_intersect($searchWords, $stylesAbrMap);
print_r($resul);
echo "<br><br>";
$resulq = array_intersect($searchWords, $testMap);
print_r($resulq);



echo "<br><br>";
//var_dump($searchWords);
echo "<br><br>";
//var_dump($stylesAbrMap);
echo "<br><br>";
//var_dump($testMap);

if (preg_match('/n|sw|w|stam|hsw|legit|cap|ez|pre|autocap|d|tas|lg/i', $searchWords[1])){
  $qStyle = $searchWords[1];

}

$key = array_search($qStyle, $stylesAbrMap); 
echo "<br><br>";
var_dump($key);



    if (isset($_GET ['page'])) {
        $page = $_GET['page'];
        } else {
        $page = 1;
    }

    $usersPerPage = 50;
    $from = ($page - 1)  * $usersPerPage;
        

    $query = "SELECT times.PlayerID, players.User, times.Time, times.Style, maps.MapName as map_name, times.Timestamp FROM maps RIGHT JOIN times ON maps.MapID=times.MapID LEFT JOIN players ON players.PlayerID=times.PlayerID WHERE style = $key ORDER BY Time ASC LIMIT  $from,$usersPerPage" ;
    $result = mysqli_query($link, $query) or die(mysqli_error($link));

    $lastRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lastRecords;

}






