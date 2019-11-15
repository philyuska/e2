@extends('templates.master')

@section('content')

<div class='container'>
    <h1>Casino War</h1>
    <h2>Instructions</h2>
    <ul class='cpeg-ul'>
        <li>One card is dealt to each player and then to the dealer.</li>
        <li>If the players card is higher than the dealers card, that player wins.</li>
        <li>If the players card is lower than the dealers card, that player loses.</li>
        <li>Aces are always high.</li>
        <li>In the event of a tie a new card will be dealt to the player and dealer until ther is a winner.</li>
        <li>Press the Take A Seat button to play.</li>
    </ul>
</div>

<div class='container'>

    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <!-- <div class='dealer-container'> -->
            <?php foreach ($game->dealer->hand as $card) { ?>
            <div class='card'>
                <span
                    class='glyph <?php echo $card['suit']; ?>'><?php echo $card['glyph']; ?></span>
                <div class='card-description'>
                    <p><?php echo $card['name']; ?>
                    </p>
                </div>
            </div>
            <?php } ?>

        </div>
        <div class=' col-lg-3'>
        </div>
    </div>

    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='player-container'>
                <p><strong><?=$game->dealer->getName()?></strong>
                    shows the <?=$game->dealer->getLastCard('name')?>
                </p>
            </div>
        </div>
        <div class='col-lg-3'>
        </div>
    </div>

    <div class='row row-no-gutters'>
        <?php foreach ($game->players as $seat => $player) { ?>
        <?php if ($player->goneToWar()) {?>
        <div class='col-lg-4'>

            <div class='row'>
                <div class='player-container'>
                    <p><strong><?=$player->getName()?> has
                            <?=$player->getLastCard()?>
                            WAR
                            <br />
                        </strong>
                    </p>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <strong><?=$game->dealer->getName()?></strong>
                </div>
                <div class='col-md-6'>
                    <strong><?=$player->getName()?></strong>
                </div>
            </div>
            <div class='row'>
                <?php foreach (array_keys($player->warHand) as $x) { ?>
                <div class='col-md-6'>
                    <?=$game->dealer->warHand[$x]['emoji'];?>
                </div>
                <div class='col-md-6'>
                    <?=$player->warHand[$x]['emoji'];?>
                </div>
                <?php } ?>
                <div class='col-md-6'>
                </div>
                <div class='col-md-6'>
                    <strong><?=$player->getOutcome()?></strong>
                </div>
            </div>
        </div>
        <?php } else { ?>

        <div class='col-lg-4'>
            <div class='player-container'>
                <p><strong><?=$player->getName()?></strong> has a
                    <strong><?=$player->getLastCard()?></strong>
                    <?=$player->getOutcome()?>
                </p>
            </div>
        </div>
        <?php } } ?>
    </div>
    <?php dump($game->dealer); ?>
    <?php dump($game->players); ?>
</div>

@endsection