<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'app/header.php';

$mapname = $_GET["mapname"];
//var_dump($mapname);

	//ПОСЛЕДНИЕ РЕКОРДЫ НА КАРТЕ
	$query = "SELECT * FROM `maps` WHERE `MapName` LIKE '$mapname'";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));

	$MapLastRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);





    foreach($MapLastRecords as $key => $child) {
    		$lastplayed = $child['LastPlayed'];
			$mapplaytime = $child['MapPlaytime'];	
    }

	echo "<pre>";
	

	echo "</pre>";




if (file_exists("/include/img/" . $mapname . ".jpg")) {
    echo "The file exists";
} else {
    echo "The file does not exist";
}


?>

<div class="container">
	<div class="map-header" style="background: url(<?php echo "/include/img/" . $mapname . ".jpg" ?>) center center;"> 	
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

	<div class="table-title">Best Records</div>
            
             <!-- Table -->
            <table id='empTable' class='display dataTable'>
                <thead>
                <tr>
                    <th>User</th>
                    <th>Time</th>
                    <th>Style
                        <select id='filterByStyle'>
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
                    <th>Map </th>
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
                        var mapname = "<?php echo "$mapname" ?>";

                        // Append to data
                        data.filterByStyle = style;
                        data.searchByMap = mapname;
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