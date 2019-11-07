<?php
require './game-lib.php';

session_start();

$max_players = 2;
$inital_handsize = 2;
$dealer = $max_players + 1;

$max_decks = 4;
$gameplay = ( $_SESSION['gameplay'] ? $_SESSION['gameplay'] : 'takeseat' );

$handover = FALSE;

if ( $gameplay == 'takeseat' )
{
	$players = seat_players();
	$deck = shuffle_deck();
	
	$_SESSION['dealer'] = $dealer;
	$_SESSION['deck'] = $deck;
	$_SESSION['players'] = $players;	
}


if ( $gameplay == 'deal_hand' )
{
	$deck = $_SESSION['deck'];
	$players = $_SESSION['players'];

	deal_hand();
	$newhand = false;

	
	$_SESSION['dealer'] = $dealer;
	$_SESSION['deck'] = $deck;
	$_SESSION['players'] = $players;
}	



if ( isset( $_SESSION['players'] ) )
{
	$deck = $_SESSION['deck'];
	$players = $_SESSION['players'];
}


// if (! $handover )
// {
	// $x = whohas_thebutton();
	
	// if ( (! $players[ $x ]['guest'] ) && ( $x < $dealer ) )
	// {
		// auto_playhand( $x );
	// }

	// if ( $players[ $dealer ]['button'] )
	// {
		// $players[ $dealer ]['digest'][] = "Show";
		// show_hand( $dealer );

		// $active_players = $max_players;
		
		// for ( $x = 1; $x <= $max_players; $x++ )
		// {
			// if ( $players[ $x ]['total'] > 21 )
			// {
				// $active_players--;
			// }
			// if ( $players[ $x ]['blackjack'] )
			// {
				// $active_players--;
			// }			
		// }
		
		// if ( $active_players )
		// {
			// if ( $players[ $dealer ]['total'] < 17 )
			// {
				// while ( $players[ $dealer ]['total'] < 17 )
				// {
					// $players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, took hit";
					// draw_a_card( $dealer );
				// }
			// }
			// if ( $players[ $dealer ]['total'] > 21 )
			// {
				// $players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, busted";				
			// }

			// else
			// {
				// $players[ $dealer ]['digest'][] = "total is {$players[ $dealer ]['total']}, stayed";		
			// }
		// }
		
		// determine_outcome();
		// $handover = TRUE;
	// }
// }
