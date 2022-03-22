<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'app/header.php';

$mapname = $_GET["mapname"];
//var_dump($mapname);

	//ПОСЛЕДНИЕ РЕКОРДЫ НА КАРТЕ
	$query = "SELECT times.PlayerID, players.User, players.SteamID, maps.MapPlaytime, maps.LastPlayed, times.Time, times.Type, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE `MapName` LIKE '$mapname' AND type = 0  ORDER BY `times`.`Time` ASC ";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));

	$qMapLastRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);
	$MapLastRecords = bd_glue_api($qMapLastRecords);




    foreach($MapLastRecords as $key => $child) {
    		$lastplayed = $child['LastPlayed'];
			$mapplaytime = $child['MapPlaytime'];	
    }

	echo "<pre>";
	

	echo "</pre>";




?>

<div class="container">
	<div class="map-header" style="background: url(/include/img/arcane.jpg) center center;"> 	
		<div class="map-info">
			<h1><? echo $mapname; ?></h1>
			<div class="map-meta">
				<span><? echo "Play Time: " . formattoseconds($mapplaytime) . " &nbsp;&nbsp; | &nbsp;&nbsp;  ";?></span>
				<span><? echo "Last Played Time: " . gmdate("Y-m-d H:i", $lastplayed);?></span>
			</div>
		</div>
	</div>
	</div>


<div class="container">

	<div class="table-title">Records</div>

	<table class="sortbale">
		<thead>
			<tr>
				<th>Place</th>
				<th>User</th>
				<th>Time</th>
				<th>Style/Track</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>

		<?php

		$i = 1;
		foreach ($MapLastRecords as $MapLastRecord): ?> 

		<tr>
		<td style="text-align: center;"><? echo $i++; ?></td>
		<td>
			<a href="/player.php?steamid=<?=$MapLastRecord["SteamID"]?>"> 
		      <div class="table-user-row">
		        <img src="<?=$MapLastRecord['avatar']?>" width="40px" height="40px;">
		        <div class="table-user-text">
		          <div><a href="/player.php?steamid=<?=$MapLastRecord["SteamID"]?>">
		           <?=empty($MapLastRecord['personaname']) ? $MapLastRecord["User"] = mb_convert_encoding($MapLastRecord["User"], "cp1252", "auto") : $MapLastRecord['personaname']?>
		           </a></div>
		          <div class="table-user-steamid"><?=empty($MapLastRecord['steamid']) ? 'NOSTEAM' : $MapLastRecord['SteamID']?></div>

		        </div>
		      </div>
		    </a>
		</td>
		<td> <?=formattoseconds($MapLastRecord["Time"])?></td>
		<td> <?=$styles[$MapLastRecord["Style"]]?></td>
		<td> <?=$MapLastRecord["Timestamp"] = gmdate("Y-m-d H:i", $MapLastRecord["Timestamp"])?></td>
		</tr>
		<?php  endforeach?>
		</tbody>
	</table>



<?php
include 'app/footer.php';
?>