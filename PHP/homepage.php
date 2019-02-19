<?php
session_start();
$conn = dbConnect();



"<body> \n";

require ('../SteamAuthentication-4.0/steamauth/steamauth.php');

print "<center> \n";
if(!isset($_SESSION['steamid'])) {
    echo "Welcome to Dota Helper! Login Here.<br><br>";
    loginbutton(); //login button
    
}  else {
    include ('../SteamAuthentication-4.0/steamauth/userInfo.php');
    //Protected content
    echo "Welcome back " . $steamprofile['personaname'] . "</br>";
    echo "here is your avatar: </br>" . '<img src="'.$steamprofile['avatarfull'].'" title="" alt="" /><br>'; // Display their avatar!
    echo $steamprofile['steamid'];
    logoutbutton();
    
    $steamID = $steamprofile['steamid'];
    $steamID = substr($steamID, 3) - 61197960265728;
    $profileURL = $steamprofile['avatarfull'];
    $steamName = $steamprofile['personaname'];

    

    
    //print($steamID);
    
    newPlayer($conn, $steamID, $profileURL, $steamName);
    
    getLastTenMatches($conn, $steamID);
    
    printGames($conn);
    
    $url = "http://aherrin.create.stedwards.edu/research/DotaHelper/index.php";
    redirect($url);
    
}

//set 'Player' variables



/*
$response = json_decode(shell_exec("python ../getMatchHistory.py ".$_SESSION['steamid']." 2>&1"));
$matches = $response->matches;
print "</center><br> \n";

print   "<h1>Recent Matches</h1> \n".
   "<table id='matches' style='width:100%'> \n".
       "<tr> \n".
           "<th>Match ID</th> \n".
           "<th>Hero Played</th> \n".
           "<th>Lobby Type</th> \n".
       "</tr> \n";

foreach ($matches as $match) {

   $matchID = json_decode($match->match_id); //gets matchID

   $heroName = shell_exec("python ../getHeroName.py ".$matchID." ".$_SESSION['steamid']." 2>&1"); //use matchID to retrieve match details

   //$heroName = $matchDetails->players->hero_name;

  print "<tr align='center'> \n".
          "<td>".$matchID."</td> \n".
          "<td>".$heroName."</td> \n".
          "<td> [Kills] / [Deaths] / [Assists] </td> \n".
      "</tr> \n";
};

print "</table> \n".
"</body> \n";
*/

function dbConnect()
{
    $servername = "localhost";
    $username = "aherrinc_senior";
    $password = "senior123!";
    $dbname = "aherrinc_seniorProject";

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function newPlayer($conn, $steamID, $profileURL, $steamName)
{
    if (doesPlayerExist($conn, $steamID)) {
        print
              "<p>Player #" .$steamID. " is already in database.</p>\n";
    } else {
        $sql = "INSERT INTO Player (steamID, picURL, steamName) VALUES (".$steamID . ", '" . $profileURL . "', '" . $steamName . "')";
        $result = $conn->query($sql);

        if ($result == "1") {
            print
                "       <p>New Player created!</p>\n".
                "       <p>Steam ID: " . $steamID . "</p>\n".
                "       <p>Profile Pic: <img src='" .$steamprofile['avatarfull']. "' title='' alt='' /></p>\n".
                "       <p>     Link: " .$steamprofile['avatarfull']. "</p>\n".
                "       <p>Steam Name: " . $steamName . "</p>\n";
        } else {
            print
                "       <p>ERROR: Player not created.</p>\n".
                "       <p>Steam ID: " . $steamID . "</p>\n".
                "       <p>Profile Pic: <img src='" .$steamprofile['avatarfull']. "' title='' alt='' /></p>\n".
                "       <p>     Link: " .$steamprofile['avatarfull']. "</p>\n".
                "       <p>Steam Name: " . $steamName . "</p>\n";
        }
    }
    
}

function doesPlayerExist($conn, $steamID) {
   $sql = "SELECT steamID FROM Player WHERE " . $steamID . " = steamID";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
          return true;
    } else {
        return false;
    }
}

