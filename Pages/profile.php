<?php
    session_start();
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <!-- Bootstrap core JavaScript-->
    <script src="./vendor/jquery/jquery.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Page level plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="./vendor/datatables/jquery.dataTables.js"></script>
    <script src="./vendor/datatables/dataTables.bootstrap4.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="./js/sb-admin.min.js"></script>
    <script src="./vendor/jquery/jquery.js" type="text/javascript"></script>
    <script src="./vendor/datatables/jquery.dataTables.js" type="text/javascript"></script>

    <script src="./js/demo/datatables-demo.js"></script>
    <script src="./js/profilePage.js"></script>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Dota Helper - Profile</title>
    
    <link href="./vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="./vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="./css/sb-admin.css" rel="stylesheet">
    
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
            <div class="card mb-3">
                <div class="card-header">
                    <i class="far fa-address-card"></i>
                    <?php
                        $conn = dbConnect();
                        printPlayerName($conn, $_SESSION["steamID"]);
                    ?>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2" id="avatar" style="text-align: center; vertical-align: middle;">
                            <?php
                                $conn = dbConnect();
                                printPlayerAvatar($conn, $_SESSION["steamID"]);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <h5>Most Played Hero</h5>
                            <?php
                                $conn = dbConnect();
                                getMostPlayedHero($conn, $_SESSION["steamID"]);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <h5>Total Matches: </h5>
                            <?php
                                $conn = dbConnect();
                                printNumMatches($conn, $_SESSION["steamID"]);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <h5>Average KDA</h5>
                            <?php
                                $conn = dbConnect();
                                printAvgKDA($conn, $_SESSION["steamID"]);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <h5>Average GPM</h5>
                            <?php
                                $conn = dbConnect();
                                printAvgGPM($conn, $_SESSION["steamID"]);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <h5>Average XPM</h5>
                            <?php
                                $conn = dbConnect();
                                printAvgXPM($conn, $_SESSION["steamID"]);
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer small text-muted">
                    <div>
                        <a href="./PHP/refreshGameData.php" class="btn btn-info" role="button">Populate Database</a>
                        Add more of your games to our database. (This will improve the accuracy and usefulness of DotaHelper)
                    </div>
                </div>
            </div>
            
            <div>
                <a onclick="showRecentMatches()" class="btn btn-info" role="button">Recent Matches</a>
                <a onclick="showWinrateGraph()" class="btn btn-info" role="button">Winrate Over Time</a>
                <a onclick="showHeroStats()" class="btn btn-info" role="button">Avg Hero Stats</a>
            </div><br>
            
            <div class="card mb-3" id="recentMatches" style="margin: auto; max-width: 1500px">
                <div class="card-header">
                    <i class='fas fa-table'></i>
                    Recent Matches
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="matchesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Win/Loss</th>
                                    <th>Match ID</th>
                                    <th>Date</th>
                                    <th>Hero</th>
                                    <th>GPM</th>
                                    <th>XPM</th>
                                    <th>Kills</th>
                                    <th>Deaths</th>
                                    <th>Assists</th>
                                    <th>Last Hits</th>
                                    <th>Denies</th>
                                </tr>
                            </thead>
                            <tbody id="recentMatchesData">
                                <?php
                                $conn = dbConnect();
                                printRecentMatches($conn);
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Win/Loss</td>
                                    <td>Match ID</td>
                                    <td>Date</td>
                                    <td>Hero</td>
                                    <td>GPM</td>
                                    <td>XPM</td>
                                    <td>Kills</td>
                                    <td>Deaths</td>
                                    <td>Assists</td>
                                    <td>Last Hits</td>
                                    <td>Denies</td>
                                </tr>
                            </thfoot>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer small text-muted">NOTE: Only games stored in the DotaHelper database are shown.</div>
            </div>
            
            <div class="card mb-3" id="winrateGraph" style="margin: auto; max-width: 1000px">
                <div class="card-header">
                    <i class='far fa-chart-bar'></i>
                    Winrate Over Time
                </div>
                
                <div class="card-body">
                    <canvas id='winrateCanvas'></canvas>
                </div>
                
                <div class="card-footer small text-muted" id="vizFooter">NOTE: Only games stored in the DotaHelper database are calculated.</div>
            </div>
            
            <div class="card mb-3" id="heroStats" style="margin: auto; max-width: 1500px">
                <div class="card-header">
                    <i class='fas fa-table'></i>
                    Average Hero Stats
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="heroTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Hero</th>
                                    <th>Total Games</th>
                                    <th>Winrate</th>
                                    <th>Kills</th>
                                    <th>Deaths</th>
                                    <th>Assists</th>
                                    <th>KDA</th>
                                    <th>Last Hits</th>
                                    <th>Denies</th>
                                    <th>GPM</th>
                                    <th>XPM</th>
                                    <th>Hero Dmg</th>
                                    <th>Tower Dmg</th>
                                </tr>
                            </thead>
                            <tbody id="heroStatsData">
                                <?php
                                $conn = dbConnect();
                                $steamID = $_SESSION['steamID'];
                                $account_id = getPlayerAccountID($conn, $steamID);
                                printHeroStats($conn, $account_id);
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Hero</td>
                                    <td>Total Games</td>
                                    <td>Winrate</td>
                                    <td>Kills</td>
                                    <td>Deaths</td>
                                    <td>Assists</td>
                                    <td>KDA</td>
                                    <td>Last Hits</td>
                                    <td>Denies</td>
                                    <td>GPM</td>
                                    <td>XPM</td>
                                    <td>Hero Dmg</td>
                                    <td>Tower Dmg</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

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

