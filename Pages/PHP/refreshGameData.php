<?php
session_start();

ignore_user_abort(TRUE);
header("Location: ../index.php");

if (isset($_SESSION['steamID'])) {
    //refreshData();
    
    $conn = dbConnect();
    
    $steamid = $_SESSION['steamID'];
    //$steamid32 = $steamid & 0xffffffff;
    
    $account_id = getPlayerAccountID($conn, $steamid);
    
    $playerMatchesResponse = file_get_contents('http://api.opendota.com/api/players/'.$account_id.'/matches?limit=50');
    $playerMatchesResponseDecoded = json_decode($playerMatchesResponse);
        
    foreach($playerMatchesResponseDecoded as $match){
        $match_id = $match->match_id;
        //countPlayerGame($conn, $match_id);
    
        $game_mode = $match->game_mode;
        $lobby_type = $match->lobby_type;
        $start_time = $match->start_time;
        $duration = $match->duration;
        $radiant_win = $match->radiant_win;
        if($radiant_win) 
            $radiant_win = 1;
        else 
            $radiant_win = 0;
        
        $matchDetailsResponse = file_get_contents('http://api.opendota.com/api/matches/'.$match_id.'');
        $matchDetailsResponseDecoded = json_decode($matchDetailsResponse);
        
        $players = $matchDetailsResponseDecoded->players;
        
        $radiant_score = $matchDetailsResponseDecoded->radiant_score;
        $dire_score = $matchDetailsResponseDecoded->dire_score;
        
        addNewGame($conn, $match_id, $game_mode, $lobby_type, $start_time, $duration, $radiant_win, $dire_score, $radiant_score);
        
        foreach($players as $player){
            $deaths = $player->deaths;
            $assists = $player->assists;
            $denies = $player->denies;
            $gold = $player->gold;
            $gold_per_min = $player->gold_per_min;
            $hero_damage = $player->hero_damage;
            $hero_healing = $player->hero_healing;
            $hero_id = $player->hero_id;
            $item_0 = $player->item_0;
            $item_1 = $player->item_1;
            $item_2 = $player->item_2;
            $item_3 = $player->item_3;
            $item_4 = $player->item_4;
            $item_5 = $player->item_5;
            $kills = $player->kills;
            $last_hits = $player->last_hits;
            $level = $player->level;
            $tower_damage = $player->tower_damage;
            $xp_per_min = $player->xp_per_min;
            $personaname = $player->personaname;
            $isRadiant = $player->isRadiant;
            if($isRadiant)
                $isRadiant = 1;
            else
                $isRadiant = 0;
                
            $total_gold = $player->total_gold;
            $total_xp = $player->total_xp;
            $kills_per_min = $player->kills_per_min;
            $kda = $player->kda;
            $courier_kills = $player->courier_kills;
            $ancient_kills = $player->ancient_kills;
            $observer_uses = $player->observer_uses;
            $sentry_uses = $player->sentry_uses;
            
            $player_slot = $player->player_slot;
            $account_id = $player->account_id;
            
            if(!$courier_kills) {
                echo "Courier Kills: ";
                $courier_kills = 0;
                echo $courier_kills;
                echo "<br>";
            }
            if(!$ancient_kills) {
                echo "Ancient Kills: ";
                $ancient_kills = 0;
                echo $ancient_kills;
                echo "<br>";
            }
            if(!$observer_uses) {
                echo "Observer Uses: ";
                $observer_uses = 0;
                echo $observer_uses;
                echo "<br>";
            }
            if(!$sentry_uses) {
                echo "Sentry Uses: ";
                $sentry_uses = 0;
                echo $sentry_uses;
                echo "<br>";
            }
            if(!$kills_per_min) {
                echo "Kills Per Min: ";
                $kills_per_min = 0;
                echo $kills_per_min;
                echo "<br>";
            }
            if(!$account_id) {
                echo "Temp Player: ";
                $account_id = $match_id + $player_slot;
                $personaname = "Anonymous";
                echo $account_id;
                echo "<br>";
                
                addTempPlayer($conn, $account_id, $personaname, 1, "avatar_url");
            }
            
            //NEEDS TO BE IMPLEMENTED
            /*if(doesPlayerGameExist($conn, $match_id, $account_id)) {
                updatePlayerGame($conn, $match_id, $account_id, $personaname, $isRadiant, $player_slot, $hero_id, $kills, $deaths, $assists, $kda, $kills_per_min, $last_hits, $denies, $gold, $gold_per_min, $total_gold, $total_xp, $xp_per_min, $level, $hero_damage, $hero_healing, $tower_damage, $courier_kills, $ancient_kills, $observer_uses, $sentry_uses, $item_0, $item_1, $item_2, $item_3, $item_4, $item_5);
            } else {
                addNewPlayerGame($conn, $match_id, $account_id, $personaname, $isRadiant, $player_slot, $hero_id, $kills, $deaths, $assists, $kda, $kills_per_min, $last_hits, $denies, $gold, $gold_per_min, $total_gold, $total_xp, $xp_per_min, $level, $hero_damage, $hero_healing, $tower_damage, $courier_kills, $ancient_kills, $observer_uses, $sentry_uses, $item_0, $item_1, $item_2, $item_3, $item_4, $item_5);
            }*/
            
            if (!doesPlayerExistAcc($conn, $account_id))
                addNewPlayer($conn, $account_id);
            else {
                //updatePlayer($conn, $account_id);
            }
            
            echo "Player: ";
            echo $account_id;
            echo "<br>";
            echo "Personaname: ";
            echo $personaname;
            echo "<br>";
            echo "<br>";
            addNewPlayerGame($conn, $match_id, $account_id, $personaname, $isRadiant, $player_slot, $hero_id, $kills, $deaths, $assists, $kda, $kills_per_min, $last_hits, $denies, $gold, $gold_per_min, $total_gold, $total_xp, $xp_per_min, $level, $hero_damage, $hero_healing, $tower_damage, $courier_kills, $ancient_kills, $observer_uses, $sentry_uses, $item_0, $item_1, $item_2, $item_3, $item_4, $item_5);
            echo "-----------------------------------------------------------------------------------<br>";
            echo "<br>";
        }
    }
    
    //header("Location: ../index.php");
}



