@extends('templates.master')

@section('content')

<div class='container'>
    <div id='accordion'>
        <div class='card'>
            <div class='card-header'>
                <div class='float-right'>
                    <form class='form-inline' method='POST' action='/register-destroy'>
                        <button class='button_submit' type='submit'>unJoin Players Club</button>
                    </form>
                </div>
                <h4>Welcome {{ $patronInfo['name'] }}</h4>
            </div>
            <div id='accordion-content' data-parent='#accordion'>
                <div class='card-body'>
                    <table class='table  table-hover table-sm'>
                        <thead>
                            <tr>
                                <th class='text-center'>Token Balance</th>
                                <th class='text-center'>Games Played</th>
                                <th class='text-center'>Games Won</th>
                                <th class='text-center'>Games Lost</th>
                                <th class='text-center'>Tokens Won</th>
                                <th class='text-center'>Tokens Lost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class='text-center'>{{ $patronInfo['token_balance'] }}</td>
                                <td class='text-center'>{{ $patronInfo['games_played'] }}</td>
                                <td class='text-center'>{{ $patronInfo['games_won'] }}</td>
                                <td class='text-center'>{{ $patronInfo['games_lost'] }}</td>
                                <td class='text-center'>{{ $patronInfo['tokens_won'] }}</td>
                                <td class='text-center'>{{ $patronInfo['tokens_lost'] }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<br />

<div class='container'>
    <h4>Game history</h4>
    <table class='table  table-hover table-sm'>
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Table</th>
                <th class='text-center'>Seat</th>
                <th>Outcome</th>
                <th>Hand Summary</th>
                <th class='text-center'>Wager</th>
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
                <td class='text-center'>{{ $entry['wager'] }}</td>
                <td class='text-center'>{{ $entry['token_win'] }}</td>
                <td class='text-center'>{{ $entry['token_loss'] }}</td>
                <td class='text-center'><a href='/game?hand_id={{ $entry["hand_id"] }}' class='btn btn-info' role='
                        button'>View</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection