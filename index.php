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
            <select name="search-by" id="search-by">
                <option value="by-map">By Map</option>
                <option value="by-player">By Player</option>
            </select>
			<input type="search" id="site-search" name="query" aria-label="Search through site content"  placeholder="bhop_eazy">
			<button>Search</button>
			</form>
		</div>
	</div>
<!-- particles.js container -->
<div id="particles-js"></div>

<!-- scripts -->
<script src="/include/js/particles.js"></script>
<script src="/include/js/app.js"></script>

</div>

<?php
//$dir   = 'include/img';
//$files = scandir($dir);
//$allfiles = array_diff($files, array('.', '..', 'desktop.ini'));

 


//var_dump($allfiles);

//ДОДЕЛАТЬ РАНДОМИЗАЦИЮ КАРТИНОК ФОНА
//ПОИСК ПО ИГРОКАМ В arrayBD через сохраненый массив

?>





<div class="container">

	<div class="table-title">Last Records</div>




            
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


