@extends('templates.master')

@section('title')
Add New Product
@endsection

@section('content')

@if($app->errorsExist())
<ul class='error alert alert-danger'>
    @foreach($app->errors() as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif

<div id='product-new'>
    <h2>Add new product</h2>


    <form method='POST' id='product-new' action='/products/save-new'>
        <div class='form-group'>
            <label for='name'>Name</label>
            <input type='text' class="form-control" name='name' id='name' value=''>
            <label for='description'>Description</label>
            <input type='text' class="form-control" name='description' id='description' value=''>
            <label for='price'>Price</label>
            <input type='text' class="form-control" name='price' id='price' value=''>
            <label for='available'>Available</label>
            <input type='text' class="form-control" name='available' id='available' value=''>
            <label for='weight'>Weight</label>
            <input type='text' class="form-control" name='weight' id='weight' value=''>
            <label for='isperishable'>Perishable</label>
            <input type='radio' class="form-control" name='perishable' id='isperishable' value='1'>
            <label for='notperishable'>Not Perishable</label>
            <input type='radio' class="form-control" name='perishable' id='notperishable' value='0'>
        </div>

        <button type='submit' class='btn btn-primary'>Add Product</button>
    </form>

</div>

<a href='/products'>&larr; Return to all products</a>

@endsection