function getHeroWinrate($conn, $hero_id, $account_id) {
    $hero_games = 0;
    $hero_wins = 0;
    $heroGamesArray = getHeroGames($conn, $hero_id, $account_id);
    foreach ($heroGamesArray as $game) {
        $hero_games += 1;
        if ($game['isRadiant'] == $game['radiant_win']) {
            $hero_wins += 1;
        }
    }
    return round(($hero_wins/$hero_games)*100, 2);
}

function getHeroGames($conn, $hero_id, $account_id){
    $sql = "SELECT hero_id, isRadiant, radiant_win FROM PlayerGame, Game WHERE PlayerGame.hero_id = ".$hero_id." AND PlayerGame.account_id = ".$account_id." AND Game.match_id = PlayerGame.match_id";
    $result = $conn->query($sql);
    $data = array();
    if ($result->num_rows > 0) {
        
        // output data of each row
        foreach($result as $row){
            //if ($row['heroName'] !== null and $row['heroName'] !== "") {
                $data[] = $row;
            //}
        }
    }
    return $data;
}

function printRecentMatches($conn) {
    $steamID = $_SESSION['steamID'];
    $account_id = getPlayerAccountID($conn, $steamID);
    
    $sql = "SELECT * FROM PlayerGame WHERE ".$account_id." = account_id ORDER BY match_id DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        
        while($row = $result->fetch_assoc()) {
            print "<tr>";
            if (didPlayerWin($conn, $account_id, $row['match_id'])) {
                print "<td bgcolor='#bfed87'>WIN</td>";
            } else {
                print "<td bgcolor='#ff9191'>LOSS</td></a>";
            }
            print "<td><a href='matchDetails.php?matchID=".$row['match_id']."'> ".$row['match_id']." </a></td>";
            
            printDateOfMatch($conn, $row['match_id']);
            
            printHeroIcon($conn, $row['hero_id']);
            
            print   "<td>".$row['gold_per_min']."</td>".
                    "<td>".$row['xp_per_min']."</td>".
                    "<td>".$row['kills']."</td>".
                    "<td>".$row['deaths']."</td>".
                    "<td>".$row['assists']."</td>".
                    "<td>".$row['last_hits']."</td>".
                    "<td>".$row['denies']."</td>";
            
            print "</tr>";
            
        }
    } 
    else{
           print "Log In to see your Recent Matches.";
    }
}

function printHeroStats($conn, $account_id) {
    $sql = "SELECT 
        hero_id, 
        avg(kills), 
        avg(deaths),
        avg(assists),
        avg(kda),
        avg(last_hits),
        avg(denies),
        avg(gold_per_min),
        avg(xp_per_min),
        avg(hero_damage),
        avg(tower_damage) 
    FROM 
        PlayerGame 
    WHERE 
        PlayerGame.account_id = ".$account_id." 
    GROUP BY hero_id";
    
    $result = $conn->query($sql);
    $data = array();
    if ($result->num_rows > 0) {
        
        // output data of each row
        foreach($result as $row){
            print "<tr>";
            
            printHeroIcon($conn, $row['hero_id']);
            print "<td>".getNumHeroGames($conn, $row['hero_id'], $account_id)."</td>";
            print "<td>".getHeroWinrate($conn, $row['hero_id'], $account_id)."</td>";
            print "<td>".round($row['avg(kills)'], 2)."</td>";
            print "<td>".round($row['avg(deaths)'], 2)."<br>";
            print "<td>".round($row['avg(assists)'], 2)."<br>";
            print "<td>".round($row['avg(kda)'], 2)."<br>";
            print "<td>".round($row['avg(last_hits)'], 2)."<br>";
            print "<td>".round($row['avg(denies)'], 2)."<br>";
            print "<td>".round($row['avg(gold_per_min)'], 2)."<br>";
            print "<td>".round($row['avg(xp_per_min)'], 2)."<br>";
            print "<td>".round($row['avg(hero_damage)'], 2)."<br>";
            print "<td>".round($row['avg(tower_damage)'], 2)."<br>";
            print "</tr>";
        }
    }
    //return $data;
}

