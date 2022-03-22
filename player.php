<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'app/header.php';

	$steamid = $_GET["steamid"];

	//Последние рекорды
	$query = "SELECT times.PlayerID, players.User, players.SteamID, times.Time, times.Type, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players  INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE `SteamID` LIKE '$steamid' AND type = 0 ORDER BY `times`.`Timestamp` DESC LIMIT 20";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$qPlayerlastRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);
	$PlayerlastRecords = bd_glue_api($qPlayerlastRecords);

	echo "<pre>";
	//var_dump($steamid);
	//var_dump($qPlayerlastRecords);
	//var_dump($PlayerlastRecords);
	echo "</pre>";

	//Инфо о игроке с проверкой на Ностим
	$nicknameDB = '';
    foreach($PlayerlastRecords as $key => $child) {
    	if (array_key_exists('steamid', $child)) {
			$nicknameAPI = $child['personaname'];
			$SteamID = $child['SteamID'];
			$avatarfull = $child['avatarfull'];
			$profileUrl = $child['profileurl'];
			$playerid = $child['PlayerID'];
			$personastate = $child['personastate'];
    	}else{
    		$nicknameDB = $child['User'];
    		$playerid = $child['PlayerID'];
    	}
    }
	//Количество WR
	$query = "SELECT distinct playerID, COUNT(record) AS wr FROM (SELECT distinct Table1.mapID, playerID, record FROM ( SELECT mapID, MIN(time) AS record FROM Times WHERE type = 0 AND style =0 AND time != 0 GROUP BY mapID) AS Table1 INNER JOIN (SELECT * FROM Times WHERE type = 0 AND style =0 and time != 0) AS Table2 ON Table1.mapID = Table2.mapID AND Table1.record = Table2.time ) AS Record_Map WHERE playerID = $playerid GROUP BY playerID";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$wrcount = mysqli_fetch_assoc($result)['wr'];

	//Колличество пройденых карт
	$query = "SELECT COUNT(*) as mapsdone FROM (SELECT times.PlayerID, players.User, players.SteamID, times.Time, times.Type, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE `SteamID` LIKE '$steamid' AND type = 0 AND style = 0) as mapsdone";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$mapsdone = mysqli_fetch_assoc($result)['mapsdone'];

	//Поинты
	$query = "SELECT SUM(Points) as points FROM (SELECT times.PlayerID, times.Points, players.User, players.SteamID, times.Time, times.Type, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE `SteamID` LIKE '$steamid' AND type = 0 AND style = 0) as points";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$points = mysqli_fetch_assoc($result)['points'];
	$points = round($points);

	//Средний Sync
	$query = "SELECT AVG(Sync) as sync FROM (SELECT times.PlayerID, times.Points, players.User, players.SteamID, times.Sync, times.Time, times.Type, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE `SteamID` LIKE '$steamid' AND type = 0 AND style = 0) as sync";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$sync = mysqli_fetch_assoc($result)['sync'];
	$sync = round($sync);




//ПОДРГУРЗКА ЧЕРЕЗ JQUERY
$html = file_get_html($profileUrl);
//var_dump($profileUrl);

// Find all images
foreach($html->find('.no_header') as $element)
       $bgprofile = $element->style;
 


$bgprofile = preg_replace('~.+url\((.+)\).+~', '$1', $bgprofile);
 


?>


