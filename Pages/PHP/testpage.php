<?php
session_start();
//addGameData();

$conn = dbConnect();
$steamid = $_SESSION["steamID"];
$account_id = getPlayerAccountID($conn, $steamid);
$hero_id = 5;

getNumHeroGames($conn, $hero_id, $account_id);

/*--------------------------FUNCTIONS--------------------------*/
function printPlayer($conn, $steamid) {
    $sql = "SELECT * FROM Player WHERE steamid = ".$steamid."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "-------PLAYER---------<br>";
            print "".$row['account_id']."<br>";
            print "".$row['personaname']."<br>";
            print "".$row['steamid']."<br>";
            print "".$row['avatar']."<br>";
            print "<img src='".$row['avatar']."'><br>";
        }
    }
    else{
        print "Log-In to see your profile stats.";
    }
    
    var_dump($result);
    echo "<br>";
}

function getNumHeroGames($conn, $hero_id, $account_id) {
    $sql = "SELECT COUNT(*) FROM PlayerGame WHERE PlayerGame.hero_id = ".$hero_id." AND PlayerGame.account_id = ".$account_id."";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print $row['COUNT(*)'];
        }
    }
}

function printPlayers($conn) {
    $sql = "SELECT * FROM Player";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print "-------PLAYER---------<br>";
            print "".$row['account_id']."<br>";
            print "".$row['personaname']."<br>";
            print "".$row['steamid']."<br>";
            print "".$row['avatar']."<br>";
            print "<img src='".$row['avatar']."'><br>";
        }
    }
    else{
        print "Log-In to see your profile stats.";
    }
    
    var_dump($result);
    echo "<br>";
}

function showHeroes($conn) {
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "showHeroes()";
    echo "<br>";
    
    $sql = "Select * FROM Hero  ORDER BY hero_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Hero ID: ";
            echo $row['hero_id'];
            echo "<br>";
            echo "Hero Name: ";
            echo $row['hero_name'];
            echo "<br>";
            echo "Hero Img: ";
            echo "<img src='".$row['hero_img']."'>";
            echo "<br>";
            echo "Hero Icon: ";
            echo "<img src='".$row['hero_icon']."'>";
            echo "<br>";
            
            echo "----------------------------";
            echo "<br>";
        }
    } else {
            echo "NO RESULT";
            echo "<br>";
    }
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "<br>";
}

function refreshHeroes($conn) {
    $heroResponse = file_get_contents('https://raw.githubusercontent.com/odota/dotaconstants/master/build/heroes.json');
    $heroResponseDecoded = json_decode($heroResponse);
    
    foreach($heroResponseDecoded as $hero){
        $hero_id = $hero->id;
        $hero_name = $hero->localized_name;
        $hero_name = escapeString($conn, $hero_name);
        $hero_img = "https://api.opendota.com".$hero->img;
        $hero_img = escapeString($conn, $hero_img);
        $hero_icon = "https://api.opendota.com".$hero->icon;
        $hero_icon = escapeString($conn, $hero_icon);
        
        if (doesHeroExist($conn, $hero_id)) {
            $sql = "UPDATE Hero ".
                "SET ".
                    "hero_name = ".$hero_name.
                    ", hero_img = ".$hero_img.
                    ", hero_icon = ".$hero_icon.
                "WHERE ".
                    "hero_id = ".$hero_id;
        
        } else {
            $sql = "INSERT INTO Hero(
                hero_id,
                hero_name,
                hero_img,
                hero_icon)
            VALUES (".$hero_id."
                , '".$hero_name."'
                , '".$hero_img."'
                , '".$hero_icon."')";
        
        }
                    
        $result = $conn->query($sql);
    }
}

function doesHeroExist($conn, $hero_id) {
    $sql = "Select * FROM Hero  WHERE hero_id = ".$hero_id;
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "TRUE";
        echo "<br>";
        return true;
    }
    else{
        echo "FALSE";
        echo "<br>";
        return false;
    }
}

