<?php
require 'app/BlackJack.php';
require 'app/ShoeOfCards.php';
require 'app/Player.php';

$maxPlayers = 2;
$dealer = $maxPlayers + 1;

print "<pre>";

$game = new BlackJack();

for ($x=1; $x<=$maxPlayers; $x++) {
    $players[$x] = new Player($playerName=$x, $seat=$x);
    $game->registerPlayer($players[$x], $seat);
}


$game->newRound();

$game->dealHand($players);

//$game->applyIntialGameRules($players);

//print_r($players);
$game->debug();

exit;

$game->applyRules($players[$dealer], $players[$dealer]);

for ($x=1; $x<=$maxPlayers; $x++) {
    $game->getHandTotal($players[$x]);
    $game->applyRules($players[$x], $players[$dealer]);
}

$game->determineOutcome($players, $players[$dealer]);



if ($game->yahPooBonusWin()) {
    $game->debug();
} else {
    print_r($players);
}

print "</pre>";
