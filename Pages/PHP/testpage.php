<?php
$conn = dbConnect();

//refreshHeroes($conn);
showHeroes($conn);










/*--------------------------FUNCTIONS--------------------------*/
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
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "refreshHeroes()";
    echo "<br>";
    
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
        
        echo "----------------------------";
        echo "<br>";
        echo "Hero ID: ";
        echo $hero_id;
        echo "<br>";
        echo "Hero Name: ";
        echo $hero_name;
        echo "<br>";
        echo "Hero Img: ";
        echo "<img src='".$hero_img."'>";
        echo "<br>";
        echo "Hero Icon: ";
        echo "<img src='".$hero_icon."'>";
        echo "<br>";
        echo "-----";
        echo "<br>";
        
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
        
        echo $sql;
        echo "<br>";
        echo "-----";
        echo "<br>";
        echo var_dump($result);
        echo "<br>";
        echo "----------------------------";
        echo "<br>";
        echo "<br>";
    }
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "<br>";
}

function doesHeroExist($conn, $hero_id) {
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "doesHeroExist(".$hero_id.")";
    echo "<br>";
    
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
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "<br>";
}

function showItems($conn) {
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "showItems()";
    echo "<br>";
    
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

function escapeString($conn, $string) {
    $retval = mysqli_real_escape_string($conn, $string);
    return $retval;
}

function doesItemExist($conn, $item_id) {
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "doesItemExist(".$item_id.")";
    echo "<br>";
    
    $sql = "Select * FROM Item  WHERE item_id = ".$item_id;
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
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "<br>";
}

function refreshItems($conn) {
    echo "--------------------------------------------------------";
    echo "<br>";
    echo "refreshItems()";
    echo "<br>";
    
    $itemResponse = file_get_contents('https://raw.githubusercontent.com/odota/dotaconstants/master/build/items.json');
    $itemResponseDecoded = json_decode($itemResponse);
    
    foreach($itemResponseDecoded as $item){
        $item_id = $item->id;
        $item_name = $item->dname;
        $item_name = escapeString($conn, $item_name);
        $item_url = "https://api.opendota.com".$item->img;
        $item_url = escapeString($conn, $item_url);
        
        /*echo "Item ID: ";
        echo $item_id;
        echo "<br>";
        echo "Item Name: ";
        echo $item_name;
        echo "<br>";
        echo "Item URL: ";
        echo $item_url;
        echo "<br>";
        
        echo "----------------------------";
        echo "<br>";*/
        
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
        
        /*echo $sql;
        echo "<br>";
        echo "-----";
        echo "<br>";
        echo var_dump($result);
        echo "<br>";
        echo "--------------------------------------------------------";
        echo "<br>";
        echo "<br>";*/
    }
}
function addNewGame($conn, $match_id, $game_mode, $lobby_type, $start_time, $duration, $radiant_win, $dire_score, $radiant_score){
    $sql = "INSERT INTO Game (match_id, game_mode, lobby_type, start_time, duration, radiant_win, dire_score, radiant_score) VALUES (".$match_id.", ".$game_mode.", ".$lobby_type.", ".$start_time.", ".$duration.", ".$radiant_win.", ".$dire_score.", ".$radiant_score.")";
    $result = $conn->query($sql);
}

function addNewPlayerGame($conn, $match_id, $account_id, $personaname, $isRadiant, $player_slot, $hero_id, $kills, $deaths, $assists, $kda, $kills_per_min, $last_hits, $denies, $gold, $gold_per_min, $total_gold, $total_xp, $xp_per_min, $level, $hero_damage, $hero_healing, $tower_damage, $courier_kills, $ancient_kills, $observer_uses, $sentry_uses, $item_0, $item_1, $item_2, $item_3, $item_4, $item_5) {
    
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

function quoteCheck($string) {
        if(strpos($string, '\'') !== false) {
            $input = $string; 
            $char = '\\'; 
            $position = strpos($input, '\''); 
            
            $retval = substr_replace( $input, $char, $position, 0 );
            
            return $retval;
        }
        if(strpos($string, '\"') !== false) {
            $input = $string; 
            $char = '\\'; 
            $position = strpos($input, '\"'); 
            
            $retval = substr_replace( $input, $char, $position, 0 );
            
            return $retval;
        }
        else
            return $string;
}

function addNewPlayer($conn, $account_id){
        //$steamid = $steamid & 0xffffffff;

        //$response = file_get_contents('https://api.opendota.com/api/players/'.$steamid.'');
        $response = file_get_contents('https://api.opendota.com/api/players/'.$account_id.'');
        $decoded = json_decode($response);
        
        $profile = $decoded->profile;
        
        //$account_id = $profile->account_id;
        
        $personaname = $profile->personaname;
        $personaname = quoteCheck($personaname);
        
        $steamid = $profile->steamid;
        
        $avatarUrl = $profile->avatar;
        $avatarUrl = quoteCheck($avatarUrl);
        
        $sql = "INSERT INTO Player (account_id, personaname, steamid, avatar) VALUES (".$account_id . ", '" . $personaname . "', " . $steamid . ", '" . $avatarUrl . "')";
        $result = $conn->query($sql);
        
        
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