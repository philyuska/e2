<?php
require 'app/BlackJack.php';
require 'app/ShoeOfCards.php';
require 'app/BlackJackPlayer.php';
require 'app/BlackJackDealer.php';
require 'app/Patron.php';


$maxPlayers = 2;

print "<pre>";

$game = new BlackJack();

$patron = new Patron();

for ($x=1; $x<=$maxPlayers; $x++) {
    $patron = new Patron("mutt" . $x);
    $players[$x] = new BlackJackPlayer($patron);
    $game->registerPlayer($players[$x], $seat=$x);
}

$game->newRound();

$game->dealHand();

foreach ($game->players as $player) {
    $player->drawCard($game->deck->dealCard());

    $player->loser();
}

//$game->dealer->drawCard($game->deck->dealCard());

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
