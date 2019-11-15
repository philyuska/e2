@extends('templates.master')

@section('content')

<div class='container'>
    <h1>Guest Services</h1>
    <h2>Welcome {{ $name->getName() }}</h2>
</div>

<div class='container'>
    <div class="col-md-3">
        <ul class="nav nav-pills nav-stacked">
            <li><a href="/register">Join our Players Club</a></li>
            <li><a href="/playerinfo">Player Info</a></li>
            <li><a href="/lost-found">Lost and Found</a></li>
            <li><a href="/attractions">Local Attractions</a></li>
            <li><a href="/hospitals">Medical Facilities</a></li>
        </ul>
    </div>
</div>

@endsection