function getLastTenMatches($conn, $steamID)
{
    $response = json_decode(shell_exec("python ../getMatchHistory.py ".$steamID." 2>&1"));
    $matches = $response->matches;
    
    foreach ($matches as $match) {
        $matchID = ($match->match_id); //gets matchID
        $matchDetails = json_decode(shell_exec("python ../getMatchDetails.py ".$matchID." 2>&1")); //get detailed match data
        
        $timestamp = $matchDetails->start_time; //gets timestamp
        $radiantWin = $matchDetails->radiant_win; //gets win status
        if($radiantWin == true){
            $radiantWin = 1;
        }
        else{
            $radiantWin = 0;
        }
        $radiantScore = 0;
        $direScore = 0;
        $radiantHero1 = 'rh1';
        $radiantHero2 = 'rh2';
        $radiantHero3 = 'rh3';
        $radiantHero4 = 'rh4';
        $radiantHero5 = 'rh5';
        $direHero1 = 'dh1';
        $direHero2 = 'dh2';
        $direHero3 = 'dh3';
        $direHero4 = 'dh4';
        $direHero5 = 'dh5';
        
        tempGame($conn, $matchID);
        
        $players = ($matchDetails->players); //get list of all players in match
        //print ("<p>Player List: " . $players . "</p>\n");
        
        foreach ($players as $player) {
            $steamID = ($player->account_id);
            $heroName = ($player->hero_name);
            $gpm = ($player->gold_per_min);
            $xpm = ($player->xp_per_min);
            $kills = ($player->kills);
            $deaths = ($player->deaths);
            $assists = ($player->assists);
            $lastHits = ($player->last_hits);
            $denies = ($player->denies);
            $playerSlot = ($player->player_slot);
            //print("<p>Player Slot: " . $playerSlot . "</p>\n");
                
            if ($playerSlot == 128) {
                $playerTeam = "Dire";
                $direScore += $kills;
                $direHero1 = $heroName;
            } else if ($playerSlot == 129) {
                $playerTeam = "Dire";
                $direScore += $kills;
                $direHero2 = $heroName;
            } else if ($playerSlot == 130) {
                $playerTeam = "Dire";
                $direScore += $kills;
                $direHero3 = $heroName;
            } else if ($playerSlot == 131) {
                $playerTeam = "Dire";
                $direScore += $kills;
                $direHero4 = $heroName;
            } else if ($playerSlot == 132) {
                $playerTeam = "Dire";
                $direScore += $kills;
                $direHero5 = $heroName;
            } else if ($playerSlot == 0) {
                $playerTeam = "Radiant";
                $radiantScore += $kills;
                $radiantHero1 = $heroName;
            } else if ($playerSlot == 1) {
                $playerTeam = "Radiant";
                $radiantScore += $kills;
                $radiantHero2 = $heroName;
            } else if ($playerSlot == 2) {
                $playerTeam = "Radiant";
                $radiantScore += $kills;
                $radiantHero3 = $heroName;
            } else if ($playerSlot == 3) {
                $playerTeam = "Radiant";
                $radiantScore += $kills;
                $radiantHero4 = $heroName;
            } else if ($playerSlot == 4) {
                $playerTeam = "Radiant";
                $radiantScore += $kills;
                $radiantHero5 = $heroName;
            } 
            
            newPlayerGame($conn, $steamID, $matchID, $heroName, $gpm, $xpm, $kills, $deaths, $assists, $lastHits, $denies, $playerTeam);
        }
        
        updateGame($conn, $matchID, $timestamp, $radiantWin, $radiantScore, $direScore, $radiantHero1, $radiantHero2, $radiantHero3, $radiantHero4, $radiantHero5, $direHero1, $direHero2, $direHero3, $direHero4, $direHero5);
    }

}

