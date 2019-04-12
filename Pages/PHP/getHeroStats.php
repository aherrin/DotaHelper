<?php
session_start();
header('Content-Type: application/json');

$conn = dbConnect();
$account_id = getPlayerAccountID($conn, $_SESSION['steamID']);
printHeroStats($conn, $account_id);


//-----------FUNCTIONS-------
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

function getNumHeroGames($conn, $hero_id, $account_id) {
    $sql = "SELECT COUNT(*) FROM PlayerGame WHERE PlayerGame.hero_id = ".$hero_id." AND PlayerGame.account_id = ".$account_id."";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row['COUNT(*)'];
        }
    }
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

function getHeroIcon($conn, $hero_id){
$sql = "SELECT hero_icon FROM Hero WHERE hero_id = ".$hero_id."";
    $result = $conn->query($sql);
    $data = array();
    if ($result->num_rows > 0) {
        
        // output data of each row
        foreach($result as $row){
            return ("<img src='".$row['hero_icon']."'>");
        }
    }
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

function getHeroWinrates($conn){
    $sql = "SELECT hero_id FROM Player, PlayerGame WHERE PlayerGame.account_id = Player.account_id AND Player.steamid = ".$_SESSION['steamID']." GROUP BY hero_id"; 

    $result = $conn->query($sql);
    $data = array();
    
    if ($result->num_rows > 0) {

        // output data of each row
        foreach($result as $row){
            $hero_id = $row['hero_id'];
            $hero_games = 0;
            $hero_wins = 0;
            $heroGamesArray = getHeroGames($conn, $hero_id);
            $hero_icon = getHeroIcon($conn, $hero_id);
            foreach ($heroGamesArray as $game) {
                $hero_games += 1;
                if ($game['isRadiant'] == $game['radiant_win']) {
                    $hero_wins += 1;
                }
            }
            $hero_name = getHeroName($conn, $hero_id);
            $hero_array = array("hero_name"=>$hero_name, "winrate"=>($hero_wins/$hero_games), "hero_icon"=>$hero_icon);
            if ($hero_games >= 10) {
                $data[] = $hero_array;
            }
        }
    }
    print json_encode($data);
}

function getHeroName($conn, $hero_id) {
    $sql = "SELECT hero_name FROM Hero WHERE hero_id = ".$hero_id.""; 

    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        
        // output data of each row
        foreach($result as $row){
            return $row['hero_name'];
        }
    }
}
?>