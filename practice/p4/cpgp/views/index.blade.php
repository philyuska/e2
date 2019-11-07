@extends('templates.master')

@section('title')
{{ $welcome }}
@stop

@section('content')

<h2>{{ $welcome }}</h2>

<h2>Instructions</h2>
<ul>
    <li>Attempt to beat the dealer by getting a hand total closest to 21 without going over.</li>
    <li>Win instantly if the dealers hole card matches &#x1f4a9;.</li>
    <li>The dealer will stand with 17, and if thier hand total is greater than 21 "bust", you win.</li>
    <li>Press the Begin button to play.</li>
    <li>Advice is offered on whether to Hit or Stay but the choice is yours.If your hand total is greater than 21
        "bust", you lose.</li>
    <li><?php echo "cards in deck " . count($deck) ?>
    </li>
    <li><?php echo "took seat {$takeseat} new hand {$newhand}" ?>
    </li>
</ul>

@endsection