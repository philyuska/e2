@extends('templates.master')

@section('content')

<div class='container'>
    <h1>Blackjack Handover</h1>
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
    <form class="form-inline" method='POST' action="/blackjack/leavetable">
        <button class='button_submit' type='submit'>Leave Table</button>
    </form>
    <form class="form-inline" method='POST' action="/blackjack/ante">
        <button class='button_submit' type='submit'>New hand</button>
    </form>
</div>


<div class='container'>

    @if ($game->getBonusWin())

    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='dealer-container'>
                @foreach ($game->dealer->hand as $card)
                <div class='card'>
                    <span
                        class='glyph <?=$card['suit']?>'><?=$card['glyph']?></span>

                    <div class='card-description'>
                        {{ $card["name"] }}
                    </div>
                </div>
                @endforeach
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

    @else

    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='dealer-container'>
                @foreach ($game->dealer->hand as $card)
                <div class='card'>
                    <span class='glyph {{ $card["suit"] }}'> {!! $card['glyph'] !!}</span>
                    <div class='card-description'>
                        {{ $card['name'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class=' col-lg-3'>
        </div>
    </div>
    @endif

    @if (! $game->getBonusWin())
    <div class='row row-no-gutters'>
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
    <div class='row'>

        @foreach ($game->players as $seat => $player)

        @if ($player->isPatron())
        <div class='col-lg-1 text-center playerButton'>
            {{ $seat }}
        </div>
        <div class='col-lg-4 playerButton'>
            <div class='row'>
                <p><strong> {{ $player->getName() }}</strong>
                    has <strong>{{ $player->blackJack ? 'Blackjack' : $player->handTotal
                        }}</strong>

                    {{ $player->outcome }}
                </p>
            </div>
            <div class='row no-gutters'>
                <p>
                    Hand summary : {!! $player->handSummary() !!}
                </p>
            </div>
        </div>

        @else

        <div class='col-lg-1 text-center'>
            {{ $seat }}
        </div>
        <div class='col-lg-4'>
            <div class='row'>
                <p><strong> {{ $player->getName() }}</strong>
                    has <strong>{{ $player->blackJack ? 'Blackjack' : $player->handTotal
                        }}</strong>

                    {{ $player->outcome }}
                    <br />
                    Hand summary : {!! $player->handSummary() !!}
                </p>
            </div>
        </div>
        @endif

        @if ($player->isPatron())
        <div class='col-lg-7 playerButton'>

            @if ($player->hasButton())
            <div class='row  playerButton'>
                <p>Advice : <strong>{{ $game->shouldHit($player) ? "Hit" : "Stay" }}</strong>
                </p>
            </div>
            <div class='row'>
                <form method='POST' action="/blackjack/choice">
                    <input type='hidden' value='{{ $seat }}' id='seat' name='seat'>
                    <input type='radio' value='hit' id='hit' name='choice' {{ $game->shouldHit($player) ? " checked" :
                    "" }}
                    >
                    <label for='hit'> Hit </label>
                    <input type='radio' value='stay' id='stay' name='choice' {{ ! $game->shouldHit($player) ? " checked"
                    : "" }}>
                    <label for='stay'> Stay</label>
                    <button class='button_submit' type='submit'>Your Choice</button>
                </form>
            </div>
            @endif
        </div>

        @else

        <div class='col-lg-7'>

        </div>
        @endif

        @endforeach

    </div>
</div>
<?php dump($game);?>
<?php //dump($_SESSION);?>
@endsection