function newGame($conn, $matchID, $timestamp, $radiantWin, $radiantScore, $direScore, $radiantHero1, $radiantHero2, $radiantHero3, $radiantHero4, $radiantHero5, $direHero1, $direHero2, $direHero3, $direHero4, $direHero5) {
    
    if (doesGameExist($conn, $matchID)) {
        print
              "<p>Game #" .$matchID. " is already in database.</p>\n";
    } else {
        $sql = "INSERT INTO Game (matchID, timestamp, radiantWin, radiantScore, direScore, radiantHero1, radiantHero2, radiantHero3, radiantHero4, radiantHero5, direHero1, direHero2, direHero3, direHero4, direHero5) VALUES (" .$matchID. ", " .$timestamp. ", " .$radiantWin. ", " .$radiantScore. ", " .$direScore. ", '" .$radiantHero1. "', '" .$radiantHero2. "', '" .$radiantHero3. "', '" .$radiantHero4. "', '" .$radiantHero5. "', '" .$direHero1. "', '" .$direHero2. "', '" .$direHero3. "', '" .$direHero4. "', '" .$direHero5 . "')";
        $result = $conn->query($sql);

        if ($result == "1") {
            
            /*print
                "        <p>New Game created!</p>\n".
                "        <p>Match ID: " . $matchID . "</p>\n".
                "        <p>Timestamp: " . $timestamp . "</p>\n".
                "        <p>Radiant Win: " . $radiantWin . "</p>\n".
                "        <p>Radiant Score: " . $radiantScore . "</p>\n".
                "        <p>Dire Score: " . $direScore . "</p>\n".
                "        <p>Radiant Hero #1: " . $radiantHero1 . "</p>\n".
                "        <p>Radiant Hero #2: " . $radiantHero2 . "</p>\n".
                "        <p>Radiant Hero #3: " . $radiantHero3 . "</p>\n".
                "        <p>Radiant Hero #4: " . $radiantHero4 . "</p>\n".
                "        <p>Radiant Hero #5: " . $radiantHero5 . "</p>\n".
                "        <p>Dire Hero #1: " . $direHero1 . "</p>\n".
                "        <p>Dire Hero #2: " . $direHero2 . "</p>\n".
                "        <p>Dire Hero #3: " . $direHero3 . "</p>\n".
                "        <p>Dire Hero #4: " . $direHero4 . "</p>\n".
                "        <p>Dire Hero #5: " . $direHero5 . "</p>\n";*/
        } else {
            print
                "        <p>ERROR: Game not created.</p>\n".
                "        <p>Match ID: " . $matchID . "</p>\n";
                /*"        <p>Timestamp: " . date('m/d/Y H:i:s', $timestamp) . "</p>\n".
                "        <p>Radiant Win?: " . $radiantWin . "</p>\n".
                "        <p>Radiant Score: " . $radiantScore . "</p>\n".
                "        <p>Dire Score: " . $direScore . "</p>\n".
                "        <p>Radiant Hero #1: " . $radiantHero1 . "</p>\n".
                "        <p>Radiant Hero #2: " . $radiantHero2 . "</p>\n".
                "        <p>Radiant Hero #3: " . $radiantHero3 . "</p>\n".
                "        <p>Radiant Hero #4: " . $radiantHero4 . "</p>\n".
                "        <p>Radiant Hero #5: " . $radiantHero5 . "</p>\n".
                "        <p>Dire Hero #1: " . $direHero1 . "</p>\n".
                "        <p>Dire Hero #2: " . $direHero2 . "</p>\n".
                "        <p>Dire Hero #3: " . $direHero3 . "</p>\n".
                "        <p>Dire Hero #4: " . $direHero4 . "</p>\n".
                "        <p>Dire Hero #5: " . $direHero5 . "</p>\n";*/
        }
    }
}

function doesGameExist($conn, $matchID) {
    $sql = "SELECT matchID FROM Game WHERE '" . $matchID . "' = matchID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function newPlayerGame($conn, $steamID, $matchID, $heroName, $gpm, $xpm, $kills, $deaths, $assists, $lastHits, $denies, $playerTeam) {
     if (doesPlayerGameExist($conn, $steamID, $matchID)) {
        print
              "<p>Player".$steamID." Game #" .$matchID. " is already in database.</p>\n";
    } else {
        $sql = "INSERT INTO PlayerGame (steamID, matchID, heroName, gpm, xpm, kills, deaths, assists, lastHits, denies, playerTeam) VALUES (" .$steamID. ", " .$matchID. ", '" .$heroName. "', " .$gpm. ", " .$xpm. ", " .$kills. ", " .$deaths. ", " .$assists. ", " .$lastHits. ", " .$denies. ", '" .$playerTeam."')";
        $result = $conn->query($sql);
    }
        
}

