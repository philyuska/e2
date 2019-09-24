<?php require './index-controller.php';?>
<!DOCTYPE html>
<html lang='en'>

<head>

    <title>Practice project 2</title>
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

    <h1>[ Black Jack ]</h1>

    <h2>Mechanics</h2>
    <ul>
        <li>step x.</li>
        <li>step y.</li>
        <li>step z.</li>
    </ul>

    <h2>Results</h2>

	<?php foreach ( $players as $player => $cards ) { ?>
	<div>
		<p><?php echo "{$players[$player]['name']} {$players[$player]['outcome']} ({$players[$player]['total']})"; ?>
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