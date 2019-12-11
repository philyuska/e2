@extends('templates.master')

@section('content')
<div class='container'>
    <h1>Players Club Registration Form</h1>
</div>

<div class='container'>
    <div class="col-md-6">
        <form class='form-inline' method='POST' action='/register-new'>
            @if($previousUrl)
            <input type hidden id='redirect' name='redirect' value='{{ $previousUrl }}'>
            @endif
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
            </div>
            <button type="submit" class="btn btn-default">Join</button>
        </form>
    </div>
    @if($app->errorsExist())
    <ul class='cpeg-ul error alert alert-danger'>
        @foreach($app->errors() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
</div>
<div class='container text-center'>
    <a href='/services'>&larr; Return to Guest Services</a>
</div>

@endsection