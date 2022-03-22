<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'app/header.php';


	//Топ игроков по Points
	$query = "SELECT times.PlayerID, SUM(times.Points) as points, players.User, players.SteamID FROM players INNER JOIN times ON players.PlayerID=times.PlayerID WHERE type = 0 AND style = 0 GROUP BY PlayerID ORDER BY points DESC LIMIT 100";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$qtopplayers = mysqli_fetch_all($result, MYSQLI_ASSOC);
	$topplayers = bd_glue_api($qtopplayers);
//var_dump($topplayers);
?>


<div class="container">

	<div class="table-title">Top Players</div>

	<table class="sortbale">
		<thead>
			<tr>
				<th>Position</th>
				<th>User</th>
				<th>Points</th>
			</tr>
		</thead>
		<tbody>

		<?php 
 		$i = 1;
		foreach ($topplayers as $topplayer): ?> 

		<tr>
		<td><? echo $i++; ?></td>
		<td>
			<a href="/player.php?steamid=<?=$topplayer["SteamID"]?>"> 
		      <div class="table-user-row">
		        <img src="<?=$topplayer['avatar']?>" width="40px" height="40px;">
		        <div class="table-user-text">
		          <div><a href="/player.php?steamid=<?=$topplayer["SteamID"]?>">
		           <?=empty($topplayer['personaname']) ? $topplayer["User"] = mb_convert_encoding($topplayer["User"], "cp1252", "auto") : $topplayer['personaname']?>
		           </a></div>
		          <div class="table-user-steamid"><?=empty($topplayer['steamid']) ? 'NOSTEAM' : $topplayer['SteamID']?></div>
		        </div>
		      </div>
		    </a>
		<td> <?=round($topplayer['points']) ?></td>
		</td>

		</tr>
		<?php  endforeach ?>
		</tbody>
	</table>

	</div>


<?php
include 'app/footer.php';
?>

</body>