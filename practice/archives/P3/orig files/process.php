<?php
require './game-lib.php';

session_start();

$gameplay = $_GET['gameplay'];

if ( $gameplay == 'takeseat' )
{
	unset( $_SESSION['deck'] );
	unset( $_SESSION['players'] );
	
	$_SESSION['playername'] = $_GET['playername'];
	$_SESSION['seat'] = $_GET['seat'];
	$_SESSION['gameplay'] = $_GET['gameplay'];
}
if ( $gameplay == 'deal_hand' )
{
	$_SESSION['gameplay'] = $_GET['gameplay'];
}
else
{
	$choice = $_GET['choice'];

	$dealer = $_SESSION['dealer'];
	$deck = $_SESSION['deck'];
	$players = $_SESSION['players'];

	$x = whohas_thebutton();

	if ( $choice == "hit" )
	{
		$players[ $x ]['digest'][] = "took hit";
		draw_a_card( $x );
		$players[ $x ]['advise_hit'] = should_draw_a_card( $x );	
		
		if ( $players[ $x ]['total'] >= 21 )
		{
			$players[ $x ]["button"] = FALSE;
			$players[ $x+1 ]["button"] = TRUE;
		}
	}

	if ( $choice == "stay" )
	{
		$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, stayed";
		$players[ $x ]["button"] = FALSE;
		$players[ $x+1 ]["button"] = TRUE;
	}

	$_SESSION['deck'] = $deck;
	$_SESSION['players'] = $players;

}
header('Location: index.php');