/*----------FUNCTIONS-----------*/
function refreshData() {
    echo "REFRESH DATA";
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

function doesPlayerExistAcc($conn, $account_id) {
    $sql = "Select * FROM Player WHERE account_id = ".$account_id."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true;
    }
    else{
        return false;
    }
}

/*function countPlayerGame($conn, $match_id) {
    $sql = "Select COUNT(*) FROM PlayerGame WHERE match_id = ".$match_id."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
        	echo ">>>PlayerGame Count: ";
            echo $row['COUNT(*)'];
            echo "<br>";
            echo "<br>";
            return $row['COUNT(*)'];
        }
        
    }
    else{
        return false;
    }
}*/

function escapeString($conn, $string) {
    $retval = mysqli_real_escape_string($conn, $string);
    return $retval;
}

function addNewPlayer($conn, $account_id){
    echo "addNewPlayer() was called.<br>";
    //$steamID32 = $steamID & 0xffffffff;
    echo $account_id;
    echo "<br>";
    
    $response = file_get_contents('http://api.opendota.com/api/players/'.$account_id.'');
    $decoded = json_decode($response);
    
    $profile = $decoded->profile;
    
    //$account_id = $profile->account_id;
    $personaname = $profile->personaname;
    $personaname = escapeString($conn, $personaname);
    echo $personaname;
    echo "<br>";
    
    $steamid = $profile->steamid;
    $avatarUrl = $profile->avatar;
    $avatarUrl = escapeString($conn, $avatarUrl);
    echo $avatarUrl;
    echo "<br>";
    
    $sql = "INSERT INTO Player (account_id, personaname, steamid, avatar) VALUES (".$account_id . ", '" . $personaname . "', " . $steamid . ", '" . $avatarUrl . "')";
    $result = $conn->query($sql);
    
    echo $sql;
    echo "<br>";
    
    echo ">>>addNewPlayer(): ";
    var_dump($result);
    echo "<br>";
    echo "<br>";
}

