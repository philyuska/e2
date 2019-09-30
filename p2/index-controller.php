<?php

$max_players = 2;
$inital_handsize = 2;
$dealer = $max_players + 1;

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

shuffle( $deck );

for ( $i = 0; $i < $inital_handsize; $i++ )
{
	for ( $x = 1; $x <= $max_players + 1; $x++ )
	{
		if (! array_key_exists( $x, $players ) )
		{ 
			$players[ $x ]['name'] = ( $x == $dealer ? "Dealer" : "Player " . $x );
			$players[ $x ]['seat'] = ( $x == $dealer ? 0 : $x );
			$players[ $x ]['hand'] = array();
			$players[ $x ]['total'] = 0;
			$players[ $x ]['blackjack'] = FALSE;

			$players[ $x ]['digest'] = array('Deal');
			$players[ $x ]['outcome'] = ( $x == $dealer ? "" : "Loser" );
		}

		draw_a_card( $x );
	}
}	

if ( $players[ $dealer ]['blackjack'] )
{
	$players[ $dealer ]['digest'][] = "total is {$players[ $x ]['total']}, black jack";
	
	for ( $x = 1; $x <= $max_players; $x++ )
	{
		if ( $players[ $x ]['blackjack'] )
		{
			$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, black jack";
		}
	}
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
		else
		{
			while ( ( $players[ $x ]['total'] <= 21 ) && ( should_draw_a_card( $x ) ) )
			{
				$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, took hit";
				draw_a_card( $x );
			}

			if ( $players[ $x ]['total'] > 21 )
			{
				$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, busted";
				$active_players--;
			}
			else
			{
				$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, stayed";		
			}
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
	else
	{
		$players[ $dealer ]['digest'][] = "House wins, all players busted";
	}
}	

determine_outcome();

foreach ( $players as $player => $player_data )
{
	$seat[ $player ] = $player_data;
}
array_multisort( $seat, $players );


function draw_a_card( $x )
{
	global $deck, $players;

	$drawn_card = array_shift( $deck );
	
	$players[ $x ]['hand'][] = $drawn_card;
	$players[ $x ]['digest'][] 	= $drawn_card['name'];

	unset ( $draw_card );

	apply_gamerules( $x );
}

function apply_gamerules( $x )
{
	global $inital_handsize, $deck, $players;

	if ( count( $players[ $x ]['hand'] ) < $inital_handsize )
	{
		return;
	}

	$players[ $x ]['total'] = 0;

	if ( count( $players[ $x ]['hand'] ) == $inital_handsize )
	{
		foreach ( $players[ $x ]['hand'] as $card )
		{
			if ( $card['rank'] <> 1 )
			{
				$players[ $x ]['total'] = $players[ $x ]['total'] + $card['value'];
			}
		}

		foreach ( $players[ $x ]['hand'] as $card )
		{
			if ( $card['rank'] == 1 )
			{
				if ( $players[ $x ]['total'] == 10 )
				{
					$players[ $x ]['blackjack'] = TRUE;
					$players[ $x ]['total'] = $players[ $x ]['total'] + 11;
				}
				else
				{
					if ( ( $players[ $x ]['total'] + 11 ) > 21 )
					{
						$players[ $x ]['total'] = $players[ $x ]['total'] + 1;
					}
					else
					{
						$players[ $x ]['total'] = $players[ $x ]['total'] + 11;
					}
				}
			}
		}		
	}
	
	if ( count( $players[ $x ]['hand'] ) > $inital_handsize )
	{
		foreach ( $players[ $x ]['hand'] as $card )
		{
			if ( $card['rank'] <> 1 )
			{
				$players[ $x ]['total'] = $players[ $x ]['total'] + $card['value'];
			}
		}

		foreach ( $players[ $x ]['hand'] as $card )
		{
			if ( $card['rank'] == 1 )
			{
				if ( ( $players[ $x ]['total'] + 11 ) > 21 )
				{
					$players[ $x ]['total'] = $players[ $x ]['total'] + 1;
				}
				else
				{
					$players[ $x ]['total'] = $players[ $x ]['total'] + 11;
				}
			}
		}
	}
}

function determine_outcome()
{
	global $players, $max_players, $dealer;

	for ( $x = 1; $x <= $max_players; $x++ )
	{
		if ( $players[ $x ]['total'] > 21 )
		{
			$players[ $x ]['outcome'] = "Busted";
		}
		else
		{
			if ( $players[ $dealer ]['total'] <= 21 )
			{
				if ( $players[ $x ]['total'] < $players[ $dealer ]['total'] )
				{			
					$players[ $x ]['outcome'] = "Loser";
				}
				if ( $players[ $x ]['total'] == $players[ $dealer ]['total'] )
				{			
					$players[ $x ]['outcome'] = "Push";
				}
				if ( $players[ $x ]['total'] > $players[ $dealer ]['total'] )
				{			
					$players[ $x ]['outcome'] = "Winner";
				}			
			}

			if ( $players[ $dealer ]['total'] > 21 )
			{
				$players[ $dealer ]['outcome'] = "Busted";
				
				if ( $players[ $x ]['total'] <= 21 )
				{			
					$players[ $x ]['outcome'] = "Winner";
				}
				else
				{
					$players[ $x ]['outcome'] = "Busted";
				}
			}
		}
	}
}

function should_draw_a_card( $x )
{
	global $players, $dealer;
	
	$strategy = array
	(
		'hard' => array
		(
			2	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			3	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			4	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			5	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			6	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			7	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			8	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			9	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			10	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			11	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			12	=> array( 1,2,3,7,8,9,10 ),
			13	=> array( 1,7,8,9,10 ),
			14	=> array( 1,7,8,9,10 ),
			15	=> array( 1,7,8,9,10 ),
			16	=> array( 1,7,8,9,10 ),
			17	=> array(),
			18	=> array(),
			19	=> array(),
			20	=> array(),
			21	=> array(),			
		),
		'soft' => array
		(
			13	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			14	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			15	=> array( 2,3,4,5,6,7,8,9,10 ),
			16	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			17	=> array( 1,2,3,4,5,6,7,8,9,10 ),
			18	=> array( 1,9,10 ),
			19	=> array(),
			20	=> array(),
			21	=> array(),
		),	
	);

	if ( $players[ $dealer ]['hand'][0]['value'] == 1 )
	{
		if ( $players[ $x ]['total'] > 12 )
		{
			if ( in_array( $players[ $dealer ]['hand'][0]['value'], $strategy['soft'][ $players[ $x ]['total'] ] ) )
			{
				return TRUE;
			}		
		}
	}
	
	if ( $players[ $x ]['total'] < 17 )  
	{
		if ( in_array( $players[ $dealer ]['hand'][0]['value'], $strategy['hard'][ $players[ $x ]['total'] ] ) )
		{
			return TRUE;
		}
	}

	return FALSE;
}
