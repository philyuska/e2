@extends('templates.master')

@section('content')
<div class='container'>
    <h1>Player Info for {{ $patronInfo['name'] }}
    </h1>
</div>

<div class='container'>
    <h3>Token balance : {{ $patronInfo['token_balance'] }}
    </h3>

</div>

<div class='container'>
    <h2>Game history</h2>
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
                <th class='text-center'>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($history as $entry)
            <tr>
                <td>{{ $entry['end_time'] }}</td>
                <td>{{ $entry['game'] }}</td>
                <td class='text-center'>{{ $entry['seat'] }}</td>
                <td>{{ $entry['outcome'] }}</td>
                <td>{!! $entry['hand_summary'] !!}</td>
                <td class='text-center'>{{ $entry['ante'] }}</td>
                <td class='text-center'>{{ $entry['token_win'] }}</td>
                <td class='text-center'>{{ $entry['token_loss'] }}</td>
                <td class='text-center'><a href='/game?hand_id={{ $entry["hand_id"] }}' class='btn btn-info' role='
                        button'>Detail</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class='container text-center'>
    <a href='/services'>&larr; Return to Guest Services</a>
</div>

@endsection