<body style="background-image: url(<?=$bgprofile?>); background-position: 49.999% 0;     background-size: cover;">




	<div class="container">
	<div class="user-header"> 
		<div class="user-info"> 
			<? if ($nicknameDB == NULL) { 
				echo '<img class="user-avatar" src="'.$avatarfull.'">';  
			} else { 
				echo '<img class="user-avatar" src="\include\img\useravatar.png">'; 
			} ?>
			<div class="user-header-column">
				<div class="user-nickname-and-steam">
					<div class="user-nickname"><?if ($nicknameDB == NULL) { echo $nicknameAPI; } else { echo $nicknameDB; } ?></div>
					<div class="user-steamid"><?if ($nicknameDB == NULL) { echo $SteamID; } else { echo 'NoSteam'; }?> <div><? 
					echo "ID  $playerid " ?> </div> </div>
				</div>
				<div class="user-online-status"> <? if ($personastate > 0) { echo "Online"; } else { echo "Offline"; } ?> </div>
			</div>
		</div>
		<div class="user-stats"> 
			<ul>
				<li>
					<div class="user-stats-icon"><i class="fas fa-trophy"></i></div>
					<div class="user-stats-value">
						<? if ($wrcount != NULL) { 
							echo $wrcount;
						} else {
							echo "--";
						} ?>
					</div>
					<div class="user-stats-text">World records</div>
				</li>
				<li>
					<div class="user-stats-icon"><i class="far fa-check-circle"></i></div>
					<div class="user-stats-value">
						<? if ($mapsdone != NULL) { 
							echo $mapsdone;
						} else {
							echo "--";
						} ?>
					</div>
					<div class="user-stats-text">Maps Done</div>
				</li>
				<li>
					<div class="user-stats-icon"><i class="fas fa-chess-board"></i></div>
					<div class="user-stats-value">
						<? if ($points != NULL) { 
							echo $points;
						} else {
							echo "--";
						} ?>
					</div>
					<div class="user-stats-text">Points</div>
				</li>
				<li>
					<div class="user-stats-icon"><i class="fas fa-sync-alt"></i></div>
					<div class="user-stats-value">
						<? if ($sync != NULL) { 
							echo $sync;
						} else {
							echo "--";
						} ?>
					</div>
					<div class="user-stats-text">Average sync</div>
				</li>
			</ul>
			<ul>
				<li>
					<div class="user-stats-icon"><i class="fas fa-list-ol"></i></div>
					<div class="user-stats-value">26?</div>
					<div class="user-stats-text">Lader position</div>
				</li>
				<li>
					<div class="user-stats-icon"><i class="fas fa-chess-board"></i></div>
					<div class="user-stats-value">1337?</div>
					<div class="user-stats-text">LVL</div>
				</li>
			</ul>
		</div>

	</div>


<script src="include/js/circle-progress.js"></script>
		<div class="user-achievement">
			<h2>Achivments</h2>
			<div class="achivments-col">
				
				<div class="achivment">
				<div class="achivmnet-info">
					<i class="fas fa-frog"></i>
					<div class="achivmnet-count"><span>334</span> /  <span>1337</span> </div>
					<div class="achivmnet-count-name">Jumps</div>
				</div>
						
						<div class="circle" id="c1"></div>

						<script>
						  $('#c1').circleProgress({
						    value: 0.25,
						    size: 100,
						    fill: {
						      gradient: ["yellow", "green"]
						    }
						  });
						</script>

					<div class="achivment-name">Frog 1 LVL</div>
				</div>
				
				</div>
			</div>
		</div>
	</div>

</div>


<div class="container">

	<div class="table-title">Last Records</div>
            
            <!-- Table -->
            <table id='empTable' class='display dataTable'>
                <thead>
                <tr>
                	<th>Map</th>
                    <th>Time</th>
                    <th>Style
                        <select id='filterByStyle'>
                            <option value=''>ALL</option>
                            <option value='0'>Normal</option>
                            <option value='1'>Sideways</option>
                            <option value='2'>W-Only</option>
                            <option value='3'>Stamina</option>
                            <option value='4'>Half-Sideways</option>
                            <option value='5'>Legit </option>
                            <option value='6'>400 CAP</option>
                            <option value='7'>EZhop </option>
                            <option value='8'>Prespeed</option>
                            <option value='9'>AUTO 400 CAP</option>
                            <option value='10'>D-Only</option>
                            <option value='11'>Tool-Assisted</option>
                            <option value='12'>Low-Grav</option>
                        </select>
                    </th>
                    <th>Date</th>
                </tr>
                </thead>
                
            </table>
        </div>
        
        <!-- Script -->
        <script>
        $(document).ready(function(){
            var dataTable = $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                "ordering": false,
                "bLengthChange" : false,
                "bFilter" : false,
                "pageLength": 20,

                'ajax': {
                    'url':'ajaxfile.php',
                    'data': function(data){
                        // Read values
                        var style = $('#filterByStyle').val();
                        var mapname = $('#searchByMap').val();
                        var steamid = "<?php echo "$steamid" ?>";

                        // Append to data
                        data.filterByStyle = style;
                        data.searchByMap = mapname;
                        data.recordBySteamID = steamid;
                    }
                },
        "columns":[
        { "data": "map_name",
        "render":function(data,type,row){
            return '<a href="/map.php?mapname=' +data + '">' + data + '</a>';
            }

        },
        { "data": "Time" },
        { "data": "Style" },

        { "data": "Timestamp" },
        ]
            });

            $('#searchByMap').keyup(function(){
                dataTable.draw();
            });

            $('#filterByStyle').change(function(){
                dataTable.draw();
            });
        });
        </script>


</body>


<?php
include 'app/footer.php';
?>
