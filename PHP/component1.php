<?php
print "<head>
    <title>Recent Matches</title>
</head>

<body>
    <h1>Recent Matches</h1>
    <table id='matches' style='width:100%'>
        <tr>
            <th>Match ID</th>
            <th>Hero Played</th> 
            <th>Kills/Deaths/Assists</th>
        </tr>";
        
$output = exec('../Static/Python component1.py');
echo $output;

//$response = json_encode($output);

//echo '<script>console.log(' + $output + ')</script>';
/*
foreach ($response as $match) {
    print "
        <tr>
            <td> [Match ID] </td>
            <td> [Hero Name] </td>
            <td> [Kills] / [Deaths] / [Assists] </td>
        </tr>";
};
*/

/*
Client::setDefaultKey('848471C48E75211005D2D7A958924178');

$client = new Client();

// Returns a Response object that contains the raw body and JSON data.
$response = $client->getMatchHistoryBySequenceNumber(new Filters\MatchSequence(76561198086741532, 10));

// Turns response into a Match collection
$matchCollection = $response->getCollection('Match');

// Loops through all the found matches and dispays the start time.
foreach ($matchCollection as $match) {
    echo $match->getStartTime()->format('d-m-Y H:i:s') . PHP_EOL;
}
*/

/*
$filter = new Filters\Match();
$filter->setGameMode(GameModes::RANKED_ALL_PICK);
$filter->setMinimumPlayers(10);
$filter->setAccountId(76561198086741532);

$response = $client->getMatchHistory($filter);

$matchCollection = $response->getCollection('Match');

foreach ($matchCollection as $match) {
    //echo $match->getStartTime()->format('d-m-Y H:i:s') . PHP_EOL;
    print "<tr><td> Match ID </td> <td> ero ID </td> <td> Kills / mDeaths / Assists </td></tr>";
}
*/

print "
    </table>
</body>";
?>