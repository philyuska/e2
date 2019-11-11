<!-- /views/products/missing.blade.php -->
@extends('templates.master')

@section('content')
<div id='product-error'>
    <h2>Product {{ $id }} not found</h2>

    <p>
        Uh oh - we were not able to find the product you were looking for.
    </p>
</div>

<a href='/products'>Check out our other products...</a>
@endsection