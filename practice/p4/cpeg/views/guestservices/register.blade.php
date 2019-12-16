@extends('templates.master')

@section('content')


<div class='container pt-3'>
    <h3>Whether your a new or returning player, our players club is the key to all the fun.</h3>
    <ul class='cpeg-ul'>
        <li>Earn valuable tokens</li>
        <li>Track your game play</li>
        <li>Help to fufill one of the project 4 requirements</li>
    </ul>
</div>

<div class='container'>
    <strong>Registration is easy.</strong>
    <form class='form-inline' method='POST' action='/register-new'>
        @if($previousUrl)
        <input type hidden id='redirect' name='redirect' value='{{ $previousUrl }}'>
        @endif
        <div class="form-group">

            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name and click">
        </div>
        <button type="submit" class="btn btn-info">Join</button>
    </form>

    @if($app->errorsExist())
    <ul class='cpeg-ul error alert alert-danger'>
        @foreach($app->errors() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif

</div>

@endsection