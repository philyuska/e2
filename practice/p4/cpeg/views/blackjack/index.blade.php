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
                        <li>Attempt to beat the dealer by getting a hand total closest to 21 without going over.</li>
                        <li>Aces are worth 1 or 11, hand splits are not allowed.</li>
                        <li>Win instantly if the dealers hole card matches &#x1f4a9;.</li>
                        <li>The dealer will stand with 17, and if thier hand total is greater than 21 "bust", you win.
                        </li>
                        <li>Advice is offered on whether to Hit or Stay but the choice is yours.If your hand total is
                            greater than 21
                            "bust", you lose.</li>
                        <li>Press the Take A Seat button to play.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if (! $scene )
<div class='container'>
    <form class="form-inline" method='POST' action="/blackjack/?action=takeseat">
        <button class='button_submit' type='submit'>Take A Seat</button>
    </form>
</div>
@else
<div class='container'>
    <form class="form-inline" method='POST' action="/blackjack/leavetable">
        <button class='button_submit' type='submit'>Leave the Table</button>
    </form>
</div>
@endif

<div class='container dealer-container'>

    @if ($game->getBonusWin())

    <div class='row'>
        <div class='col-lg-1'>
        </div>
        <div class='col-lg-2'>
            <div class='playingcard'>
                <!-- <span class='glyph'> {!! $game->deck->getCardBack() !!}</span> -->
                <span class='glyph'><img src='/images/jester_PNG12.png' width='70' height='78'></span>
                <div class='playingcard-description'>
                    Shoe: <strong>{{ $game->deck->getCardsRemaining() }}</strong>
                </div>
            </div>
        </div>
        <div class='col'>
            <div>
                @foreach ($game->dealer->hand as $card)
                <div class='playingcard'>
                    <div class='playingcard-description'>
                        {{ $card["name"] }}
                    </div>
                    <div
                        class='glyph <?=$card['suit']?>'>
                        <?=$card['glyph']?>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class='col-lg-3'>
        </div>
    </div>
    <div class='row'>
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

    @else
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
    @endif

    @if (! $game->getBonusWin())
    <div class='row'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='player-container'>
                <p><strong>{{ $game->dealer->getName() }}</strong>
                    has <strong>{{ $game->getBlackJack() ? 'Blackjack' : $game->dealer->handTotal }}</strong>
                </p>
            </div>
        </div>
        <div class='col-lg-3'>
        </div>
    </div>
    @endif

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
        <div class='col-lg-4'>
            <div class='row'>
                <p><strong> {{ $player->getName() }}</strong>
                    has <strong>{{ $player->blackJack ? 'Blackjack' : $player->handTotal()
                        }}</strong>
                    {{ $player->outcome }}
                    <br />
                </p>
            </div>
            <div class='row'>
                <p>
                    Hand summary : {!! $player->handSummary() !!}
                </p>
            </div>
        </div>
        <div class='col-lg-5'>
        </div>
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
        <div class='col-lg-4 playerButton'>
            <div class='row'>
                <p><strong> {{ $player->getName() }}</strong>
                    has <strong>{{ $player->blackJack ? 'Blackjack' : $player->handTotal()
                        }}</strong>
                    {{ $player->outcome }}
                    <br />
                </p>
            </div>
            <div class='row no-gutters'>
                <p>
                    Hand summary : {!! $player->handSummary() !!}
                </p>
            </div>
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
                <form class='form-inline' method='POST' action="/blackjack/ante">
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

        @if ($scene == "turn")

        <div class='col-lg-5 playerButton'>
            <div class='row'>
                @if($app->errorsExist())
                <ul class='cpeg-ul error alert alert-danger'>
                    @foreach($app->errors() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @else
                <p>Advice : <strong>{{ $game->shouldHit($player) ? "Hit" : "Stay" }}</strong>
                </p>
                @endif
            </div>

            <div class='row'>
                <form class='form-inline' method='POST' action="/blackjack/choice">
                    <input type='hidden' value='{{ $seat }}' id='seat' name='seat'>
                    <div class='form-check-inline'>
                        <input type='radio' value='hit' id='hit' name='choice' {{ $game->shouldHit($player) ? "
                        checked"
                        :
                        "" }}
                        >
                        <label for='hit'> Hit </label>
                    </div>
                    <div class='form-check-inline'>
                        <input type='radio' value='stay' id='stay' name='choice' {{ ! $game->shouldHit($player) ? "
                        checked"
                        : "" }}>
                        <label for='stay'> Stay</label>
                    </div>

                    <button class='button_submit' type='submit'>Your Choice</button>
                </form>
            </div>
        </div>

        @endif

    </div>

    @endif

    @endforeach

</div>


@endsection