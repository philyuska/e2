@extends('templates.master')

@section('content')
<div class='container'>
    <h1>Player Info for {{ $patronInfo['name'] }}
    </h1>
</div>

<div class='container'>
    hand id : {{ $game['hand_id'] }}
    </h3>
</div>

<div class='container'>
    <h2>Game Outcome</h2>
    <table class='table  table-hover table-sm'>
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Table</th>
                <th class='text-center'>Seat</th>
                <th>Outcome</th>
                <th>Hand Summary</th>
                <th class='text-center'>Ante</th>
                <th class='text-center'>Tokens Won</th>
                <th class='text-center'>Tokens Lost</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $game['end_time'] }}</td>
                <td>{{ $game['game'] }}</td>
                <td class='text-center'>{{ $game['seat'] }}</td>
                <td>{{ $game['outcome'] }}</td>
                <td>{!! $game['hand_summary'] !!}</td>
                <td class='text-center'>{{ $game['ante'] }}</td>
                <td class='text-center'>{{ $game['token_win'] }}</td>
                <td class='text-center'>{{ $game['token_loss'] }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class='container'>
    <h2>Hand Detail</h2>
    <table class='table  table-hover table-sm'>
        <thead>
            <tr>
                <th>Patron</th>
                <th>Dealer</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>
                    <ul class='cpeg-ul'>
                        @foreach ($detail as $entry)
                        <li>{!! $entry['turn'] !!}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul class='cpeg-ul'>
                        @foreach ($dealer as $entry)
                        <li>{!! $entry['turn'] !!}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<div class='container text-center'>
    <a href='/patron'>&larr; Return to Player Info</a>
</div>

@endsection