<?php

function whohas_thebutton()
{
	global $players;

	foreach ( array_keys( $players ) as $player )
	{
		if ( $players[ $player ]['button'] )
		{
			return ( $player );
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

function draw_a_card( $x )
{
	global $deck, $players;

	$drawn_card = array_shift( $deck );
	
	$players[ $x ]['hand'][] = $drawn_card;
	$players[ $x ]['digest'][] 	= $drawn_card['name'];

	unset ( $drawn_card );

	apply_gamerules( $x );
}

function draw_a_hole_card( $x )
{
	global $deck, $players;
	
	$_value = 0;
	$_name = 'Bummer';
	$_glyph = '&#x' . dechex( rand( hexdec("1f600"), hexdec("1f64f") ) ) . ';';

	$ur_thinking = rand( 1, 10 );
	$im_thinking = rand( 1, 10 );

	if ( $ur_thinking == $im_thinking )
	{
		$_name = 'yahPoo!';
		$_value = 21;
		$_glyph = '&#x1f4a9';
	}
	
	$_nullcard = array
	(
		'name' => $_name,
		'value' => $_value,
		'suit' => "",
		'rank' => "",
		'glyph' => $_glyph,
	);

	$drawn_card = array_shift( $deck );
	
	$players[ $x ]['hole'][] = $drawn_card;
	$players[ $x ]['hand'][] = $_nullcard;

	unset ( $drawn_card );
	
	apply_gamerules( $x );
}

function show_hand( $x )
{
	global $deck, $players;

	if ( array_key_exists( 'hole', $players[ $x ] ) )
	{
		foreach ( $players[ $x ]['hole'] as $card )
		{
			$players[ $x ]['hand'][1] = $players[ $x ]['hole'][0];
			$players[ $x ]['digest'][] = $players[ $x ]['hole'][0]['name'];
		}
		
		unset( $players[ $x ]['hole'] );
		
		apply_gamerules( $x );
	}
}

function apply_gamerules( $x )
{
	global $inital_handsize, $deck, $players, $dealer;

	if ( count( $players[ $x ]['hand'] ) < $inital_handsize )
	{
		return;
	}

	$players[ $x ]['total'] = 0;

	if ( count( $players[ $x ]['hand'] ) == $inital_handsize )
	{
		if ( $x == $dealer )
		{
			if ( array_key_exists( 'hole', $players[ $dealer ] ) )
			{
				if
				(
					( ( $players[ $dealer ]['hand'][0]['rank'] == 1 ) && ( $players[ $dealer ]['hole'][0]['value'] == 10 ) ) ||
					( ( $players[ $dealer ]['hand'][0]['value'] == 10 ) && ( $players[ $dealer ]['hole'][0]['rank'] == 1 ) )
				)
				{
					$players[ $dealer ]['blackjack'] = TRUE;
					$players[ $dealer ]['total'] = 21;
				}
				else
				{
					if ( $players[ $dealer ]['hand'][0]['rank'] == 1 )
					{
						$players[ $dealer ]['total'] = $players[ $dealer ]['total'] + 11;
					}
					else
					{
						$players[ $dealer ]['total'] = $players[ $dealer ]['total'] + $players[ $dealer ]['hand'][0]['value'];
					}
				}
				
				if ( $players[ $dealer ]['hand'][1]['value'] == 21 )
				{
					$players[ $dealer ]['bonus'] = TRUE;
					
					for ( $xb = 1; $xb <= ( count( $players ) - 1 ) ; $xb++ )
					{
						$players[ $xb ]['bonus'] = TRUE;
						$players[ $xb ]['total'] = 21;
					}					
				}
			}
			else
			{
				foreach ( $players[ $dealer ]['hand'] as $card )
				{
					if ( $card['rank'] <> 1 )
					{
						$players[ $dealer ]['total'] = $players[ $dealer ]['total'] + $card['value'];
					}					

					if ( $card['rank'] == 1 )
					{
						if ( ( $players[ $dealer ]['total'] + 11 ) > 21 )
						{
							$players[ $dealer ]['total'] = $players[ $dealer ]['total'] + 1;
						}
						else
						{
							$players[ $dealer ]['total'] = $players[ $dealer ]['total'] + 11;
						}
					}
				}					
			}
		}
		else
		{
			if ( $players[ $x ]['bonus'] )
			{
				$players[ $x ]['total'] = 21;
			}
			else
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
			
			if ( $players[ $x ]['total'] > 21 )
			{
				$players[ $x ]['outcome'] = "Busted";
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
				
				if ( $players[ $x ]['total'] > 21 )
				{
					$players[ $x ]['outcome'] = "Busted";
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
		if ( $players[ $x ]['bonus'] )
		{
			$players[ $x ]['outcome'] = "yahPoo Bonus Win!!";
		}
		else
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
}

function auto_playhand( $x )
{
	global $players;

	while ( ( $players[ $x ]['total'] <= 21 ) && ( should_draw_a_card( $x ) ) )
	{
		$players[ $x ]['advise_hit'] = should_draw_a_card( $x );
		$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, took hit";
		draw_a_card( $x );
	}

	if ( $players[ $x ]['total'] > 21 )
	{
		$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, busted";
	}
	else
	{
		$players[ $x ]['digest'][] = "total is {$players[ $x ]['total']}, stayed";
	}

	$players[ $x ]["button"] = FALSE;
	$players[ $x+1 ]["button"] = TRUE;	
}

function seat_players()
{
	global $max_players, $dealer;

	$players = array();

	$_playername = $_SESSION['playername'];
	$_seat = $_SESSION['seat'];	
	
	for ( $x = 1; $x <= $max_players + 1; $x++ )
	{
		if (! array_key_exists( $x, $players ) )
		{
			if ( $_seat == "auto" )
			{
				$_seat = rand( 1, $max_players );
			}
			if ( $x == $_seat )
			{
				$players[ $x ]['name'] = $_playername;
				$players[ $x ]['seat'] = $_seat;
				$players[ $x ]['guest'] = TRUE;
			}
			else
			{
				$players[ $x ]['name'] = ( $x == $dealer ? "Dealer" : "Player " . $x );					
				$players[ $x ]['seat'] = $x;
				$players[ $x ]['guest'] = FALSE;
			}
		}
	}

	return $players;
	
}

function shuffle_deck()
{
	global $max_decks;

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

	return $deck;
	
}


function deal_hand()
{
	global $deck, $dealer, $max_players, $inital_handsize, $players;

	for ( $x = 1; $x <= $max_players + 1; $x++ )
	{
		$players[ $x ]['hand'] = array();
		$players[ $x ]['hole'] = array();
		$players[ $x ]['total'] = 0;
		$players[ $x ]['blackjack'] = FALSE;
		$players[ $x ]['bonus'] = FALSE;
		$players[ $x ]['button'] = ( $x == 1 ? TRUE : FALSE );

		$players[ $x ]['digest'] = array('Deal');
		$players[ $x ]['outcome'] = ( $x == $dealer ? "" : "" );
	}

	for ( $i = 0; $i < $inital_handsize; $i++ )
	{
		for ( $x = 1; $x <= $max_players + 1; $x++ )
		{
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
	
	if ( $players[ $dealer ]['bonus'] )
	{
		for ( $x = 1; $x <= $max_players; $x++ )
		{
			$players[ $x ]['digest'][] = "bonus win";
		}
		
		determine_outcome();
		$handover = TRUE;
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
		$handover = TRUE;
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

	if (! $handover )
	{
		for ( $x = 1; $x <= $max_players; $x++ )
		{
			if (! $players[ $x ]['guest'] )
			{
				auto_playhand( $x );
			}
			else
			{
				$players[ $x ]['advise_hit'] = should_draw_a_card( $x );
				break;
			}
		}
	}	
}