function showItems($conn) {
    echo "-------------------------------------------------------- <br>";
    echo "showItems() <br>";
    
    $sql = "Select * FROM Item  ORDER BY item_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Item ID: ";
            echo $row['item_id'];
            echo "<br>";
            echo "Item Name: ";
            echo $row['item_name'];
            echo "<br>";
            echo "Item URL: ";
            echo "<img src='".$row['item_url']."'>";
            echo "<br>";
            
            echo "---------------------------- <br>";
        }
    } else {
            echo "NO RESULT <br>";
    }
    echo "-------------------------------------------------------- <br> <br>";
}

function escapeString($conn, $string) {
    $retval = mysqli_real_escape_string($conn, $string);
    return $retval;
}

function doesItemExist($conn, $item_id) {
    $sql = "Select * FROM Item  WHERE item_id = ".$item_id;
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true;
    }
    else{
        return false;
    }
}

function refreshItems($conn) {
    $itemResponse = file_get_contents('https://raw.githubusercontent.com/odota/dotaconstants/master/build/items.json');
    $itemResponseDecoded = json_decode($itemResponse);
    
    foreach($itemResponseDecoded as $item){
        $item_id = $item->id;
        $item_name = $item->dname;
        $item_name = escapeString($conn, $item_name);
        $item_url = "https://api.opendota.com".$item->img;
        $item_url = escapeString($conn, $item_url);
        
        if (doesItemExist($conn, $item_id)) {
            $sql = "UPDATE Item ".
                "SET ".
                    "item_name = ".$item_name.
                    "item_url = ".$item_url.
                "WHERE ".
                    "item_id = ".$item_id;
        
        } else {
            $sql = "INSERT INTO Item(
                item_id,
                item_name,
                item_url)
            VALUES (".$item_id."
                , '".$item_name."'
                , '".$item_url."')";
        
        }
                    
        $result = $conn->query($sql);
    }
}

function addGameData() {
    if (isset($_SESSION['steamID'])) {
        $conn = dbConnect();
    
        $steamid = $_SESSION['steamID'];
        $account_id = getPlayerAccountID($conn, $steamid);

        $playerMatchesResponse = file_get_contents('http://api.opendota.com/api/players/'.$account_id.'/matches?limit=1&api_key=65a96d82-0ad7-462f-87bf-07b10e6007be');
        $playerMatchesResponseDecoded = json_decode($playerMatchesResponse);
        
        foreach($playerMatchesResponseDecoded as $match){
            $match_id = $match->match_id;
            if (!doesGameExist($conn, $match_id)) {
                $game_mode = $match->game_mode;
                $lobby_type = $match->lobby_type;
                $start_time = $match->start_time;
                $duration = $match->duration;
                
                $radiant_win = $match->radiant_win;
                if($radiant_win)
                    $radiant_win = 1;
                else 
                    $radiant_win = 0;
                
                $matchDetailsResponse = file_get_contents('http://api.opendota.com/api/matches/'.$match_id.'?api_key=65a96d82-0ad7-462f-87bf-07b10e6007be');
                $matchDetailsResponseDecoded = json_decode($matchDetailsResponse);
            
                $players = $matchDetailsResponseDecoded->players;
            
                $radiant_score = $matchDetailsResponseDecoded->radiant_score;
                $dire_score = $matchDetailsResponseDecoded->dire_score;
            
                addNewGame($conn, $match_id, $game_mode, $lobby_type, $start_time, $duration, $radiant_win, $dire_score, $radiant_score, $players);
                
                $time = $start_time;
                addGamesAfterTime($conn, $account_id, $time);
            } else {
                $steamid = $_SESSION['steamID'];
                $account_id = getPlayerAccountID($conn, $steamid);
                $time = getOldestGameTime($conn, $account_id);
                addGamesAfterTime($conn, $account_id, $time);
            }
        }
    } else {
        $message = "You must log in to add Game data.";
        echo "<script type='text/javascript'>alert('$message');</script>";
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

function addGamesAfterTime($conn, $account_id, $time) {
    $playerMatchesResponse = file_get_contents('http://api.opendota.com/api/players/'.$account_id.'/matches?date='.$time.'&api_key=65a96d82-0ad7-462f-87bf-07b10e6007be');
    $playerMatchesResponseDecoded = json_decode($playerMatchesResponse);
        
    foreach($playerMatchesResponseDecoded as $match){
        $match_id = $match->match_id;
        if (!doesGameExist($conn, $match_id)) {
            $game_mode = $match->game_mode;
            $lobby_type = $match->lobby_type;
            $start_time = $match->start_time;
            $duration = $match->duration;
            
            $radiant_win = $match->radiant_win;
            if($radiant_win)
                $radiant_win = 1;
            else 
                $radiant_win = 0;
            
            $matchDetailsResponse = file_get_contents('http://api.opendota.com/api/matches/'.$match_id.'?api_key=65a96d82-0ad7-462f-87bf-07b10e6007be');
            $matchDetailsResponseDecoded = json_decode($matchDetailsResponse);
        
            $players = $matchDetailsResponseDecoded->players;
        
            $radiant_score = $matchDetailsResponseDecoded->radiant_score;
            $dire_score = $matchDetailsResponseDecoded->dire_score;
        
            addNewGame($conn, $match_id, $game_mode, $lobby_type, $start_time, $duration, $radiant_win, $dire_score, $radiant_score, $players);
        } else {
            
        }
    }
}

function getOldestGameTime($conn, $account_id) {
    $sql = "SELECT MIN(Game.start_time) AS timestamp FROM Game, PlayerGame WHERE PlayerGame.account_id = ".$account_id." AND Game.match_id = PlayerGame.match_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "getOldestGameTime() <br>";
            echo "TIMESTAMP: ";
            echo $row['timestamp'];
            echo "<br>";
            
            return $row['timestamp'];
        }
    } else {
            echo "NO RESULT <br>";
    }
}