function getNumHeroGames($conn, $hero_id, $account_id) {
    $sql = "SELECT COUNT(*) FROM PlayerGame WHERE PlayerGame.hero_id = ".$hero_id." AND PlayerGame.account_id = ".$account_id."";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row['COUNT(*)'];
        }
    }
}

function printDateOfMatch($conn, $match_id) {
    $sql = "SELECT start_time FROM Game WHERE match_id = ".$match_id."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "<td>".date("Y - n - j", $row['start_time'])."</td>";
        }
    }
    else{
        print "<td>ERROR</td>";
    }
}

function didPlayerWin($conn, $account_id, $match_id) {
    $sql = "SELECT isRadiant, radiant_win FROM PlayerGame, Game WHERE ".$account_id." = account_id and PlayerGame.match_id = ".$match_id." and PlayerGame.match_id = Game.match_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            if ($row['isRadiant'] == $row['radiant_win']) {
                return true;
            } else {
                return false;
            }
        }
    } 
    else{
        return false;
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

function printPlayerAvatar($conn, $steamid) {
    $sql = "SELECT avatar FROM Player WHERE steamid = ".$steamid."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "<img height='150' width='150' src='".$row['avatar']."'>";
        }
    }
    else{
        print "Log-In to see your profile stats.";
    }
}

function printPlayerName($conn, $steamid) {
    $sql = "SELECT personaname FROM Player WHERE steamid = ".$steamid."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print $row['personaname'];
        }
    }
    else{
        print "";
    }
}

function getMostPlayedHero($conn, $steamid) {
    $sql = "SELECT hero_id, count(hero_id) AS hero_count FROM PlayerGame, Player WHERE Player.steamid = ".$steamid." AND PlayerGame.account_id = Player.account_id GROUP BY hero_id ORDER BY hero_count DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            printHeroPortrait($conn, $row['hero_id']);
            print "<u>Matches Played:</u> ".$row['hero_count']."";
        }
    }
    else{
        print "";
    }
}

function printHeroPortrait($conn, $hero_id) {
    $sql = "SELECT * FROM Hero WHERE hero_id = ".$hero_id."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "<img height='75' width='125' src='".$row['hero_img']."'> ";
            print $row['hero_name'];
            print "<br>";
        }
    }
    else{
        print "";
    }
}

function printNumMatches($conn, $steamid) {
    $sql = "SELECT count(*) AS match_count FROM PlayerGame, Player WHERE Player.steamid = ".$steamid." AND PlayerGame.account_id = Player.account_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "<h5>".$row['match_count']."</h5>";
            
            $matchesWon = getMatchesWon($conn, $steamid);
            print "<u># of Wins:</u> ".$matchesWon."<br>";
            print "<u>Current Winrate:</u> ".round(($matchesWon/$row['match_count'])*100, 2);
        }
    }
    else{
        print "";
    }
}

function getMatchesWon($conn, $steamid) {
    $sql = "SELECT PlayerGame.account_id AS account_id, match_id FROM PlayerGame, Player WHERE Player.steamid = ".$steamid." AND PlayerGame.account_id = Player.account_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $wonMatches = 0;
        while($row = $result->fetch_assoc()) {
            if (didPlayerWin($conn, $row['account_id'], $row['match_id'])) {
                $wonMatches += 1;
            }
        }
        
        return $wonMatches;
    }
    else{
        print "";
    }
}

function printAvgKDA($conn, $steamid) {
    $sql = "SELECT avg(kda) AS avg_kda, avg(kills) AS avg_kills, avg(deaths) AS avg_deaths, avg(assists) AS avg_assists FROM PlayerGame, Player WHERE Player.steamid = ".$steamid." AND PlayerGame.account_id = Player.account_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "<u>Kills:</u> ".round($row['avg_kills'], 2)."<br>";
            print "<u>Deaths:</u> ".round($row['avg_deaths'], 2)."<br>";
            print "<u>Assists:</u> ".round($row['avg_assists'], 2)."<br>";
            print "<u>(K+A)/D:</u> ".round($row['avg_kda'], 2)."<br>";
        }
    }
    else{
        print "";
    }
}

function printAvgGPM($conn, $steamid) {
    $sql = "SELECT avg(gold_per_min) AS avg_gpm FROM PlayerGame, Player WHERE Player.steamid = ".$steamid." AND PlayerGame.account_id = Player.account_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print round($row['avg_gpm'], 2);
        }
    }
    else{
        print "";
    }
}

function printAvgXPM($conn, $steamid) {
    $sql = "SELECT avg(xp_per_min) AS avg_xpm FROM PlayerGame, Player WHERE Player.steamid = ".$steamid." AND PlayerGame.account_id = Player.account_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print round($row['avg_xpm'], 2);
        }
    }
    else{
        print "";
    }
}

?>
