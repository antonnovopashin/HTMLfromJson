<?php

require_once("Player.php");
require_once("Team.php");

$files = scandir('source/matches');
array_shift($files);
array_shift($files);

foreach ($files as $file) {
    $filename = pathinfo($file, PATHINFO_FILENAME);
    $string = mb_convert_encoding(file_get_contents("source/matches/" . $filename . ".json"), 'HTML-ENTITIES', "UTF-8");
    $events = json_decode($string, true);

    $playersTimeOnTheField = [];

//TODO потом убрать отсюда html куда нибудь в отдельный файл (в идеал прикрутить шаблонизатор)
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
    $filePointer = fopen("result/" . $filename . ".html", "w");
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
                $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] . '</td>' . '<td>' . $record['description'] . '</td>' . '</tr>';

                break;
            case 'startPeriod':
                $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] . '</td>' . '<td>' . $record['description'] . '</td>' . '</tr>';

                if (!empty($record['details'])) {
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

                    $teamOneStartPlayersNumbers = $record['details']['team1']['startPlayerNumbers'];

                    foreach ($teamOneStartPlayersNumbers as $teamOneStartPlayersNumber) {
                        $teamOne->getPlayers()[$teamOneStartPlayersNumber]->enterField();
                        $teamOne->getPlayers()[$teamOneStartPlayersNumber]->setLastTimeUsed($record['time']);
                    }

                    $teamTwoStartPlayersNumbers = $record['details']['team2']['startPlayerNumbers'];

                    foreach ($teamTwoStartPlayersNumbers as $teamTwoStartPlayersNumber) {
                        $teamTwo->getPlayers()[$teamTwoStartPlayersNumber]->enterField();
                        $teamTwo->getPlayers()[$teamTwoStartPlayersNumber]->setLastTimeUsed($record['time']);
                    }
                }

                $teamOnePlayers = $teamOne->getPlayers();

                foreach ($teamOnePlayers as $player) {
                    if ($player->isActivity()) {
                        $player->setLastTimeUsed($record['time']);
                    }
                }

                $teamTwoPlayers = $teamTwo->getPlayers();

                foreach ($teamTwoPlayers as $player) {
                    if ($player->isActivity()) {
                        $player->setLastTimeUsed($record['time']);
                    }
                }

                break;
            case 'dangerousMoment':
                $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] . '</td>' . '<td>' . $record['description'] . '</td>' . '</tr>';
                $team[$record['details']['team']]->setDangerousMoments($team[$record['details']['team']]->getDangerousMoments() + 1);

                break;
            case 'yellowCard':
                $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] . '</td>' . '<td>' . $record['description'] . '</td>' . '</tr>';
                $players = $team[$record['details']['team']]->getPlayers();
                $cardReciever = $players[$record['details']['playerNumber']];
                $cardReciever->setYelowCards($cardReciever->getYelowCards() + 1);

                break;
            case 'goal':
                $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] . '</td>' . '<td>' . $record['description'] . '</td>' . '</tr>';

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
                $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] . '</td>' . '<td>' . $record['description'] . '</td>' . '</tr>';

                //тут нужно подсчитать и зафиксировать время проведенное игроками на поле у которых активити = тру
                $teamOnePlayers = $teamOne->getPlayers();

                foreach ($teamOnePlayers as $player) {
                    if ($player->isActivity()) {
                        $addedTime =  $record['time'] - (int) $player->getLastTimeUsed();
                        $player->setTimeOnTheField($player->getTimeOnTheField() + $addedTime);
                    }
                }

                $teamTwoPlayers = $teamTwo->getPlayers();

                foreach ($teamTwoPlayers as $player) {
                    if ($player->isActivity()) {
                        $addedTime =  $record['time'] - (int) $player->getLastTimeUsed();
                        $player->setTimeOnTheField($player->getTimeOnTheField() + $addedTime);
                    }
                }

                break;
            case 'replacePlayer':
                $eventsTable = $eventsTable . '<tr>' . '<td>' . $record['time'] . '</td>' . '<td>' . $record['description'] . '</td>' . '</tr>';

                $timePlayed = $record['time'] - $team[$record['details']['team']]->getPlayers()[$record['details']['outPlayerNumber']]->getLastTimeUsed();
                //полученое значение прибавляю к его общему времени на поле
                $team[$record['details']['team']]->getPlayers()[$record['details']['outPlayerNumber']]->setTimeOnTheField($team[$record['details']['team']]->getPlayers()[$record['details']['outPlayerNumber']]->getTimeOnTheField() + $timePlayed);
                //ставлю активити на фолс
                $team[$record['details']['team']]->getPlayers()[$record['details']['outPlayerNumber']]->leaveField();

                //у выходящего игрока ставлю время выхода на поле текущее время
                $team[$record['details']['team']]->getPlayers()[$record['details']['inPlayerNumber']]->setLastTimeUsed($record['time']);
                //ставлю активити на тру
                $team[$record['details']['team']]->getPlayers()[$record['details']['inPlayerNumber']]->enterField();
                break;
        }
    }

    $eventsTable = $eventsTable . '</table>';
    $overallScore = '<table class="table score"><tr><td>' . $teamOne->getTitle() . '</td><td>' . $teamOne->getGoals() . ' : ' . $teamTwo->getGoals() . '</td><td>' . $teamTwo->getTitle() . '</td></tr></table>';
    $fileContent = $fileContent . $overallScore;
    $fileContent = $fileContent . $eventsTable;

    foreach ($team as $teamItem) { //TODO cделать что то с этими кусками html
        $playersTable = '<table border="2" class="table players">';
        $tHead = '<tr>
    <th>Имя игрока</th>
    <th>Голы</th>
    <th>Время на поле</th>
    <th>Голевые передачи</th>
  </tr>';
        $playersTable = $playersTable . $tHead;
        foreach ($teamItem->getPlayers() as $player) {
            $tableRow = "<tr><td>" . $player->getName() . "</td>
        <td>" . $player->getGoals() . "</td>
        <td>" . $player->getTimeOnTheField() . "</td>
        <td>" . $player->getGoalPases() . "</td></tr>";
            $playersTable = $playersTable . $tableRow;
        }
        $playersTable = $playersTable . '</table>';
        $fileContent = $fileContent . $playersTable;
    }

    fwrite($filePointer, $fileContent . '</body></html>');
    fclose($filePointer);
    echo '<a href ="result/' . $filename . '.html">' . $filename . '</a><br>';
    chmod("result/" . $filename . ".html", 0777);
}
?>