function addNewGame($conn, $match_id, $game_mode, $lobby_type, $start_time, $duration, $radiant_win, $dire_score, $radiant_score, $players){
    $sql = "INSERT INTO Game (match_id, game_mode, lobby_type, start_time, duration, radiant_win, dire_score, radiant_score) VALUES (".$match_id.", ".$game_mode.", ".$lobby_type.", ".$start_time.", ".$duration.", ".$radiant_win.", ".$dire_score.", ".$radiant_score.")";
    $result = $conn->query($sql);
    
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
                $kills_per_min = round($kills_per_min, 2);
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
                
                echo "-------------<br>";
                echo "Before AccID Check: <br> ";
                echo $account_id;
                echo "<br>";
                echo $personaname;
                echo "<br>";
                echo "-------------<br>";
                
                if(!$account_id) {
                    echo "Temp Player: ";
                    $account_id = $match_id + $player_slot;
                    while (doesPlayerExist($conn, $account_id)) {
                        $account_id += 1;
                    }
                    
                    $personaname = "Anonymous";
                    echo $account_id;
                    echo "<br>";
                    
                    addTempPlayer($conn, $account_id);
                } else {
                    if (!doesPlayerExistAcc($conn, $account_id)) {
                        addNewPlayer($conn, $account_id);
                    } else {
                        updatePlayer($conn, $account_id);
                    }
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

function doesGameExist($conn, $match_id){
    $sql = "SELECT * FROM Game WHERE match_id = ".$match_id."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true;
    }
    else{
        return false;
    }
}

function addNewPlayerGame($conn, $match_id, $account_id, $personaname, $isRadiant, $player_slot, $hero_id, $kills, $deaths, $assists, $kda, $kills_per_min, $last_hits, $denies, $gold, $gold_per_min, $total_gold, $total_xp, $xp_per_min, $level, $hero_damage, $hero_healing, $tower_damage, $courier_kills, $ancient_kills, $observer_uses, $sentry_uses, $item_0, $item_1, $item_2, $item_3, $item_4, $item_5) {
    echo "-------------------------------------<br>";
    
    $personaname = escapeString($conn, $personaname);
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
        VALUES (".$match_id."
                , ".$account_id."
                , '".$personaname."'
                , ".$isRadiant."
                , ".$player_slot."
                , ".$hero_id."
                , ".$kills."
                , ".$deaths."
                , ".$assists."
                , ".$kda."
                , ".$kills_per_min."
                , ".$last_hits."
                , ".$denies."
                , ".$gold."
                , ".$gold_per_min."
                , ".$total_gold."
                , ".$total_xp."
                , ".$xp_per_min."
                , ".$level."
                , ".$hero_damage."
                , ".$hero_healing."
                , ".$tower_damage."
                , ".$courier_kills."
                , ".$ancient_kills."
                , ".$observer_uses."
                , ".$sentry_uses."
                , ".$item_0."
                , ".$item_1."
                , ".$item_2."
                , ".$item_3."
                , ".$item_4."
                , ".$item_5."
                )";
                
    $result = $conn->query($sql);
    
    echo $sql;
    echo "<br>";
    
    echo ">>>addNewPlayerGame(): ";
    var_dump($result);
    echo "<br>";
    
    echo "-------------------------------------<br>";
    echo "<br>";
}

function doesPlayerExist($conn, $account_id) {
    $sql = "Select * FROM Player WHERE account_id = ".$account_id."";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return true;
    }
    else{
        return false;
    }
}

