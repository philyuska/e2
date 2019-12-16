@extends('templates.master')

@section('content')

@if (! $scene )

<div class='container'>
    <div id='accordion'>
        <div class='card'>
            <div class='card-header'>
                <div class='float-right'>
                    <form class="form-inline" method='POST' action="/blackjack/takeseat">
                        <button class='button_submit float-right' type='submit'>Take A Seat</button>
                    </form>
                </div>
                <h4>Take a seat at our Black Jack table</h4>
            </div>
            <div id='accordion-content' data-parent='#accordion'>
                <div class='card-body'>
                    <h5>Instructions</h5>
                    <ul class='cpeg-ul'>
                        <li>Attempt to beat the dealer by getting a hand total closest to 21 without going over.</li>
                        <li>Cards are ranked 2 thru King, Aces are worth 1 or 11.</li>
                        <li>Two cards are dealt to each player and the dealer.</li>
                        <li>Each player is given a chance to adjust their hand total by drawing additional cards "Hit"
                            or "Stay" when they are satisfied with the hand total.</li>
                        <li>The dealer will stay at 17, and if thier hand total is greater than 21 "Bust", you win.</li>
                        <li>If your hand total is greater than 21 "Bust", you lose.</li>
                        <li>Win instantly if the dealers hole card matches &#x1f4a9;.</li>

                    </ul>
                    Press the Take A Seat button to play.<br />
                    Visit the <a href='/patron'>Players Club</a> any time to view your game history.
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@if ($scene == 'newhand')
<div class='container'>
    <div id='accordion'>
        <div class='card'>
            <div class='card-header'>
                <div class='float-right'>
                    <form class="form-inline" method='POST' action="/blackjack/leavetable">
                        <button class='button_submit float-right' type='submit'>Leave the Table</button>
                    </form>
                </div>
                <h4>Wagers and Payouts</h4>
            </div>
            <div id='accordion-content' data-parent='#accordion'>
                <div class='card-body'>
                    <ul class='cpeg-ul'>
                        <li>Minimum wager is 1 token, maximum is 50</li>
                        <li>Black Jack payout is 2 to 1, all other winning hands payout at 1 to 1</li>
                        <li>yahPoo Bonus payout is a surprise</li>
                    </ul>
                    Place your wager in the field below and click the New Hand button to begin.<br />
                    Visit the <a href='/patron'>Players Club</a> any time to view your game history.
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if ($scene == 'turn')
<div class='container'>
    <div id='accordion'>
        <div class='card'>
            <div class='card-header'>
                <h4>Game Play</h4>
            </div>
            <div id='accordion-content' data-parent='#accordion'>
                <div class='card-body'>
                    <ul class='cpeg-ul'>
                        <li>Choose Hit to be dealt an additional card and adjust your hand total.</li>
                        <li>Choose Stay if you feel your hand total is sufficient to beat the dealers.</li>
                        <li>The advice offered is based upon basic blackjack strategy.
                        </li>
                    </ul>
                    Choose to Hit or Stay and click the Your Choice button to continue<br />
                    Visit the <a href='/patron'>Players Club</a> any time to view your game history.
                </div>
            </div>
        </div>
    </div>
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
                    <div class='glyph {{ $card["suit"] }}'>
                        {!! $card['glyph'] !!}
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
    <div class='row seat'>

        <div class='col-lg-1 text-center'>
            seat {{ $seat }}
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
    <div class='row seat playerButton'>
        <div class='col-lg-1 text-center'>
            seat {{ $seat }}
        </div>
        <div class='col-lg-2'>
            <div class='row no-gutters'>
                Tokens {{ $player->getTokens() }}
            </div>
            <div class='row no-gutters'>
                Wager {{ ( $player->getWager() ? $player->getWager() : "1") }}
            </div>
            @if ( $player->outcome )
            @if ( $player->handOutcome['playerLoss'] )
            <div class='row no-gutters'>
                Tokens lost {{ ( $player->handOutcome['playerLoss'] ? $player->getWager() : "0" ) }}
            </div>
            @else
            <div class='row no-gutters'>
                Tokens won {{ ( $player->payout ? $player->payout : "0") }}
            </div>
            @endif
            @endif

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
            <div class='row no-gutters'>
                <p>
                    Hand summary : {!! $player->handSummary() !!}
                </p>
            </div>
        </div>

        @if ($scene == "newhand")

        <div class='col-lg-5'>
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
                <form class='form-inline' method='POST' action="/blackjack/collectWager">
                    <input type='hidden' value='{{ $seat }}' id='seat' name='seat'>

                    <label for='wager'>Wager&nbsp;</label>
                    <div class='form-group'>
                        <input type='text' class='col-3'
                            value='{{ ( $player->getWager() ? $player->getWager() : "1" ) }}' id='wager' name='wager'>
                        <button class='button_submit' type='submit'>New Hand</button>
                    </div>
                </form>
            </div>
        </div>

        @endif

        @if ($scene == "turn")

        <div class='col-lg-5'>
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