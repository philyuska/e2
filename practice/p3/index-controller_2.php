<?php
require './game-lib.php';

session_start();

$max_players = 2;
$inital_handsize = 2;
$dealer = $max_players + 1;

$max_decks = 4;
$gameover = FALSE;

if ( isset( $_SESSION['players'] ) )
{
	$deck = $_SESSION['deck'];
	$players = $_SESSION['players'];
}
else
{
	$game = array
	(
		'max_players' => $max_players,
		'dealer' => $dealer,
	);

	$players = array();

	$card_suits = array
	(
		'spades' => array( 'UBlock' => '1F0A' ),
		'hearts' => array( 'UBlock' => '1F0B' ),
		'diamonds' => array( 'UBlock' => '1F0C' ),
		'clubs' => array( 'UBlock' => '1F0D' ),
	);

	$card_ranks = array
	(
		1 => array( 'name' => "Ace", 'value' => 1, 'UByte' => '1' ),
		2 => array( 'name' => "2", 'value' => 2, 'UByte' => '2' ),
		3 => array( 'name' => "3", 'value' => 3, 'UByte' => '3' ),
		4 => array( 'name' => "4", 'value' => 4, 'UByte' => '4' ),
		5 => array( 'name' => "5", 'value' => 5, 'UByte' => '5' ),
		6 => array( 'name' => "6", 'value' => 6, 'UByte' => '6' ),
		7 => array( 'name' => "7", 'value' => 7, 'UByte' => '7' ),
		8 => array( 'name' => "8", 'value' => 8, 'UByte' => '8' ),
		9 => array( 'name' => "9", 'value' => 9, 'UByte' => '9' ),
		10 => array( 'name' => "10", 'value' => 10, 'UByte' => 'A' ),
		11 => array( 'name' => "Jack", 'value' => 10, 'UByte' => 'B' ),
		12 => array( 'name' => "Queen", 'value' => 10, 'UByte' => 'D' ),
		13 => array( 'name' => "King", 'value' => 10, 'UByte' => 'E' ),
	);

	for ( $i=1; $i<=$max_decks; $i++ )
	{
		foreach ( $card_suits as $suit => $suit_props )
		{
			foreach ( $card_ranks as $rank => $card )
			{
				$_card = array
				(
					'name' => $card['name'] . " of " . ucfirst( $suit ),
					'value' => $card['value'],
					'suit' => $suit,
					'rank' => $rank,
					'glyph' => '&#x' . $suit_props['UBlock'] . $card['UByte'] . ';',
				);

				$deck[] = $_card;
			}
		}
	}

	shuffle( $deck );

	for ( $i = 0; $i < $inital_handsize; $i++ )
	{
		for ( $x = 1; $x <= $max_players + 1; $x++ )
		{
			if (! array_key_exists( $x, $players ) )
			{ 
				$players[ $x ]['name'] = ( $x == $dealer ? "Dealer" : "Player " . $x );
				$players[ $x ]['guest'] = ( $x == 1 ? TRUE : FALSE );
				$players[ $x ]['seat'] = ( $x == $dealer ? 0 : $x );
				$players[ $x ]['hand'] = array();
				$players[ $x ]['hole'] = array();
				$players[ $x ]['total'] = 0;
				$players[ $x ]['blackjack'] = FALSE;
				$players[ $x ]['bonus'] = FALSE;
				$players[ $x ]['button'] = ( $x == 1 ? TRUE : FALSE );

				$players[ $x ]['digest'] = array('Deal');
				$players[ $x ]['outcome'] = ( $x == $dealer ? "" : "" );
			}

			if ( $x == $dealer && $i == ( $inital_handsize - 1 ) )
			{
				draw_a_hole_card( $x );	
			}
			else
			{
				draw_a_card( $x );
			}
		}
	}

	for ( $x = 1; $x <= $max_players; $x++ )
	{
		$players[ $x ]['advise_hit'] = should_draw_a_card( $x );
	}

	// for ( $x = 1; $x <= $max_players; $x++ )
	// {
		// if ( $players[ $x ]['guest'] )
		// {
			// $players[ $x ]['advise_hit'] = should_draw_a_card( $x );
			// break;
		// }
		// else
		// {
			// if (! $players[ $x ]['bonus'] )
			// {
				// while ( $players[ $x ]['total'] <= 21 )
				// {
					// $players[ $x ]['advise_hit'] = should_draw_a_card( $x );
					// $players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, took hit";
					// $players[ $x ]["button"] = FALSE;
					// $players[ $x+1 ]["button"] = TRUE;
					// draw_a_card( $x );
				// }

				// if ( $players[ $x ]['total'] > 21 )
				// {
					// $players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, busted";
					// $players[ $x ]["button"] = FALSE;
					// $players[ $x+1 ]["button"] = TRUE;
				// }
				// else
				// {
					// $players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, stayed";
					// $players[ $x ]["button"] = FALSE;
					// $players[ $x+1 ]["button"] = TRUE;
				// }
			// }
		// }
	// }
	
	if ( $players[ $dealer ]['bonus'] )
	{
		for ( $x = 1; $x <= $max_players; $x++ )
		{
			$players[ $x ]['digest'][] = "bonus win";
		}
		
		determine_outcome();
		$gameover = TRUE;		
	}
	elseif ( $players[ $dealer ]['blackjack'] )
	{
		show_hand( $dealer );
		
		$players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, black jack";
		
		for ( $x = 1; $x <= $max_players; $x++ )
		{
			if ( $players[ $x ]['blackjack'] )
			{
				$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, black jack";
			}
			else
			{
				$players[ $x ]['outcome'] = "Loser";
			}
		}
		
		determine_outcome();
		$gameover = TRUE;
	}
	else
	{
		$active_players = $max_players;

		for ( $x = 1; $x <= $max_players; $x++ )
		{
			if ( $players[ $x ]['blackjack'] )
			{
				$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, black jack";
			}

			if ( $players[ $x ]['bonus'] )
			{
				$players[ $x ]['digest'][] = "bonus win";
			}			
		}
	}
	
	$_SESSION['game'] = $game;
	$_SESSION['deck'] = $deck;
	$_SESSION['players'] = $players;
}

if ( $players[ $dealer ]['button'] )
{
	$players[ $dealer ]['digest'][] = "Show";
	show_hand( $dealer );

	$active_players = $max_players;
	
	for ( $x = 1; $x <= $max_players; $x++ )
	{
		if ( $players[ $x ]['total'] > 21 )
		{
			$active_players--;
		}
		if ( $players[ $x ]['blackjack'] )
		{
			$active_players--;
		}			
	}
	
	if ( $active_players )
	{
		if ( $players[ $dealer ]['total'] < 17 )
		{
			while ( $players[ $dealer ]['total'] < 17 )
			{
				$players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, took hit";
				draw_a_card( $dealer );
			}
		}
		if ( $players[ $dealer ]['total'] > 21 )
		{
			$players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, busted";				
		}

		else
		{
			$players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, stayed";		
		}
	}
	
	determine_outcome();
	$gameover = TRUE;
}
