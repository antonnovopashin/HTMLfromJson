<?php

require_once("Player.php");
require_once("Team.php");


error_reporting(E_ALL);
ini_set('display_errors', 1);
$string = mb_convert_encoding(file_get_contents("source/matches/1024102.json"), 'HTML-ENTITIES', "UTF-8");
$events = json_decode($string, true);

$playersTimeOnTheField = [];

//TODO потом убрать отсюда html куда нибудь в отдельный файл
$fileContent = '<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>';
$fp = fopen("file.html", "w");
$teams = [];
$eventsTable = '<table class="table events">';
$tHead = '<tr>
    <th>Время</th>
    <th>Описание</th>
  </tr>';
$eventsTable = $eventsTable . $tHead;

foreach ($events as $record) {
    switch ($record['type']) {
        case 'info':
            $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] .  '</td>' . '<td>' . $record['description'] .  '</td>' . '</tr>';

            break;
        case 'startPeriod':
            $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] .  '</td>' . '<td>' . $record['description'] .  '</td>' . '</tr>';

            if (!empty($record['details'])){
                $teamOnePlayersJson = $record['details']['team1']['players'];
                $teamOnePlayers = [];

                foreach ($teamOnePlayersJson as $teamOnePlayerJson) {
                    $newPlayer = new Player(); //TODO написать конструктор
                    $newPlayer->setName($teamOnePlayerJson['name']);
                    $newPlayer->setNumber($teamOnePlayerJson['number']);
                    $teamOnePlayers[$teamOnePlayerJson['number']] = $newPlayer;
                }

                $teamOne = new Team();
                $teamOne->setPlayers($teamOnePlayers);
                $teamOne->setTitle($record['details']['team1']['title']);

                $team[$record['details']['team1']['title']] = $teamOne;

                $teamTwoPlayersJson = $record['details']['team2']['players'];

                $teamOnePlayers = [];

                foreach ($teamTwoPlayersJson as $teamTwoPlayerJson) {
                    $newPlayer = new Player(); //TODO написать конструктор
                    $newPlayer->setName($teamTwoPlayerJson['name']);
                    $newPlayer->setNumber($teamTwoPlayerJson['number']);
                    $teamOnePlayers[$teamTwoPlayerJson['number']] = $newPlayer;
                }

                $teamTwo = new Team();
                $teamTwo->setPlayers($teamOnePlayers);
                $teamTwo->setTitle($record['details']['team2']['title']);
                $team[$record['details']['team2']['title']] = $teamTwo;
            }

            break;
        case 'dangerousMoment':
            $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] .  '</td>' . '<td>' . $record['description'] .  '</td>' . '</tr>';

            break;
        case 'yellowCard':
            $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] .  '</td>' . '<td>' . $record['description'] .  '</td>' . '</tr>';

            break;
        case 'goal':
            $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] .  '</td>' . '<td>' . $record['description'] .  '</td>' . '</tr>';

            $players = $team[$record['details']['team']]->getPlayers();
            $goalAuthor = $players[$record['details']['playerNumber']];
            $goalAuthor->setGoals($goalAuthor->getGoals() + 1);
            //TODO ассистента гола тоже нужно фиксировать
            if (!empty($record['details']['assistantNumber'])) {
                $goalAssistant = $players[$record['details']['assistantNumber']];
                $goalAssistant->setGoalPases($goalAssistant->getGoalPases() + 1);
            }

            $team[$record['details']['team']]->setGoals($team[$record['details']['team']]->getGoals() + 1);

            break;
        case 'finishPeriod':
            $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] .  '</td>' . '<td>' . $record['description'] .  '</td>' . '</tr>';

            break;
        case 'replacePlayer':
            $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] .  '</td>' . '<td>' . $record['description'] .  '</td>' . '</tr>';

            break;
    }
}

$eventsTable = $eventsTable . '</table>';
$overallScore = '<table class="table score"><tr><td>' . $teamOne->getTitle() . '</td><td>' . $teamOne->getGoals() . ' : ' . $teamTwo->getGoals() . '</td><td>' . $teamTwo->getTitle() . '</td></tr></table>';
$fileContent = $fileContent . $overallScore;
$fileContent = $fileContent . $eventsTable;

foreach ($team as $teamItem) {
    $playersTable = '<table border="2" class="table players">';
    $tHead = '<tr>
    <th>Имя игрока</th>
    <th>Голы</th>
    <th>Голевые передачи</th>
  </tr>';
    $playersTable = $playersTable . $tHead;
    foreach ($teamItem->getPlayers() as $player) {
        $tableRow = "<tr><td>" . $player->getName() . "</td>
        <td>" . $player->getGoals() . "</td>
        <td>" . $player->getGoalPases() . "</td></tr>";
        $playersTable = $playersTable . $tableRow;
    }
    $playersTable = $playersTable . '</table>';
    $fileContent = $fileContent . $playersTable;
}

fwrite($fp, $fileContent . '</body></html>');
fclose($fp);
chmod("file.html", 0777);

?>
