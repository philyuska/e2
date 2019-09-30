<?php require './index-controller.php';?>
<!DOCTYPE html>
<html lang='en'>

<head>

    <title>Blackjack Game Simulator</title>
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
		
	</style>	

</head>

<body>

    <h1>Black Jack</h1>

    <h2>Mechanics</h2>
    <ul>
        <li>Players attempt to beat the dealer by getting a hand total closest to 21 without going over.</li>
		<li>If the dealers hand total is greater than 21 "bust", all active players are declared winners.</li>
		<li>If the players hand total is greater than 21, that player loses and no longer participates in the round.</li> 		
        <li>Aces can be worth 1 or 11, face cards worth 10, and all other cards worth the pip value displayed on the card.</li>
		<li>Cards are dealt in a clockwise fashion beginning with the Player 1. The dealer is last to act.</li>
        <li>Each player is dealt two cards facing up from the deck, but the dealers second card remains face down until it is their turn to act.</li>
		<li>Each player in turn may choose to "stay" or recieve additional cards "hit" until they feel their hand total is sufficient to beat the dealers.</li>
        <li>Once all players have had their turn, the dealer will reveal the card that was dealt face down and draw additional cards until their hand total is 17 or greater.</li>
        <li>If the players hand total is greater than the dealers that player is declared a winner.</li>
        <li>If the players hand total is equal to the dealers a "push" is declared and there is no winner.</li> 				
    </ul>

    <h2>Results</h2>

	<?php foreach ( $players as $player => $cards ) { ?>
	<div>
		<p><?php echo "{$players[$player]['name']} " . ( $players[$player]['blackjack'] ? 'Blackjack' : "total : " . $players[$player]['total'] ) . " {$players[$player]['outcome']}" ; ?>
		<br />Hand digest: <?php echo join(', ', $players[$player]['digest'] ); ?></p>
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
	
</body>

</html>