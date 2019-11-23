@extends('templates.master')

@section('content')

<div class='container'>
    <h1>Guest Services</h1>
    <h2>Welcome <?=($patron ? $patron->getName() : "")?>
    </h2>
</div>

<div class='container'>
    <div class='list-group'>
        <?php if (!$patron->getName()) {?>
        <a class='list-group-item list-group-item-action' href="/register">Join our Players Club</a>
        <?php } else {?>
        <a class='list-group-item list-group-item-action' href="/register-destroy">unJoin our Players Club</a>
        <a class='list-group-item list-group-item-action' href="/playerinfo">Player Info</a>
        <?php }?>
        <a class='list-group-item list-group-item-action' href="/lost-found">Lost and Found</a>
        <a class='list-group-item list-group-item-action' href="/attractions">Local Attractions</a>
        <a class='list-group-item list-group-item-action' href="/hospitals">Medical Facilities</a>
    </div>
</div>

<?php dump($_SESSION);?>
<?php dump($patron);?>

@endsection