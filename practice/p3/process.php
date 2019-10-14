<?php
require './game-lib.php';

session_start();

$dealer = $_SESSION['game']['dealer'];
$deck = $_SESSION['deck'];
$players = $_SESSION['players'];

$x = whohas_thebutton();

$choice = $_GET['choice'];

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

if ( $choice == 'newgame' )
{
	unset( $_SESSION['deck'] );
	unset($_SESSION['players'] );
}
else
{
	$_SESSION['deck'] = $deck;
	$_SESSION['players'] = $players;
}

header('Location: index.php');