@extends('templates.master')

@section('content')
<div class='container'>
    <h1>Players Club Registration Form</h1>
</div>

<div class='container'>
    <div class="col-md-6">
        <form class="form-inline" method='POST' action="/register-save">
            <div class="form-group">
                <label for="name">Name</label>
                <input required type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
            </div>
            <button type="submit" class="btn btn-default">Join</button>
        </form>
    </div>
</div>
<div class='container text-center'>
    <a href='/services'>&larr; Return to Guest Services</a>
</div>
@endsection