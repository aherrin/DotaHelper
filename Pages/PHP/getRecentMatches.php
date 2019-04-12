<?php
session_start();
header('Content-Type: application/json');

$conn = dbConnect();
printRecentMatches($conn);

//---------------------------------------------------------------------//
function dbConnect() {
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

?>