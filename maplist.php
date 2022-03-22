<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'app/header.php';
?>



<div class="container">

	<div class="table-title">Map List</div>

	<table class="sortbale">
		<thead>
			<tr>
				<th>Map</th>
				<th>Best Time</th>
			</tr>
		</thead>
		<tbody>

		<?php
		$maplist = get_maplist();

		foreach ($maplist as $map): ?> 

		<tr>
		<td> <a href="/map.php?map_name=<?=$map["MapName"]?>"> <?=$map["MapName"]?></a></td>
		<td> <div class="best-time">16:00</div> <div class="record-by">AEWOW</div></td>
		</tr>
		<?php  endforeach?>
		</tbody>
	</table>

	</div>




<?php
include 'app/footer.php';
?>