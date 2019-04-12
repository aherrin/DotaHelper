<?php
    session_start();
    require('SteamLogin.php');
    $url = SteamLogin::genUrl('http://aherrin.create.stedwards.edu/DotaHelper/Pages/index.php');
    //$url = SteamLogin::genUrl();
    $response = SteamLogin::validate();
            
    if(empty($response)) {
        
    }
    else{
        $_SESSION['steamID'] = $response;
        $steamID = $_SESSION['steamID'];
        $conn = dbConnect();
        if(!doesPlayerExist($conn, $steamID)){
        addNewPlayer($conn, $steamID);
        } else {
            //updatePlayer($conn, $steamID);
        }
    }
    
    
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <!-- Bootstrap core JavaScript-->
    <script src="./vendor/jquery/jquery.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    
    <!-- Core plugin JavaScript-->
    <script src="./vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Page level plugin JavaScript-->
    <script src="./vendor/chart.js/Chart.min.js"></script>
    <script src="./vendor/datatables/jquery.dataTables.js"></script>
    <script src="./vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    
    <!-- Custom scripts for all pages-->
    <script src="./js/sb-admin.min.js"></script>
    <script src="./vendor/jquery/jquery.js" type="text/javascript"></script>
    <script src="./vendor/datatables/jquery.dataTables.js" type="text/javascript"></script>
    
    <!-- Demo scripts for this page-->
    <script src="./js/demo/datatables-demo.js"></script>
    <script src="./js/demo/chart-area-demo.js"></script>
  
  

    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Dota Helper - Dashboard</title>
    
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <link href="css/sb-admin.css" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

</head>


<body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

        <a class="navbar-brand mr-1" href="index.php">Dota Helper</a>
    
        <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
            <i class="fas fa-bars"></i>
        </button>

    </nav>
  
    <div id="wrapper">
    
    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
            </a>
        </li>
      
        <!--<li class="nav-item active">
            <a class="nav-link" href="profile.php">
                <i class="far fa-address-card"></i>
                    <span>Profile</span>
            </a>
        </li>-->
        
        <li class="nav-item active">
            <?php
                if(isset($_SESSION['steamID'])){
                    print "<a class='nav-link' href='profile.php'>";
                    print "<i class = 'far fa-address-card'></i>";
                    print "<span> Profile</span>";
                    print "</a>";
                }
            ?>
        </li>
          
        
        <li class="nav-item active">
            <?php
                if(!isset($_SESSION['steamID'])){
                    print "<a class='nav-link' href='";
                    echo $url;
                    print"'>Login via Steam</a>";
                }
            ?>
        </li>
        
        <li class="nav-item active">
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
            </a>
        </li>
    </ul>

    <div id="content-wrapper">
    
        
            
            
    <!--THIS IS WHERE THE BODY GOES-->
    <div class="container-fluid">

                
        <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-table"></i>
                        Professional Trends
                </div>
      
      <div class="card-body">
          
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Hero</th>
                    <th>Picks</th>
                    <th>Bans</th>
                    <th>Wins</th>
                    <th>Losses</th>
                    <th>Win Rate</th>
                    <th>Loss Rate</th>
                  </tr>
                </thead>
                <tbody>
                    
                    <?php
                        printProTrendsTable();
                    ?>
                  
                </tbody>
                <tfoot>
                    <tr>
                        <th>Hero</th>
                        <th>Picks</th>
                        <th>Bans</th>
                        <th>Wins</th>
                        <th>Losses</th>
                        <th>Win Rate</th>
                        <th>Loss Rate</th>
                    </tr>
                </tfoot>
              </table>
        </div>
        
        
      </div>
      
    </div>
    
      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © DotaHelper 2019</span>
          </div>
        </div>
      </footer>
    
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  

</body>



<?php

/*----------FUNCTIONS-----------*/
function dbConnect()
{
    $servername = "localhost";
    $username = "aherrinc_senior";
    $password = "password!";
    $dbname = "aherrinc_DotaHelper";

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function doesPlayerExist($conn, $steamid) {
    $sql = "Select * FROM Player WHERE steamid = ".$steamid."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true;
    }
    else{
           return false;
    }
}
    

function escapeString($conn, $string) {
    $retval = mysqli_real_escape_string($conn, $string);
    return $retval;
}
    
function addNewPlayer($conn, $steamid){
    $steamid = $steamid & 0xffffffff;

    $response = file_get_contents('https://api.opendota.com/api/players/'.$steamid.'');
    $decoded = json_decode($response);
    
    $profile = $decoded->profile;
    
    $account_id = $profile->account_id;
    $steamid = $profile->steamid;
    
    $personaname = $profile->personaname;
    $personaname = escapeString($conn, $personaname);
    
    $steamid = $profile->steamid;
    $avatarUrl = $profile->avatarfull;
    $avatarUrl = escapeString($conn, $avatarUrl);

    
    $sql = "INSERT INTO Player (account_id, personaname, steamid, avatar) VALUES (".$account_id . ", '" . $personaname . "', " . $steamid . ", '" . $avatarUrl . "')";
    $result = $conn->query($sql);

    //var_dump($result);
}

function printProTrendsTable(){
    $heroStatsResponse = file_get_contents('https://api.opendota.com/api/heroStats?api_key=65a96d82-0ad7-462f-87bf-07b10e6007be');
    $heroStatsResponseDecoded = json_decode($heroStatsResponse);
    $conn = dbConnect();
    foreach($heroStatsResponseDecoded as $heroStat){
        $hero_id = $heroStat -> id;
        $picks = $heroStat -> pro_pick;
        $bans = $heroStat -> pro_ban;
        $wins = $heroStat -> pro_win;
        $losses = $picks - $wins;
        if($picks!=0){
        $winRate = $wins / $picks;
        $winRate = round($winRate, 2);
        $lossRate = $losses / $picks;
        $lossRate = round($lossRate, 2);
            print "<tr>";
            printHeroIcon($conn, $hero_id);
            print   "<td>".$picks."</td>".
                    "<td>".$bans."</td>".
                    "<td>".$wins."</td>".
                    "<td>".$losses."</td>".
                    "<td>".$winRate."</td>".
                    "<td>".$lossRate."</td>";
            print "</tr>";
        }
        
        
    }
}

function printHeroIcon($conn, $hero_id) {
    $sql = "SELECT * FROM Hero WHERE ".$hero_id." = hero_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "<td><img src='".$row['hero_icon']."'> ".$row['hero_name']."</td>";
        }
    }
    else{
        print "<td>".$row['hero_id']."</td>";
    }
}

    
    
    
?>




</html>
