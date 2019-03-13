<?php
    session_start();
    require('SteamLogin.php');
    //$url = SteamLogin::genUrl('http://aherrin.create.stedwards.edu/DotaHelper/Pages/index.php');
    $url = SteamLogin::genUrl();
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
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Dota Helper - Dashboard</title>
    
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    
    <link href="css/sb-admin.css" rel="stylesheet">

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
      
        <li class="nav-item active">
            <a class="nav-link" href="profile.php">
                <i class="far fa-address-card"></i>
                    <span>Profile</span>
            </a>
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

        <div class="container-fluid">

            <!-- Button for redirecting to database population php page -->
            <div class="float-right"> <a href="./PHP/refreshGameData.php" class="btn btn-info" role="button">Populate Database</a> </div>
            
        

     

        <!--WINRATE CHART 
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-table"></i>
            WinRate
            </div>
            
            <style type="text/css">
              #bar-chart {
                width: auto;
                height: auto;
              }
            </style>
            
            <div class="card-body">
                <div id="bar-chart">
                    <canvas id="mycanvas"></canvas>
                </div>
                <script src="./js/getWinrateGraph.js">
                </script>
            </div>
        </div>-->
        
                <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Recent Matches</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Match ID</th>
                    <th>Hero</th>
                    <th>GPM</th>
                    <th>XPM</th>
                    <th>Kills</th>
                    <th>Deaths</th>
                    <th>Assists</th>
                    <th>Last Hits</th>
                    <th>Denies</th>
                    <th>Team</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Match ID</th>
                    <th>Hero</th>
                    <th>GPM</th>
                    <th>XPM</th>
                    <th>Kills</th>
                    <th>Deaths</th>
                    <th>Assists</th>
                    <th>Last Hits</th>
                    <th>Denies</th>
                    <th>Team</th>
                  </tr>
                </tfoot>
                <tbody>
                    <?php  
                         $conn = dbConnect();
                        if(!isset($_SESSION['steamID'])) {
                          
                        }  
                        else {
                            printPlayerGameTable();
                        }
                    ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->

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

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>

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
    $avatarUrl = $profile->avatar;
    $avatarUrl = escapeString($conn, $avatarUrl);

    
    $sql = "INSERT INTO Player (account_id, personaname, steamid, avatar) VALUES (".$account_id . ", '" . $personaname . "', " . $steamid . ", '" . $avatarUrl . "')";
    $result = $conn->query($sql);

    //var_dump($result);
}
    
function printPlayerGameTable(){
    $steamID = $_SESSION['steamID'];
    $conn = dbConnect();
    $account_id = getPlayerAccountID($conn, $steamID);
    
    $sql = "SELECT * FROM PlayerGame WHERE ".$account_id." = account_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            print "<tr>".
                    "<td> <a href = 'matchDetails.php?matchID=".$row['match_id']."'> ".$row['match_id']."</td></a>".
                    "<td>".$row['hero_id']."</td>".
                    "<td>".$row['gold_per_min']."</td>".
                    "<td>".$row['xp_per_min']."</td>".
                    "<td>".$row['kills']."</td>".
                    "<td>".$row['deaths']."</td>".
                    "<td>".$row['assists']."</td>".
                    "<td>".$row['last_hits']."</td>".
                    "<td>".$row['denies']."</td>".
                    "<td>".$row['isRadiant']."</td>".
                  "</tr>";
            
        }
    } 
    else{
           echo "0 results.\n";
    }
    
        
        
        
}

function getPlayerAccountID($conn, $steamid) {
    $sql = "SELECT account_id FROM Player WHERE steamid = ".$steamid."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row['account_id']; 
        }
    }
    else{
        return -1;
    }
}
    
    
    
?>




</html>
