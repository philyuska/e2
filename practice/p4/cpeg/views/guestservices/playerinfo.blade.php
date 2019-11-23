@extends('templates.master')

@section('content')
<div class='container'>
    <h1>Player Info <?=$patron->getName()?>
    </h1>
</div>

<div class='container'>
    <ul class='cpeg-ul'>
        <li>Token balance : <?=$patron->getTokens()?>
        </li>
        <li>Game history
            <ul>
                <li>1</li>
                <li>2</li>
                <li>3</li>
            </ul>
            <?php foreach ($patron->history as $entry) {?>
        <li><?=$entry?>
        </li>
        <?php }?>
        </li>
    </ul>
</div>

<div class='container text-center'>
    <a href='/services'>&larr; Return to Guest Services</a>
</div>

@endsection