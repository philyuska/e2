# Project 4
+ By: *Phil Yuska*
+ Production URL: <http://practice.tymabus.fun/p4>

## Game planning
* Play a hand of Blackjack. A single player attempts to beat the dealer by getting a hand total closest to 21 without going over.
* Model it after the traditional casino table game.
* Build upon project 3 by incorporating objects in place of game-lib.php

* Initialize a compound array of 208 playing cards `deck` containing the properties of each playing card 
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
	* bonus = boolean indicating if the player won the bonus feature
	* button = token used to track the active player 
	* digest = array containing the hand history
	* outcome = plain text indicating the final outcome of the hand Winner, Loser, Busted, Blackjack. Players had is initialized with Loser to save a loop in case the dealers initial two cards are a blackjack
	* Deal each player up to `max_players` and the dealer `max_players + 1` the `inital_handsize` of 2 cards via the UDF `draw_a_card`
	* Deal a random hole/down card to the dealer via the UDF `draw_a_hole_card` so as not to reveal the dealers entire hand to the player
	* Use the hole card as part of the bonus feature

* After the initial cards are dealt
	* check to see if the dealer has a blackjack and if so end the hand
	* check to see if the dealer hole card triggers a bonus win and if so end the hand.

* If there is no bonus win and the dealer does not have blackjack play continues until all players plus the dealer have had their turn

* Game play
	* Maintain a count of `active_players` used to determine if the dealer needs to act. Initially the counter is set to `max_players` and if a player busts the counter is decremented

	* Each player in turn will have the option to play thyeir hand hit or stay based on the boolean result of the UDF `should_draw_a_card`
		* `should_draw_a_card` uses the `strategy` array and logic is based upon basic blackjack strategy where the dealer stands on a Soft 17 (ref: wizard of odds)
			* It will choose to use either 'hard' or 'soft' strategy if the dealer is showing an ace and factor in the players hand total (the array key) along with the card shown by the dealer (card zero) 
				* Yield TRUE to hit if the dealers show card is in the array
				* Yield FALSE to stay if it is not
		* If should_draw_a_card returns TRUE, advise the player they should hit and default the `hit` radio input
		* If should_draw_a_card returns FALSE, advise the player they should stay and default the `stay` radio input		
		* Update the hand digest with any action taken, eg stayed, took hit
		* Repeat until the players choice selection is stay
		
	* After all players have had their turn, the dealer will act
		* If there are still `active_players` and the dealers hand total is less than 17
		* Draw cards until busted or the hand total is 17 or greater

	* Once all players and the dealer have had a turn the final outcome of the round
		* The UDF `determine_outcome` compares each players hand to that of the dealer
		* If the players hand total is greater, then declare the player a winner
		* If the players hand total is less, then declare the player a loser
		* If the players hand total is equal, then declare a push
		* If the dealers hand total geater than 21, then declare the dealer busted and any active player a winner
		
		
## Outside resources
https://www.php.net/manual/en/
https://www.php.net/manual/en/function.substr.php
https://www.php.net/manual/en/function.is-object.php
https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
https://secure.php.net/manual/en/function.array-multisort.php
https://en.wikipedia.org/wiki/Standard_52-card_deck
https://en.wikipedia.org/wiki/Playing_cards_in_Unicode
https://bicyclecards.com/how-to-play/blackjack/
https://wizardofodds.com/games/blackjack/strategy/4-decks/
https://www.w3schools.com/howto/howto_css_cards.asp
#
https://www.casinosupply.com/pages/search-results/shoe
https://en.wikipedia.org/wiki/Glossary_of_blackjack_terms
https://emojipedia.org
https://stackoverflow.com/questions/9332596/pass-an-object-as-parameter-in-php
https://www.vegas-aces.com/site/articles/how-to-deal-blackjack.html
## Notes for instructor
