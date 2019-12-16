@extends('templates.master')

@section('content')

<div class='container'>
    <div id='accordion'>
        <div class='card'>
            <div class='card-header'>
                <div class='float-right'>
                    <form class="form-inline" method='POST' action="/blackjack/leavetable">
                        <button class='button_submit float-right' type='submit'>Leave the Table</button>
                    </form>
                </div>
                <h4>Black Jack Wagers and Payouts</h4>
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

@foreach ($game->players as $seat => $player)

@if (! $player->isPatron())
<div class='container'>
    <div class='row seat'>

        <div class='col-lg-1 text-center'>
            seat {{ $seat }}
        </div>
        <div class='col-lg-2'>
            <div class='row no-gutters'>
                {{ $player->getName() }}
            </div>

        </div>

        <div class='col-lg-3'>
            <div class='row no-gutters'>
            </div>
        </div>

        <div class='col-lg-6'>
            <div class='row'>
            </div>
        </div>
    </div>
</div>
@else

<div class='container'>
    <div class='row seat playerButton'>

        <div class='col-lg-1 text-center'>
            seat {{ $seat }}
        </div>
        <div class='col-lg-2'>
            <div class='row no-gutters'>
                {{ $player->getName() }}
            </div>

        </div>

        <div class='col-lg-3'>
            <div class='row no-gutters'>
                Token Balance {{ $player->getTokens() }}
            </div>
        </div>

        <div class='col-lg-6'>
            <div class='row'>
                <div class='row'>
                    <form class='form-inline' method='POST' action="/blackjack/collectWager">
                        <input type='hidden' value='{{ $seat }}' id='seat' name='seat'>

                        <label for='wager'>Wager&nbsp;</label>
                        <div class='form-group'>
                            <input type='text' class='col-3'
                                value='{{ ( $player->getWager() ? $player->getWager() : "1" ) }}' id='wager'
                                name='wager'>
                            <button class='button_submit' type='submit'>New hand</button>
                        </div>
                    </form>
                </div>

                @if($app->errorsExist())
                <div class='row'>
                    <ul class='cpeg-ul error alert alert-danger'>
                        @foreach($app->errors() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

@endsection