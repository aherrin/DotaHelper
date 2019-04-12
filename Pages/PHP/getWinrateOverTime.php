<?php
session_start();
header('Content-Type: application/json');

$conn = dbConnect();

getWinsOverTime($conn);



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

function getWinsOverTime($conn){
    $sql = "SELECT start_time, radiant_win, isRadiant FROM PlayerGame, Game, Player where Player.steamid = ".$_SESSION['steamID']." and PlayerGame.account_id = Player.account_id and PlayerGame.match_id = Game.match_id ORDER BY start_time";
    //$sql = "SELECT playerTeam, radiantWin, timestamp FROM PlayerGame, Game where PlayerGame.matchID = Game.matchID and steamID = 88748957";
    $result = $conn->query($sql);
    $data = array();
    if ($result->num_rows > 0) {
        // output data of each row
        foreach($result as $row){
            $temp = array("start_time"=>$row['start_time'], "MYstring"=>date("M Y", $row['start_time']), "radiant_win"=>$row['radiant_win'], "isRadiant"=>$row['isRadiant']);
            $data[] = $temp;
        }
    }
    print json_encode($data);
    
    

    
}


?>