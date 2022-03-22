<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'app/header.php';


?>




<div class="hero-section">

	<div class="container">
		<div class="hero-search-item">
			<label for="site-search"><h3>Search <span>WR</span> on TRUBHOPING:</h3></label>
			<form class="search" name="search" method="post" action="search.php">
			<div class="search-input">
			<input type="search" id="site-search" name="query" aria-label="Search through site content" value="<?=$_POST["query"]?>"  placeholder="!wr hsw bhop_eazy">
					<div class="ex-query">example query: &nbsp;  !wr &nbsp;  [style] &nbsp;  [map name] &nbsp;  [nickname or steamid] &nbsp;  *all keys optionals </div>
			</div>
			<button>Search</button>
			</form>

		
		</div>


		<ul class="hero-wr-keys">
			<li><span>Normal</span> n</li>
			<li><span>Sideways</span> sw</li>
			<li><span>W-Only</span> w</li>
			<li><span>Stamina</span> stam</li>
			<li><span>Half-Sideways</span> hsw</li>
			<li><span>Legit</span> legit</li>
			<li><span>400 CAP</span> cap</li>
			<li><span>EZhop</span> ez</li>
			<li><span>Prespeed</span> pre</li>
			<li><span>AUTO 400 CAP</span> autocap</li>
			<li><span>D-Only</span> d</li>
			<li><span>Tool-Assisted</span> tas</li>
			<li><span>Low-Grav</span> lg</li>
		</ul>
	</div>
<!-- particles.js container -->
<div id="particles-js"></div>

<!-- scripts -->
<script src="/include/js/particles.js"></script>
<script src="/include/js/app.js"></script>
</div>




<div class="container">

	<div class="table-title">Last Records</div>

	<table class="sortbale">
		<thead>
			<tr>
				<th>PlayerID</th>
				<th>User</th>
				<th>Time</th>
				<th>Style</th>
				<th>Map</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>

		<?php
		$lastRecords = search_query();
		foreach ($lastRecords as $lastRecord): ?> 
		<tr>
		<td> <?=$lastRecord["PlayerID"]?></a></td>
		<td> <a href="/player.php?player_id=<?=$lastRecord["PlayerID"]?>"> <?=$lastRecord["User"] = mb_convert_encoding($lastRecord["User"], "cp1252", "auto")?></a></td>
		<td> <?=formattoseconds($lastRecord["Time"])?></td>
		<td> <?=$styles[$lastRecord["Style"]]?></a></td>
		<td> <a href="/map.php?map_name=<?=$lastRecord["map_name"]?>"> <?=$lastRecord["map_name"]?></a></td>
		<td> <?=$lastRecord["Timestamp"] = gmdate("Y-m-d H:i", $lastRecord["Timestamp"])?></a></td>
		</tr>
		<?php  endforeach?>
		</tbody>
	</table>

	</div>