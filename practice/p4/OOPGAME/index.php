<?php require './index-controller.php';?>
<!DOCTYPE html>
<html lang='en'>

<head>

    <title>Blackjack</title>
    <meta charset='utf-8'>

    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <style>
	
		body
		{
			font-family: Arial, Helvetica, sans-serif;
		}

		.grid-container
		{
			display: inline-grid;
			grid-template-columns: auto auto auto auto auto auto;
			grid-column-gap: 10px;
			grid-row-gap: 50px;
			padding: 10px;
		}

		.grid-item
		{
			text-align: center;
			border: 1px solid;
		}

        .container
		{
            padding-top: 0px;
			padding-bottom: 4px;
			font-size: 12px;
			text-align: center;
        }

        .card
		{
			box-shadow: 0 4px 8px 0;
			width: 100px;
			height: 100%;
        }

        .glyph
		{
			margin: 0px;
			padding: 0px;
			font-size: 64px;
			text-align: center;
        }		

        .glyph__mini
		{
			margin: 0px;
			padding: 0px;
			font-size: 64px;
			text-align: center;
        }
		
        .spades
		{
            color: black;
        }
		
        .hearts
		{
            color: red;
        }		

        .diamonds
		{
            color: blue;
        }

        .clubs
		{
            color: green;
        }
		
		select
		{
			padding: 4px;
		}
		
        .button
		{
			color: green;
			padding: 4px 12px;
			text-align: center;
			font-size: 12px;
			font-weight: bold;
			margin-left: 12px;
        }
		
        .button_submit
		{
            background-color: white;
			border: 2px solid green;
			color: black;
			padding: 8px 16px;
			text-align: center;
			font-size: 12px;
			font-weight: bold;
			width: 160px;
        }
		
		.active_player
		{
			background-color: #f7f7f7;
			font-weight: bold;
		}
		
	</style>	

</head>

<body>

    <h1>Blackjack</h1>

    <h2>Instructions</h2>
    <ul>
        <li>Attempt to beat the dealer by getting a hand total closest to 21 without going over.</li>
		<li>Win instantly if the dealers hole card matches &#x1f4a9;.</li>
		<li>The dealer will stand with 17, and if thier hand total is greater than 21 "bust", you win.</li>
		<li>Press the Begin button to play.</li>
		<li>Advice is offered on whether to Hit or Stay but the choice is yours.If your hand total is greater than 21 "bust", you lose.</li>
		<li><?php echo "cards in deck " . count($deck) ?></li> 
		<li><?php echo "took seat {$takeseat} new hand {$newhand}" ?></li> 		
    </ul>
	
    <form method='GET' action='process.php'>

		<?php if ( $gameplay == 'takeseat' ) { ?>
		
		<input type='hidden' name='gameplay' id='gameplay' value='deal_hand'>
		
        <label for='playername'> Player name</label>
        <input type='text' value='' id='playername' name='playername'>

        <label for='playername'> Choose a seat</label>
        <select name='seat'>
			<option value='1'>1</option>
			<option value='2'>2</option>
			<option value='auto' selected>Auto</option>
		</select>
		
		<button class='button' type='submit'>Play</button>

		<?php } elseif ( $handover ) { ?>		

        <input type='radio' value='newhand' id='newhand' name='gameplay' checked >
        <label for='newhand'> New Hand</label>

        <input type='radio' value='newgame' id='newgame' name='gameplay'>
        <label for='newgame'> Leave Table</label>
		
        <button class='button' type='submit'>Your Choice</button>

		<?php } else { ?>
		<?php $x = whohas_thebutton();?>
		<?php if ( $players[ $x ]['guest'] ) {?>

        <input type='radio' value='hit' id='hit' name='choice' <?php if ( $players[ $x ]['advise_hit'] ) { echo 'checked'; } ?> >
        <label for='hit'> Hit</label>

        <input type='radio' value='stay' id='stay' name='choice' <?php if (! $players[ $x ]['advise_hit'] ) { echo 'checked'; } ?>>
        <label for='stay'> Stay</label>
		
        <button class='button_submit' type='submit'>Your Choice</button> <strong><?php echo " {$players[ $x ]['name']}"; ?></strong>
		
		<p><em>Advice:<strong> <?php echo ( $players[ $x ]['advise_hit'] ? "Hit" : "Stay" ) ?> </strong></em></p>

		<?php } ?>
		<?php } ?>		
	
		

    </form>	

	<?php foreach ( $players as $player => $cards ) { ?>
	<div>
		<div class=' <?php echo ( $players[ $player ]['button'] ? "active_player" : "" ); ?> '>
			<p><?php echo "{$players[ $player ]['name']} " . ( $players[ $player ]['blackjack'] ? 'Blackjack' : "total : " . $players[ $player ]['total'] ) . " {$players[ $player ]['outcome']}"; ?><br />
			Hand digest: <?php echo join(', ', $players[ $player ]['digest'] ); ?> </p>
		</div>	
		<div class='grid-container'>
			<?php foreach ( $cards['hand'] as $card ) { ?>
			<div class='grid-item'>
				<div class='card'>
					<span class='glyph <?php echo $card['suit']; ?>'><?php echo $card['glyph']; ?></span>
					<div class='container'><p><?php echo $card['name']; ?></p></div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
	
<?php
print "<pre>";
print_r( $players );
print "</pre>";
?>
	
</body>

</html>