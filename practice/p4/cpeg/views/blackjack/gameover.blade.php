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
            <div id='instructions' class='collapse' data-parent='#accordion'>
                <div class='card-body'>
                    <ul class='cpeg-ul'>
                        <li>Attempt to beat the dealer by getting a hand total closest to 21 without going over.</li>
                        <li>Aces are worth 1 or 11, hand splits are not allowed.</li>
                        <li>Win instantly if the dealers hole card matches &#x1f4a9;.</li>
                        <li>The dealer will stand with 17, and if thier hand total is greater than 21 "bust", you win.
                        </li>
                        <li>Advice is offered on whether to Hit or Stay but the choice is yours.If your hand total is
                            greater
                            than
                            21
                            "bust", you lose.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class='container'>

    <div class='row row-no-gutters'>
        <div class='col-lg-12 center'>
            Number of cards remaining in shoe {{ $game->deck->getCardsRemaining() }}
        </div>
    </div>

    @if ($game->getBonusWin())

    <div class='row row-no-gutters'>
        <div class='col-lg-3'>
        </div>
        <div class='col-lg-6'>
            <div class='dealer-container'>
                @foreach ($game->dealer->hand as $card)
                <div class='playingcard'>
                    <span
                        class='glyph <?=$card['suit']?>'><?=$card['glyph']?></span>

                    <div class='playingcard-description'>
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
                <div class='playingcard'>
                    <span class='glyph {{ $card["suit"] }}'> {!! $card['glyph'] !!}</span>
                    <div class='playingcard-description'>
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
        <div class='col-lg-2 playerButton'>
            <div class='row no-gutters'>
                Tokens {{ $player->getTokens() }}

            </div>
            <div class='row no-gutters'>
                Tokens won {{ ( $player->payout ? $player->payout : "0") }}
            </div>
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

        @else

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
                    Hand summary : {!! $player->handSummary() !!}
                </p>
            </div>
        </div>
        @endif

        @if ($player->isPatron())
        <div class='col-lg-5 playerButton'>
            <div class='row'>
                <p> &nbsp; </p>
            </div>
            <div class='row'>
                <form class='form-inline' method='POST' action="/blackjack/ante-collect">
                    <input type='hidden' value='{{ $seat }}' id='seat' name='seat'>

                    <label for='wager'>Wager&nbsp;</label>
                    <div class='form-group'>
                        <input type='text' class='col-2' value='{{ $player->getAnte() }}' id='wager' name='wager'>
                        <button class='button_submit' type='submit'>New hand</button>
                    </div>
                </form>
            </div>
        </div>

        @else

        <div class='col-lg-5'>
        </div>
        @endif

        @endforeach

    </div>
</div>

@endsection