function doesPlayerGameExist($conn, $steamID, $matchID) {
    $sql = "SELECT steamdID, matchID FROM PlayerGame WHERE " . $steamdID . " = steamID AND " . $matchID . " = matchID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function printGames($conn){
    $sql = "SELECT * FROM Game";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            if ($row["radiantWin"] == 1) {
                $winner = "Radiant";
            } else {
                $winner = "Dire";
            }
            print "matchID: " .$row["matchID"]. "   -   Date: " .date('m/d/Y', $row["timestamp"]). "   -   Winner: " .$winner. "<br> \n".
            "Radiant Score: " .$row["radiantScore"]. "   -----   Dire Score: " .$row["direScore"]. "<br> \n".
            "RH1: " .$row["radiantHero1"]. "   -----   DH1: " .$row["direHero1"]. "<br> \n".
            "RH2: " .$row["radiantHero2"]. "   -----   DH2: " .$row["direHero2"]. "<br> \n".
            "RH3: " .$row["radiantHero3"]. "   -----   DH3: " .$row["direHero3"]. "<br> \n".
            "RH4: " .$row["radiantHero4"]. "   -----   DH4: " .$row["direHero4"]. "<br> \n".
            "RH5: " .$row["radiantHero5"]. "   -----   DH5: " .$row["direHero5"]. "<br><br> \n";
        }
    } 
    else{
           echo "0 results.\n";
    }
    
}

function redirect($url){
    if (headers_sent()){
        die('<script type="text/javascript">window.location=\''.$url.'\';</script>');  
    }
    else{
      header('Location: ' . $url);
      die();
    }    
}

function updateGame($conn, $matchID, $timestamp, $radiantWin, $radiantScore, $direScore, $radiantHero1, $radiantHero2, $radiantHero3, $radiantHero4, $radiantHero5, $direHero1, $direHero2, $direHero3, $direHero4, $direHero5) {
    

    $sql = "UPDATE Game SET timestamp=".$timestamp.", radiantWin=".$radiantWin.", radiantScore=".$radiantScore.", direScore=".$direScore.", radiantHero1='".$radiantHero1."', radiantHero2='".$radiantHero2."', radiantHero3='".$radiantHero3."', radiantHero4='".$radiantHero4."', radiantHero5='".$radiantHero5."', direHero1='".$direHero1."', direHero2='".$direHero2."', direHero3='".$direHero3."', direHero4='".$direHero4."', direHero5='".$direHero5."' WHERE matchID = ".$matchID;
    $result = $conn->query($sql);

    if ($result == "1") {
        
        /*print
            "        <p>New Game created!</p>\n".
            "        <p>Match ID: " . $matchID . "</p>\n".
            "        <p>Timestamp: " . $timestamp . "</p>\n".
            "        <p>Radiant Win: " . $radiantWin . "</p>\n".
            "        <p>Radiant Score: " . $radiantScore . "</p>\n".
            "        <p>Dire Score: " . $direScore . "</p>\n".
            "        <p>Radiant Hero #1: " . $radiantHero1 . "</p>\n".
            "        <p>Radiant Hero #2: " . $radiantHero2 . "</p>\n".
            "        <p>Radiant Hero #3: " . $radiantHero3 . "</p>\n".
            "        <p>Radiant Hero #4: " . $radiantHero4 . "</p>\n".
            "        <p>Radiant Hero #5: " . $radiantHero5 . "</p>\n".
            "        <p>Dire Hero #1: " . $direHero1 . "</p>\n".
            "        <p>Dire Hero #2: " . $direHero2 . "</p>\n".
            "        <p>Dire Hero #3: " . $direHero3 . "</p>\n".
            "        <p>Dire Hero #4: " . $direHero4 . "</p>\n".
            "        <p>Dire Hero #5: " . $direHero5 . "</p>\n";*/
    } 
    else {
        print
            "        <p>ERROR: Game not created.</p>\n".
            "        <p>Match ID: " . $matchID . "</p>\n";
            /*"        <p>Timestamp: " . date('m/d/Y H:i:s', $timestamp) . "</p>\n".
            "        <p>Radiant Win?: " . $radiantWin . "</p>\n".
            "        <p>Radiant Score: " . $radiantScore . "</p>\n".
            "        <p>Dire Score: " . $direScore . "</p>\n".
            "        <p>Radiant Hero #1: " . $radiantHero1 . "</p>\n".
            "        <p>Radiant Hero #2: " . $radiantHero2 . "</p>\n".
            "        <p>Radiant Hero #3: " . $radiantHero3 . "</p>\n".
            "        <p>Radiant Hero #4: " . $radiantHero4 . "</p>\n".
            "        <p>Radiant Hero #5: " . $radiantHero5 . "</p>\n".
            "        <p>Dire Hero #1: " . $direHero1 . "</p>\n".
            "        <p>Dire Hero #2: " . $direHero2 . "</p>\n".
            "        <p>Dire Hero #3: " . $direHero3 . "</p>\n".
            "        <p>Dire Hero #4: " . $direHero4 . "</p>\n".
            "        <p>Dire Hero #5: " . $direHero5 . "</p>\n";*/
    }
    
}

