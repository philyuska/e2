@extends('templates.master')

@section('content')

<div class='container'>
    <h1>Blackjack</h1>
    <h2>Instructions</h2>
    <ul class='cpeg-ul'>
        <li>Attempt to beat the dealer by getting a hand total closest to 21 without going over.</li>
        <li>Aces are worth 1 or 11, hand splits are not allowed.</li>
        <li>Win instantly if the dealers hole card matches &#x1f4a9;.</li>
        <li>The dealer will stand with 17, and if thier hand total is greater than 21 "bust", you win.</li>
        <li>Advice is offered on whether to Hit or Stay but the choice is yours.If your hand total is greater than 21
            "bust", you lose.</li>
        <li>Press the Take A Seat button to play.</li>
    </ul>
</div>

<div class='container'>

    <?php if ($game->getBonusWin()) { ?>

    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='dealer-container'>
                <?php foreach ($game->dealer->hand as $card) { ?>
                <div class='card'>
                    <span
                        class='glyph <?php echo $card['suit']; ?>'><?php echo $card['glyph']; ?></span>
                    <div class='card-description'>
                        <p><?php echo $card['name']; ?>
                        </p>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <div class='col-lg-3'>
        </div>
    </div>
    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='player-container'>
                <p> <strong>Bonus Win!!</strong></p>
            </div>
        </div>
        <div class='col-lg-3'>
        </div>
    </div>

    <?php } else {
    ?>
    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='dealer-container'>
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
        </div>
        <div class=' col-lg-3'>
        </div>
    </div>
    <?php
} ?>

    <?php
    if (! $game->getBonusWin()) {?>
    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='player-container'>
                <p><strong><?=$game->dealer->getName()?></strong>

                    <?php
                     if (! $game->getBonusWin()) {?>
                    has <strong><?= ($game->getBlackJack() ? 'Blackjack' : $game->dealer->handTotal) ?></strong>
                    <?php }
                    ?>

                </p>
            </div>
        </div>
        <div class='col-lg-3'>
        </div>
    </div>
    <?php } ?>

    <div class='row row-no-gutters'>
        <?php foreach ($game->players as $seat => $player) { ?>
        <?php //if ($seat == 2) {?>

        <?php if (($player->isPatron()) && $player->hasButton()) { ?>

        <div class='col-lg-4'>
        </div>
        <div class='col-lg-4'>
            <div class='player-container playerButton'>
                <p><?php echo "<strong>{$player->getName()}</strong> " .   ($player->blackJack ? 'Blackjack' : "")  . " {$player->outcome}"; ?>
                    <br />
                    <?php echo "Hand summary : " . $player->handSummary() . " " . $player->handTotal; ?>
                </p>
                <form class="form-inline" method='POST' action="/blackjack-action">
                    <input type='hidden' value='<?=$seat?>' id='seat'
                        name='seat'>
                    <input type='radio' value='hit' id='hit' name='choice'>
                    <label for='hit'> Hit </label>
                    <input type='radio' value='stay' id='stay' name='choice'>
                    <label for='stay'> Stay</label>
                    <button class='button_submit' type='submit'>Your Choice</button>
                </form>
            </div>
        </div>
        <?php } else {?>


        <div class='col-lg-4'>
            <div class='player-container'>
                <p><?php echo "<strong>{$player->getName()}</strong> " .   ($player->blackJack ? 'Blackjack' : "")  . " {$player->outcome}"; ?>
                    <br />
                    <?php echo "Hand summary : " . $player->handSummary() . " " . $player->handTotal; ?>
                </p>
            </div>
        </div>
        <?php } ?>
        <?php } ?>
    </div>

    <?php dump($_SESSION); ?>

</div>

@endsection