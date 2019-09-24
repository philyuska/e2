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

        .container
		{
            width: 100%;
            margin: auto;
        }

        .hand
		{
            // width: 200px;
            // margin: auto;
            // text-align: center;
			// float: left;
        }
		
		.ul-card-deck
		{
			list-style-type: none;
			display: inline-block;
			margin: 0px;
			padding: 0px;
		}		

        .ul-card-deck li
		{
            font-size: 96px;
			float: left;
        }		
		
        .ul-card-deck .spades
		{
            color: black;
        }
		
        .ul-card-deck .hearts
		{
            color: red;
        }		

        .ul-card-deck .diamonds
		{
            color: blue;
        }

        .ul-card-deck .clubs
		{
            color: green;
        }
		
	</style>	

</head>

<body>

    <h1>[ game title ]</h1>

    <h2>Mechanics</h2>
    <ul>
        <li>step x.</li>
        <li>step y.</li>
        <li>step z.</li>
    </ul>

    <h2>Results</h2>
	
	<div class='container'>
	<?php foreach ( $players as $player => $cards ) { ?>
		<div class='hand'>
			<ul class='ul-card-deck'>
				<?php foreach ( $cards as $card ) { ?>
				<li class='<?php echo $card['suit']; ?>'><?php echo $card['glyph']; ?></li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
	</div>
</body>

</html>