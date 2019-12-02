@extends('templates.master')

@section('content')

<div class='container'>
    <div id='accordion'>
        <div class='card'>
            <div class='card-header'>
                <a class='card-link' data-toggle="collapse" href="#instructions">
                    <h4>Instructions</h4>
                </a>
            </div>
            <div id='instructions' class='{{ ( $scene ? "collapse" : "" ) }}' data-parent='#accordion'>
                <div class='card-body'>
                    <ul class='cpeg-ul'>
                        <li>One card is dealt to each player and then to the dealer.</li>
                        <li>If the players card is higher than the dealers card, that player wins.</li>
                        <li>If the players card is lower than the dealers card, that player loses.</li>
                        <li>Aces are always high.</li>
                        <li>In the event of a tie a new card will be dealt to the player and dealer until ther is a
                            winner.</li>
                        <li>Press the Take A Seat button to play.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if (! $scene )
<div class='container'>
    <form class="form-inline" method='POST' action="/casinowar/?action=takeseat">
        <button class='button_submit' type='submit'>Take A Seat</button>
    </form>
</div>
@else
<div class='container'>
    <form class="form-inline" method='POST' action="/casinowar/leavetable">
        <button class='button_submit' type='submit'>Leave the Table</button>
    </form>
</div>
@endif

<div class='container dealer-container'>

    <div class='row'>
        <div class='col-lg-1'>
        </div>
        <div class='col-lg-2'>
            <div class='playingcard'>
                <span class='glyph'><img src='/images/jester_PNG12.png' width='70' height='78'></span>
                <div class='playingcard-description'>
                    Shoe: <strong>{{ $game->deck->getCardsRemaining() }}</strong>
                </div>
            </div>
        </div>
        <div class='col'>
            <div>
                @if( $game->dealer->hand )
                @foreach ($game->dealer->hand as $card)
                <div class='playingcard'>
                    <span class='glyph {{ $card["suit"] }}'> {!! $card['glyph'] !!}</span>
                    <div class='playingcard-description'>
                        {{ $card['name'] }}
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        <div class=' col-lg-3'>
        </div>
    </div>

    <div class='row'>
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
</div>

<div class='container'>
    @foreach ($game->players as $seat => $player)
    @if (! $player->isPatron())
    <div class='row'>

        <div class='col-lg-1 text-center'>
            {{ $seat }}
        </div>
        <div class='col-lg-2'>
        </div>

        @if ($player->goneToWar())
        <div class='col-lg-4'>

            <div class='row'>
                <div class='player-container'>
                    <p><strong>{{ $player->getName() }} has
                            {!! $player->getLastCard() !!}
                            WAR
                            <br />
                        </strong>
                    </p>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <strong>{{ $game->dealer->getName() }}</strong>
                </div>
                <div class='col-md-6'>
                    <strong>{{ $player->getName() }}</strong>
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
                    <strong>{{ $player->getOutcome() }}</strong>
                </div>
            </div>
        </div>
        @else

        <div class='col-lg-4'>
            <div class='player-container'>
                <p><strong>{{ $player->getName() }}</strong> has a
                    <strong>{!! $player->getLastCard() !!}</strong>
                    {{ $player->getOutcome() }}
                </p>
            </div>
        </div>
        @endif
    </div>
    @else
    <div class='row'>

        <div class='col-lg-1 text-center playerButton'>
            {{ $seat }}
        </div>
        <div class='col-lg-2 playerButton'>
            <div class='row no-gutters'>
                Tokens {{ $player->getTokens() }}
            </div>
            <div class='row no-gutters'>
                Ante {{ ( $player->getAnte() ? $player->getAnte() : "1") }}
            </div>
            @if ( $player->outcome )
            @if ( $player->handOutcome['playerLoss'] )
            <div class='row no-gutters'>
                Tokens lost {{ ( $player->handOutcome['playerLoss'] ? $player->getAnte() : "0" ) }}
            </div>
            @else
            <div class='row no-gutters'>
                Tokens won {{ ( $player->payout ? $player->payout : "0") }}
            </div>
            @endif
            @endif

        </div>

        @if ($scene == "newhand")

        <div class='col-lg-5 playerButton'>
            <div class='row'>
                @if($app->errorsExist())
                <ul class='cpeg-ul error alert alert-danger'>
                    @foreach($app->errors() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @else
                <p> &nbsp; </p>
                @endif
            </div>
            <div class='row'>
                <form class='form-inline' method='POST' action="/casinowar/ante">
                    <input type='hidden' value='{{ $seat }}' id='seat' name='seat'>

                    <label for='ante'>Ante&nbsp;</label>
                    <div class='form-group'>
                        <input type='text' class='col-3' value='{{ ( $player->getAnte() ? $player->getAnte() : "1" ) }}'
                            id='ante' name='ante'>
                        <button class='button_submit' type='submit'>New hand</button>
                    </div>
                </form>
            </div>
        </div>

        @endif

        @if ($player->goneToWar())
        <div class='col-lg-4'>

            <div class='row'>
                <div class='player-container'>
                    <p><strong>{{ $player->getName() }} has
                            {!! $player->getLastCard() !!}
                            WAR
                            <br />
                        </strong>
                    </p>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-6'>
                    <strong>{{ $game->dealer->getName() }}</strong>
                </div>
                <div class='col-md-6'>
                    <strong>{{ $player->getName() }}</strong>
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
                    <strong>{{ $player->getOutcome() }}</strong>
                </div>
            </div>
        </div>
        @else

        <div class='col-lg-4'>
            <div class='player-container'>
                <p><strong>{{ $player->getName() }}</strong> has a
                    <strong>{!! $player->getLastCard() !!}</strong>
                    {{ $player->getOutcome() }}
                </p>
            </div>
        </div>
        @endif
    </div>
    @endif
    @endforeach
</div>

@endsection