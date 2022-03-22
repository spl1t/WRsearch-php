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
			<input type="search" id="site-search" name="query" aria-label="Search through site content"  placeholder="!wr hsw bhop_eazy">
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
				<th>User</th>
				<th>Time</th>
				<th>Style/Track</th>
				<th>Map</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>

		<?php



	$query = "SELECT times.PlayerID, players.User, players.SteamID, times.Time, times.Type, times.Style, maps.MapID, maps.MapName as map_name, times.Timestamp FROM players   INNER JOIN times ON players.PlayerID=times.PlayerID INNER JOIN maps ON maps.MapID=times.MapID WHERE type = 0 ORDER BY Timestamp DESC LIMIT 20";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));

	$qlastRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);
	$lastRecords = bd_glue_api($qlastRecords);

	//echo "<pre>";

	//echo "</pre>";


		foreach ($lastRecords as $lastRecord): ?> 

		<tr>
		<td>
			<a href="/player.php?steamid=<?=$lastRecord["SteamID"]?>"> 
		      <div class="table-user-row">
		        <img src="<?=$lastRecord['avatar']?>" width="40px" height="40px;">
		        <div class="table-user-text">
		          <div><a href="/player.php?steamid=<?=$lastRecord["SteamID"]?>">
		           <?=empty($lastRecord['personaname']) ? $lastRecord["User"] = mb_convert_encoding($lastRecord["User"], "cp1252", "auto") : $lastRecord['personaname']?>
		           </a></div>
		          <div class="table-user-steamid"><?=empty($lastRecord['steamid']) ? 'NOSTEAM' : $lastRecord['SteamID']?></div>

		        </div>
		      </div>
		    </a>
		</td>
		<td> <?=formattoseconds($lastRecord["Time"])?></td>
		<td> <?=$styles[$lastRecord["Style"]]?></td>
		<td> <a href="/map.php?mapname=<?=$lastRecord["map_name"]?>"> <?=$lastRecord["map_name"]?></a></td>
		<td> <?=$lastRecord["Timestamp"] = gmdate("Y-m-d H:i", $lastRecord["Timestamp"])?></td>
		</tr>
		<?php  endforeach?>
		</tbody>
	</table>



	</div>




       <div class="container">

            
            <!-- Table -->
            <table id='empTable' class='display dataTable'>
                <thead>
                <tr>
                    <th>User</th>
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
                    <th>Map     <input type='text' id='searchByMap' placeholder='ALL'></th>
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
                        var gender = $('#filterByStyle').val();
                        var name = $('#searchByMap').val();

                        // Append to data
                        data.filterByStyle = gender;
                        data.searchByMap = name;
                    }
                },
        "columns":[
        { "data":"personaname",
            "render":function(data,type,row){
                if ( data === undefined ) {
                return '<div class="table-user-row">' + '<a href="/player.php?steamid=' +row['SteamID'] + '">' + '<img  src="'+row['avatar']+'"/>'+ '<div class="table-user-text">' + '<div>' + row['User'] +' </div>' + '<div class="table-user-steamid">' + 'NOSTEAM' + '</div>' + '</div>' + '</a>' + '<div>';
                }
                else {
                return '<div class="table-user-row">' + '<a href="/player.php?steamid=' +row['SteamID'] + '">' + '<img  src="'+row['avatar']+'"/>'+ '<div class="table-user-text">' + '<div>' + data+' </div>' + '<div class="table-user-steamid"> '+row['SteamID'] + '</div>' + '</div>' + '</a>' + '<div>';
                }
            }
        },
        { "data": "Time" },
        { "data": "Style" },
        { "data": "map_name",
        "render":function(data,type,row){
            return '<a href="/map.php?mapname=' +data + '">' + data + '</a>';
            }

        },
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


<?php
include 'app/footer.php';
?>


