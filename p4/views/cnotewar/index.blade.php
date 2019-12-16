@extends('templates.master')

@section('content')

@if (! $scene )

<div class='container'>
    <div id='accordion'>
        <div class='card'>
            <div class='card-header'>
                <div class='float-right'>
                    <form class="form-inline" method='POST' action="/cnotewar/takeseat">
                        <button class='button_submit float-right' type='submit'>Take A Seat</button>
                    </form>
                </div>
                <h4>Take a seat at our Cnote War table</h4>
            </div>
            <div id='accordion-content' data-parent='#accordion'>
                <div class='card-body'>
                    <!-- <h5>Instructions</h5> -->
                    <ul class='cpeg-ul'>
                        <li>Cards are ranked 2 thru King, and Aces are
                            always high, win if your card has a greater value than the dealers.</li>
                        <li>One card is dealt to each player and then to the dealer.</li>
                        <li>If your card is higher than the dealers, you win.</li>
                        <li>If your card is card is lower than the dealers card, you lose.</li>
                        <li>In the event of a tie "War" a new card is dealt to you and the dealer and a winner declared.
                        </li>
                        <li>If the War results in a tie you win 100 tokens instantly.</li>
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
                    <form class="form-inline" method='POST' action="/cnotewar/leavetable">
                        <button class='button_submit float-right' type='submit'>Leave the Table</button>
                    </form>
                </div>
                <h4>Wagers and Payouts</h4>
            </div>
            <div id='accordion-content' data-parent='#accordion'>
                <div class='card-body'>
                    <ul class='cpeg-ul'>
                        <li>Minimum wager is 1 token, maximum is 50.</li>
                        <li>Winning hands pay out at 1 to 1.</li>
                        <li>Winning War pays out at 2 to 1.</li>
                        <li>Tying War pays 100 tokens.</li>
                    </ul>
                    Place your wager in the field below and click the New Hand button to begin.
                </div>
            </div>
        </div>
    </div>
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
                <p><strong>{{ $game->dealer->getName() }}</strong>
                    has the {{ $game->dealer->getLastCard('name') }}
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

    <div class='row seat'>

        <div class='col-lg-1 text-center'>
            seat {{ $seat }}
        </div>
        <div class='col-lg-2'>
        </div>

        @if ($player->goneToWar())
        <div class='col-lg-4'>
            <div class='row'>
                <p><strong>{{ $player->getName() }} has
                        {!! $player->getLastCard() !!}
                        WAR
                        <br />
                    </strong>
                </p>
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
                <div class='col-md-6'>
                    {!! $game->dealer->warHandSummary($player->seat) !!}
                </div>
                <div class='col-md-6'>
                    {!! $player->warHandSummary() !!}
                </div>
                <div class='col-md-6'>
                </div>
                <div class='col-md-6'>
                    <strong>{{ $player->getOutcome() }}</strong>
                </div>
            </div>

        </div>

        @else

        <div class='col-lg-4'>
            <div class='row'>
                <p><strong>{{ $player->getName() }}</strong> has a
                    <strong>{!! $player->getLastCard() !!}</strong>
                    {{ $player->getOutcome() }}
                </p>
            </div>
        </div>

        @endif

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
                <p><strong>{{ $player->getName() }}</strong> has a
                    <strong>{!! $player->getLastCard() !!}
                        {{ $player->goneToWar() ? "WAR" : $player->getOutcome() }}</strong>
                </p>
            </div>

            @if ($player->goneToWar())

            <div class='row'>
                <div class='col-md-6'>
                    <strong>{{ $game->dealer->getName() }}</strong>
                </div>
                <div class='col-md-6'>
                    <strong>{{ $player->getName() }}</strong>
                </div>
            </div>

            <div class='row'>
                <div class='col-md-6'>
                    {!! $game->dealer->warHandSummary($player->seat) !!}
                </div>
                <div class='col-md-6'>
                    {!! $player->warHandSummary() !!}
                </div>
                <div class='col-md-6'>
                </div>
                <div class='col-md-6'>
                    <strong>{{ $player->getOutcome() }}</strong>
                </div>
            </div>

            @endif

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
                <form class='form-inline' method='POST' action="/cnotewar/collectWager">
                    <input type='hidden' value='{{ $seat }}' id='seat' name='seat'>

                    <label for='wager'>Wager&nbsp;</label>
                    <div class='form-group'>
                        <input type='text' class='col-3'
                            value='{{ ( $player->getWager() ? $player->getWager() : "1" ) }}' id='wager' name='wager'>
                        <button class='button_submit' type='submit'>New hand</button>
                    </div>
                </form>
            </div>
        </div>

        @endif

    </div>
    @endif
    @endforeach
</div>

@endsection