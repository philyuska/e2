<?php
require 'app/BlackJack.php';
require 'app/ShoeOfCards.php';
require 'app/Player.php';

$maxPlayers = 1;
$dealer = $maxPlayers + 1;

print "<pre>";

$game = new BlackJack();
$deck = new ShoeOfCards(1);


for ($x=1; $x<=$maxPlayers; $x++) {
    $players[$x] = new Player($x, $x);
}
$players[$dealer] = new Player($dealer, 0);

$game->dealHand($players, $players[$dealer]);

$game->applyRules($players[$dealer], $players[$dealer]);

for ($x=1; $x<=$maxPlayers; $x++) {
    $game->getHandTotal($players[$x]);
    $game->applyRules($players[$x], $players[$dealer]);
}

$game->determineOutcome($players, $players[$dealer]);


print_r($players);
$game->debug();

print "</pre>";
