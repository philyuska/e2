<?php
require 'app/BlackJack.php';
require 'app/ShoeOfCards.php';
require 'app/BlackJackPlayer.php';
require 'app/BlackJackDealer.php';
require 'app/Patron.php';


$names = getRandomNames();
shuffle($names);
print "<pre>";

$game = new BlackJack($seats=5);

$patron = new Patron("Valued Guest");
$player = new BlackJackPlayer($patron);
$game->registerPlayer($player);

for ($x=1; $x<= $game->seats -1; $x++) {
    $playerName = array_shift($names);
    $players[$x] = new BlackJackPlayer($patron=null, $playerName = $playerName);
    $game->registerPlayer($players[$x]);
}

foreach ($game->players as $player) {
    if ($player->isPatron()) {
        print $player->getName() . " starting with tokens " . $player->getTokens() . "\n";
    }
}

while ($game->getCurrentRound() < 3) {
    $game->newRound();

    foreach ($game->players as $player) {
        if ($player->isPatron()) {
            $player->collectAnte();
            print " round " . $game->getCurrentRound() . " Ante : " . $player->getName() . " tokens " . $player->getTokens() . "\n";
        }
    }

    $game->dealHand();
    $game->peekHand();

    if ($game->continueRound()) {
        foreach ($game->players as $player) {
            if ($player->hasButton()) {
                while (($player->handTotal() < 21) && ($game->shouldHit($player))) {
                    $player->drawCard($game->deck->dealCard());
                }
                $player->setHandHistory($player->handSummary() . " total: " . $player->handTotal());
                $game->passButton($player->seat);
            }
        }

        $game->dealer->showHand();

        while ($game->dealer->handTotal() < 17) {
            $game->dealer->drawCard($game->deck->dealCard());
        }
        $game->determineOutcome();
    } elseif ($game->getBlackJack()) {
        $game->dealer->showHand();
    }

    $game->payout();

    if ($game->getBonusWin()) {
        print " round " . $game->getCurrentRound() . " : Bonus win " . $game->dealer->hand[2]['glyph'] . " awarded at " . $game->dealer->hole[2]['value'] . "X ante\n";
    } else {
        print " round " . $game->getCurrentRound() . " : " . $game->dealer->getName() . " " . $game->dealer->handSummary() . " total: " . $game->dealer->handTotal() . "\n";
    }

    foreach ($game->players as $player) {
        print " round " . $game->getCurrentRound() . " : " . $player->getName() . " " . $player->handSummary() . " total: " . $player->handTotal() . " " . $player->outcome . "\n";
    }

    foreach ($game->players as $player) {
        if ($player->isPatron()) {
            print $player->getName() . " finished with tokens " . $player->getTokens() . "\n";
        }
    }

    print "\n";
}



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

function getRandomNames()
{
    $randomNames = array(

        'King',
        'Baldie',
        'Kingpin',
        'Blueman',
        'Knuckles',
        'Bones',
        'Lord',
        'Books',
        'Lucky',
        'The Boss',
        'Machine Gun',
        'Bugsy',
        'Mad Hatter',
        'Butcher',
        'Parole',
        'Cain',
        'The Prophet',
        'Coon',
        'Rattler',
        'Cottonmouth',
        'Recluse',
        'Deathrow',
        'Rifle',
        'Digger',
        'Rocks',
        'Dimebag',
        'The Sandman',
        'The Don',
        'Scar',
        'Dreads',
        'Sharkie',
        'The Enforcer',
        'Skinhead',
        'Fastfingers',
        'Smuggie',
        'Fat Tony',
        'Snake Eyes',
        'The Godfather',
        'Snap',
        'Guttermouth',
        'Stab Happy',
        'Headlock',
        'T-Bone',
        'Ice',
        'Vito',
        'Ice Box',
        'Wheels',
        'Angel Face',
        'Magnolia',
        'Baby',
        'Mama',
        'Baby Blue',
        'Margarita',
        'Bambi',
        'Miss Demeanor',
        'Bandit',
        'Missy',
        'Banker',
        'The Monalisa',
        'Bonnie',
        'Nails',
        'Brooklyn',
        'Pearl',
        'Bug',
        'Pinstripes',
        'Cadillac',
        'Pistol',
        'The Cardinal',
        'Queen Bee',
        'Cheeks',
        'Queenie',
        'The Cougar',
        'Red',
        'Diamond',
        'Red Hot',
        'Dollface',
        'Rosie',
        'Duchess',
        'Ruby',
        'Felony',
        'Stiletto',
        'The Flamingo',
        'Tammy Gun',
        'Frenchie',
        'Trigger',
        'Gams',
        'The Vicereine',
        'The Harlem Hatchett',
        'Vixon',
        'Jailbird',
        'Wicked Witch',
        'Jersey',
        'Widow',
        'Kitty',
        'Wifey',
    );

    shuffle($randomNames);
    return $randomNames;
}
