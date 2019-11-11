@extends('templates.master')

@section('content')
<div class='container'>
    <h1>Registration Form</h1>
</div>

<div class='container'>
    <div class="col-md-6">
        <form class="form-inline" method='POST' action="/register_process.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input required type="text" class="form-control" id="name" placeholder="Enter your name" name="name">
            </div>
            <button type="submit" class="btn btn-default">Register</button>
        </form>
    </div>
</div>
@endsection