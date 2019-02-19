<?php
$response = json_decode(shell_exec("python ./pythonTest.py 2>&1"));
$matches = $response->matches;

print "<head> \n".
    "<title>Recent Matches</title> \n".
"</head> \n".

"<body> \n".
    "<h1>Recent Matches</h1> \n".
    "<table id='matches' style='width:100%'> \n".
        "<tr> \n".
            "<th>Match ID</th> \n".
            "<th>Hero Played</th> \n".
            "<th>Kills/Deaths/Assists</th> \n".
        "</tr> \n";

foreach ($matches as $match) {
    $matchID = json_encode($match->match_id);
    
    print "<tr align='center'> \n".
            "<td>".$matchID."</td> \n".
            "<td> [Hero Name] </td> \n".
            "<td> [Kills] / [Deaths] / [Assists] </td> \n".
        "</tr> \n";
};

print "</table> \n".
"</body> \n";

?>