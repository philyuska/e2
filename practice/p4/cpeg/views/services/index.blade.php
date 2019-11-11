@extends('templates.master')

@section('content')
<div class='container'>
    <h1>Guest Services</h1>
    <h2>Welcome valued guest</h2>
</div>

<div class='container'>
    <div class="col-md-3">
        <ul class="nav nav-pills nav-stacked">
            <li><a href="/register">Join the players club</a></li>
            <li><a href="/playerinfo">Player Info</a></li>
        </ul>
    </div>
</div>

@endsection