function addNewPlayer($conn, $account_id){
    echo "addNewPlayer() was called. <br> ";
    echo "Account ID: ";
    echo $account_id;
    echo "<br>";
    
    $response = file_get_contents('http://api.opendota.com/api/players/'.$account_id.'?api_key=65a96d82-0ad7-462f-87bf-07b10e6007be');
    $decoded = json_decode($response);
    
    $profile = $decoded->profile;
    
    $personaname = $profile->personaname;
    $personaname = escapeString($conn, $personaname);
    echo "PersonaName: ";
    echo $personaname;
    echo "<br>";
    
    $steamid = $profile->steamid;
    echo "Steam ID: ";
    echo $personaname;
    echo "<br>";
    
    $avatarUrl = $profile->avatar;
    $avatarUrl = escapeString($conn, $avatarUrl);
    echo "Avatar: ";
    echo "<img src='".$avatarUrl."'>";
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

function updatePlayer($conn, $account_id){
    echo "updatePlayer() was called. <br> ";
    echo "Account ID: ";
    echo $account_id;
    echo "<br>";
    
    $response = file_get_contents('http://api.opendota.com/api/players/'.$account_id.'?api_key=65a96d82-0ad7-462f-87bf-07b10e6007be');
    $decoded = json_decode($response);
    
    $profile = $decoded->profile;
    
    $personaname = $profile->personaname;
    $personaname = escapeString($conn, $personaname);
    echo "PersonaName: ";
    echo $personaname;
    echo "<br>";
    
    $steamid = $profile->steamid;
    echo "Steam ID: ";
    echo $personaname;
    echo "<br>";
    
    $avatarUrl = $profile->avatar;
    $avatarUrl = escapeString($conn, $avatarUrl);
    echo "Avatar: ";
    echo "<img src='".$avatarUrl."'>";
    echo "<br>";
    
    $sql = "UPDATE Player SET personaname = '".$personaname."', steamid = ".$steamid.", avatar = '".$avatarURL."' WHERE account_id = ".$account_id."";
    $result = $conn->query($sql);
    
    echo $sql;
    echo "<br>";
    
    echo ">>>updatePlayer(): ";
    var_dump($result);
    echo "<br>";
    echo "<br>";
}

function addTempPlayer($conn, $account_id){
    $sql = "INSERT INTO Player (account_id, personaname, steamid, avatar) VALUES (".$account_id.", 'Anonymous', 1, 'https://static.giantbomb.com/uploads/square_small/0/1081/2434901-icefrog.jpg')";
    
    echo ">>>addTempPlayer(): ";
    
    $result = $conn->query($sql);
    var_dump($result);
    echo "<br>";
    echo "<br>";
}

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