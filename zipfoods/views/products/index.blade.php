@extends('templates.master')

@section('title')
All Products
@endsection

@section('content')
<h2>All Products</h2>


<div id='product-index'>
    @foreach($products as $product)
    <a href='/product?id={{ $product["id"] }}'>
        <div class='product'>
            <div class='product-name'>{{ $product['name'] }}</div>
            <img class='product-thumb' src="/images/products/{{ $product['id'] }}.jpg">
        </div>
    </a>
    @endforeach
</div>
<div id='product-new'>
    <a href='/products/new'>Add a new product
</div>

@endsection