@extends('templates.master')

@section('content')

<div class='container'>
    <div id='accordion'>
        <div class='card'>
            <div class='card-header'>
                <div class='float-right'>
                    <a href='/patron' class='btn btn-info' role='button'>Back</a>
                </div>
                <h4>Game detail for {{ $patronInfo['name'] }}</h4>
            </div>
            <div id='accordion-content' data-parent='#accordion'>
                <div class='card-body'>
                    <table class='table  table-hover table-sm'>
                        <thead>
                            <tr>
                                <th>Game Id</th>
                                <th>Timestamp</th>
                                <th>Table</th>
                                <th class='text-center'>Seat</th>
                                <th class='text-center'>Outcome</th>
                                <th>Hand Summary</th>
                                <th class='text-center'>Wager</th>
                                <th class='text-center'>Tokens Won</th>
                                <th class='text-center'>Tokens Lost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $game['hand_id'] }}</td>
                                <td>{{ $game['end_time'] }}</td>
                                <td>{{ $game['game'] }}</td>
                                <td class='text-center'>{{ $game['seat'] }}</td>
                                <td class='text-center'>{{ $game['outcome'] }}</td>
                                <td>{!! $game['hand_summary'] !!}</td>
                                <td class='text-center'>{{ $game['wager'] }}</td>
                                <td class='text-center'>{{ $game['token_win'] }}</td>
                                <td class='text-center'>{{ $game['token_loss'] }}</td>
                            </tr>
                        </tbody>
                    </table>


                    <h4>Detail</h4>
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
            </div>
        </div>
    </div>
</div>

@endsection