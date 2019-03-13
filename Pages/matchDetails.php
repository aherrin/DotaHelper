<?php
    session_start();
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

    <!-- Navbar Search 
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>-->

  <!-- Navbar 
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger">9+</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-envelope fa-fw"></i>
          <span class="badge badge-danger">7</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">Settings</a>
          <a class="dropdown-item" href="#">Activity Log</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>
    -->
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

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Overview</li>
        </ol>
    

<!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Match Details</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Hero ID</th>
                    <th>Player</th>
                    <th>Kills</th>
                    <th>Deaths</th>
                    <th>Assists</th>
                    <th>KDA</th>
                    <th>Last Hits</th>
                    <th>Denies</th>
                    <th>Level</th>
                    <th>XPM</th>
                    <th>Total Gold</th>
                    <th>GPM</th>
                    <th>Ancient Kills</th>
                    <th>Hero Damage</th>
                    <th>Hero Healing</th>
                    <th>Tower Damage</th>
                    <th>Courier Kills</th>
                    <th>Observer Uses</th>
                    <th>Sentry Uses</th>
                  </tr>
                </thead>
                <tbody>
                    <?php

                        $conn = dbConnect();
                        $matchID = $_GET["matchID"];
                        printMatchDetailsTable($conn, $matchID);
                        
                    ?>

                </tbody>
              </table>
              <!--<canvas id="bar-chart" width="550" height="200"></canvas>
              <script>
              new Chart(document.getElementById("bar-chart"), {
                type: 'bar',
                data: {
                  labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
                  datasets: [
                    {
                      label: "Population (millions)",
                      backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                      data: [2478,5267,734,784,433]
                    }
                  ]
                },
                options: {
                  legend: { display: false },
                  title: {
                    display: true,
                    text: 'Predicted world population (millions) in 2050'
                  }
                }
            });
            </script>!-->
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

      </div>

<?php


//----------------------------------FUNCTIONS----------------------------------

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

function printMatchOverview() {
    
}

function printMatchDetailsTable($conn, $match_id){   
    $playerGameSql = "SELECT * FROM PlayerGame WHERE ".$match_id." = match_id";
    $playerGameResult = $conn->query($playerGameSql);
    
    $gameSql = "SELECT * FROM Game WHERE ".$match_id." = match_id";
    $gameResult = $conn->query($gameSql);
    
    if ($gameResult->num_rows > 0) {
        // output data of each row
        while($row = $gameResult->fetch_assoc()) {
            if ($row["radiantWin"] == 1) {
                $winner = "Radiant";
            } else {
                $winner = "Dire";
            }
        }
    } 
    else{
           echo "0 results.\n";
    }
    radiantRows($conn, $match_id);
    
    print "<tr>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
        "<td></td>".
    "</tr>";
    
    direRows($conn, $match_id);
}

function radiantRows($conn, $match_id) {
    $playerGameSql = "SELECT * FROM PlayerGame WHERE ".$match_id." = match_id";
    $playerGameResult = $conn->query($playerGameSql);
    
    if ($playerGameResult->num_rows > 0) {
        // output data of each row
        while($row = $playerGameResult->fetch_assoc()) {
            if ($row["isRadiant"] == 1) {
                print "<tr bgcolor='#bfed87'>".
                    "<td>".$row['hero_id']."</td>".
                    "<td>".$row['personaname']."</td>".
                    "<td>".$row['kills']."</td>".
                    "<td>".$row['deaths']."</td>".
                    "<td>".$row['assists']."</td>".
                    "<td>".$row['kda']."</td>".
                    "<td>".$row['last_hits']."</td>".
                    "<td>".$row['denies']."</td>".
                    "<td>".$row['level']."</td>".
                    "<td>".$row['xp_per_min']."</td>".
                    "<td>".$row['total_gold']."</td>".
                    "<td>".$row['gold_per_min']."</td>".
                    "<td>".$row['ancient_kills']."</td>".
                    "<td>".$row['hero_damage']."</td>".
                    "<td>".$row['hero_healing']."</td>".
                    "<td>".$row['tower_damage']."</td>".
                    "<td>".$row['courier_kills']."</td>".
                    "<td>".$row['observer_uses']."</td>".
                    "<td>".$row['sentry_uses']."</td>".
                "</tr>";
            }
            
        }
    } 
    else{
           echo "0 results.\n";
    }
}

function direRows($conn, $match_id) {
    $playerGameSql = "SELECT * FROM PlayerGame WHERE ".$match_id." = match_id";
    $playerGameResult = $conn->query($playerGameSql);
    
    if ($playerGameResult->num_rows > 0) {
        // output data of each row
        while($row = $playerGameResult->fetch_assoc()) {
            if ($row["isRadiant"] == 0) {
                print "<tr bgcolor='#ff9191'>".
                    "<td>".$row['hero_id']."</td>".
                    "<td>".$row['personaname']."</td>".
                    "<td>".$row['kills']."</td>".
                    "<td>".$row['deaths']."</td>".
                    "<td>".$row['assists']."</td>".
                    "<td>".$row['kda']."</td>".
                    "<td>".$row['last_hits']."</td>".
                    "<td>".$row['denies']."</td>".
                    "<td>".$row['level']."</td>".
                    "<td>".$row['xp_per_min']."</td>".
                    "<td>".$row['total_gold']."</td>".
                    "<td>".$row['gold_per_min']."</td>".
                    "<td>".$row['ancient_kills']."</td>".
                    "<td>".$row['hero_damage']."</td>".
                    "<td>".$row['hero_healing']."</td>".
                    "<td>".$row['tower_damage']."</td>".
                    "<td>".$row['courier_kills']."</td>".
                    "<td>".$row['observer_uses']."</td>".
                    "<td>".$row['sentry_uses']."</td>".
                "</tr>";
            }
            
        }
    } 
    else{
           echo "0 results.\n";
    }
}
?>