function addTempPlayer($conn, $account_id, $personaname, $steamid, $avatarUrl){
    $sql = "INSERT INTO Player (account_id, personaname, steamid, avatar) VALUES (".$account_id . ", '" . $personaname . "', " . $steamid . ", '" . $avatarUrl . "')";
    $result = $conn->query($sql);
    
    echo ">>>addTempPlayer(): ";
    var_dump($result);
    echo "<br>";
    echo "<br>";
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

function addNewGame($conn, $match_id, $game_mode, $lobby_type, $start_time, $duration, $radiant_win, $dire_score, $radiant_score){
    $sql = "INSERT INTO Game (match_id, game_mode, lobby_type, start_time, duration, radiant_win, dire_score, radiant_score) VALUES (".$match_id.", ".$game_mode.", ".$lobby_type.", ".$start_time.", ".$duration.", ".$radiant_win.", ".$dire_score.", ".$radiant_score.")";
    $result = $conn->query($sql);
}

function addNewPlayerGame($conn, $match_id, $account_id, $personaname, $isRadiant, $player_slot, $hero_id, $kills, $deaths, $assists, $kda, $kills_per_min, $last_hits, $denies, $gold, $gold_per_min, $total_gold, $total_xp, $xp_per_min, $level, $hero_damage, $hero_healing, $tower_damage, $courier_kills, $ancient_kills, $observer_uses, $sentry_uses, $item_0, $item_1, $item_2, $item_3, $item_4, $item_5) {
    echo "Match ID: ";
    echo $match_id;
    echo "<br>";
    
    echo "Account ID: ";
    echo $account_id;
    echo "<br>";
    
    $personaname = escapeString($conn, $personaname);
    echo "Persona Name: ";
    echo $personaname;
    echo "<br>";
    
    echo "Is Radiant: ";
    echo $isRadiant;
    echo "<br>";
    
    echo "Player Slot: ";
    echo $player_slot;
    echo "<br>";
    
    echo "Hero ID: ";
    echo $hero_id;
    echo "<br>";
    
    echo "Kills: ";
    echo $kills;
    echo "<br>";
    
    echo "Deaths: ";
    echo $deaths;
    echo "<br>";
    
    echo "Assists: ";
    echo $assists;
    echo "<br>";
    
    echo "KDA: ";
    echo $kda;
    echo "<br>";
    
    echo "Kills Per Min: ";
    echo $kills_per_min;
    echo "<br>";
    
    echo "Last Hits: ";
    echo $last_hits;
    echo "<br>";
    
    echo "Denies: ";
    echo $denies;
    echo "<br>";
    
    echo "Gold: ";
    echo $gold;
    echo "<br>";
    
    echo "Gold Per Min: ";
    echo $gold_per_min;
    echo "<br>";
    
    echo "Total Gold: ";
    echo $total_gold;
    echo "<br>";
    
    echo "Total XP: ";
    echo $total_xp;
    echo "<br>";
    
    echo "XP Per Min: ";
    echo $xp_per_min;
    echo "<br>";
    
    echo "Level: ";
    echo $level;
    echo "<br>";
    
    echo "Hero Damage: ";
    echo $hero_damage;
    echo "<br>";
    
    echo "Hero Healing: ";
    echo $hero_healing;
    echo "<br>";
    
    echo "Tower Damage: ";
    echo $tower_damage;
    echo "<br>";
    
    echo "Courier Kills: ";
    echo $courier_kills;
    echo "<br>";
    
    echo "Ancient Kills: ";
    echo $ancient_kills;
    echo "<br>";
    
    echo "Observer Uses: ";
    echo $observer_uses;
    echo "<br>";
    
    echo "Sentry Uses: ";
    echo $sentry_uses;
    echo "<br>";
    
    echo "Item Slot 0: ";
    echo $item_0;
    echo "<br>";
    
    echo "Item Slot 1: ";
    echo $item_1;
    echo "<br>";
    
    echo "Item Slot 2: ";
    echo $item_2;
    echo "<br>";
    
    echo "Item Slot 3: ";
    echo $item_3;
    echo "<br>";
    
    echo "Item Slot 4: ";
    echo $item_4;
    echo "<br>";
    
    echo "Item Slot 5: ";
    echo $item_5;
    echo "<br>";
    echo "<br>";
      
    $sql = "INSERT INTO PlayerGame(
            match_id,
            account_id,
            personaname,
            isRadiant,
            player_slot,
            hero_id,
            kills,
            deaths,
            assists,
            kda,
            kills_per_min,
            last_hits,
            denies,
            gold,
            gold_per_min,
            total_gold,
            total_xp,
            xp_per_min,
            level,
            hero_damage,
            hero_healing,
            tower_damage,
            courier_kills,
            ancient_kills,
            observer_uses,
            sentry_uses,
            item_0,
            item_1,
            item_2,
            item_3,
            item_4,
            item_5)
        VALUES (".$match_id.", ".
            $account_id.", '".
            $personaname."', ".
            $isRadiant.", ".
            $player_slot.", ".
            $hero_id.", ".
            $kills.", ".
            $deaths.", ".
            $assists.", ".
            $kda.", ".
            $kills_per_min.", ".
            $last_hits.", ".
            $denies.", ".
            $gold.", ".
            $gold_per_min.", ".
            $total_gold.", ".
            $total_xp.", ".
            $xp_per_min.", ".
            $level.", ".
            $hero_damage.", ".
            $hero_healing.", ".
            $tower_damage.", ".
            $courier_kills.", ".
            $ancient_kills.", ".
            $observer_uses.", ".
            $sentry_uses.", ".
            $item_0.", ".
            $item_1.", ".
            $item_2.", ".
            $item_3.", ".
            $item_4.", ".
            $item_5.")";
    
    $result = $conn->query($sql);
    
    echo $sql;
    echo "<br>";
    echo "<br>";
    echo ">>>addNewPlayerGame(): ";
    var_dump($result);
    echo "<br>";    
}

/*function addNewPlayer($conn, $steamID){
    $response = file_get_contents("http://api.opendota.com/api/players/".$steamID."");
    $decoded = json_decode($response);
    
    $profile = $decoded->profile;
    
    $account_id = $profile->account_id;
    $personaname = $profile->personaname;
    $steamid = $profile->steamid;
    $avatarUrl = $profile->avatar;
    
    $sql = "INSERT INTO Player (account_id, personaname, steamid, avatar) VALUES (".$account_id . ", '" . $personaname . "', " . $steamid . ", '" . $avatarUrl . "')";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true;
    }
    else{
        return false;
    }
}*/

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

?>