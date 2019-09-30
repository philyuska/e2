# Project 2
+ By: *Phil Yuska*
+ Production URL: <http://p2.tymabus.fun>

## Game planning
* Play a hand of Blackjack. Multiple players attempt to beat the dealer by getting a hand total closest to 21 without going over.
* Model it after the traditional casino table game.

* Initialize a compound array of 52 playing cards `deck` containing the properties of each playing card 
	* name = plain english name and suit of card ( concatenation of card name and suit )
	* value = pip value
	* suit = spades, hearts, diamonds or clubs
	* rank = card rank Ace to King 1-13
	* glyph = Unicode character representing an image of the playing card

* Randomly `shuffle` the deck

* Initalize a compund array of `players` using the variable `x` as a key
	* name = concatenation of "Player" and the current value of `x` or Dealer if the key value equals the `dealer` variable
	* seat = set the display order used when rendering the html
	* hand = array containing the properties of each card dealt
	* total = running total of hand initially set to 0
	* blackjack = boolean indicating if the hand contains blackjack
	* digest = array containing the hand history
	* outcome = plain text indicating the final outcome of the hand Winner, Loser, Busted, Blackjack. Players had is initialized with Loser to save a loop in case the dealers initial two cards are a blackjack. 
	* Deal each player up to `max_players` and the dealer `max_players + 1` the `inital_handsize` of 2 cards via the UDF `draw_a_card`		

* After the initial cards are dealt check to see if the dealer has a blackjack and if so end the hand

* If the dealer does not have blackjack play continues until all players plus the dealer have had their turn

* Game play
	* Maintain a count of `active_players` used to determine if the dealer needs to act. Initially the counter is set to `max_players` and if a player busts the counter is decremented

	* Each player in turn will hit or stay based on the boolean result of the UDF `should_draw_a_card`
		* `should_draw_a_card` uses the `strategy` array and logic is based upon basic blackjack strategy where the dealer stands on a Soft 17 (ref: wizard of odds)
			* It will choose to use either 'hard' or 'soft' strategy if the dealer is showing an ace and factor in the players hand total (the array key) along with the card shown by the dealer (card zero). 
				* Yield TRUE to hit if the dealers show card is in the array
				* Yield FALSE to stay if it is not
		* If should_draw_a_card returns TRUE than call the UDF `draw_a_card` 
		* Update the hand digest with any action taken, eg stayed, took hit
		* Repeat until should_draw_a_card returns FALSE
		
	* After all players have had their turn, the dealer will act.
		* If there are still `active_players` and the dealers hand total is less than 17
		* Draw cards until busted or the hand total is 17 or greater

	* Once all players and the dealer have had a turn the final outcome of the round
		* The UDF `determine_outcome` compares each players hand to that of the dealer.
		* If the players hand total is greater, then declare the player a winner
		* If the players hand total is less, then declare the player a loser
		* If the players hand total is equal, then declare a push
		* If the dealers hand total geater than 21, then declare the dealer busted and any active player a winner

* Sort the `players` array by the key `seat` so the dealer's hand is displayed first by index.php 
		
		
## Outside resources
*your list of outside resources go here*
https://www.php.net/manual/en/
https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
https://secure.php.net/manual/en/function.array-multisort.php
https://en.wikipedia.org/wiki/Standard_52-card_deck
https://en.wikipedia.org/wiki/Playing_cards_in_Unicode
https://bicyclecards.com/how-to-play/blackjack/
https://wizardofodds.com/games/blackjack/strategy/4-decks/

## Notes for instructor
*any notes for me to refer to while grading; if none, omit this section*