function tempGame($conn, $matchID) {
    
    if (doesGameExist($conn, $matchID)) {
        print
              "<p>Game #" .$matchID. " is already in database.</p>\n";
    } else {
        $sql = "INSERT INTO Game (matchID) VALUES (" .$matchID. ")";
        $result = $conn->query($sql);

        if ($result == "1") {
            
            /*print
                "        <p>New Game created!</p>\n".
                "        <p>Match ID: " . $matchID . "</p>\n".
                "        <p>Timestamp: " . $timestamp . "</p>\n".
                "        <p>Radiant Win: " . $radiantWin . "</p>\n".
                "        <p>Radiant Score: " . $radiantScore . "</p>\n".
                "        <p>Dire Score: " . $direScore . "</p>\n".
                "        <p>Radiant Hero #1: " . $radiantHero1 . "</p>\n".
                "        <p>Radiant Hero #2: " . $radiantHero2 . "</p>\n".
                "        <p>Radiant Hero #3: " . $radiantHero3 . "</p>\n".
                "        <p>Radiant Hero #4: " . $radiantHero4 . "</p>\n".
                "        <p>Radiant Hero #5: " . $radiantHero5 . "</p>\n".
                "        <p>Dire Hero #1: " . $direHero1 . "</p>\n".
                "        <p>Dire Hero #2: " . $direHero2 . "</p>\n".
                "        <p>Dire Hero #3: " . $direHero3 . "</p>\n".
                "        <p>Dire Hero #4: " . $direHero4 . "</p>\n".
                "        <p>Dire Hero #5: " . $direHero5 . "</p>\n";*/
        } else {
            print
                "        <p>ERROR: Game not created.</p>\n".
                "        <p>Match ID: " . $matchID . "</p>\n";
                /*"        <p>Timestamp: " . date('m/d/Y H:i:s', $timestamp) . "</p>\n".
                "        <p>Radiant Win?: " . $radiantWin . "</p>\n".
                "        <p>Radiant Score: " . $radiantScore . "</p>\n".
                "        <p>Dire Score: " . $direScore . "</p>\n".
                "        <p>Radiant Hero #1: " . $radiantHero1 . "</p>\n".
                "        <p>Radiant Hero #2: " . $radiantHero2 . "</p>\n".
                "        <p>Radiant Hero #3: " . $radiantHero3 . "</p>\n".
                "        <p>Radiant Hero #4: " . $radiantHero4 . "</p>\n".
                "        <p>Radiant Hero #5: " . $radiantHero5 . "</p>\n".
                "        <p>Dire Hero #1: " . $direHero1 . "</p>\n".
                "        <p>Dire Hero #2: " . $direHero2 . "</p>\n".
                "        <p>Dire Hero #3: " . $direHero3 . "</p>\n".
                "        <p>Dire Hero #4: " . $direHero4 . "</p>\n".
                "        <p>Dire Hero #5: " . $direHero5 . "</p>\n";*/
        }
    }
}


    

?>