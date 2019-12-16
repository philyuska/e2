# Project 4
+ By: Phil Yuska
+ Production URL: <http://p4.tymabus.fun>

## Game planning
* Play multiple rounds of Black Jack or Cnote War 
* Build upon project 3 using classes methods instead of traditional arrays and functions
* Record the game results in a database tables `games` and `game` for viewing
* Games will be modeled after traditional casino table games
* Decks of cards will be combined into a single shoe object `ShoeOfCards`
* All end user input shold be validated using app->validate
* Games should display a demo screen when there is no seated patron 
* Players must register their name in order to play. A `Patron` onject will be bound to the games player object to distiguish them
* An initial allotment of tokens will be awarded to patrons upon registration
* Unlimited credit will be extended ( negative token balance ) 
* The patrons name and token balance will be recorded in the database table `patron`
* Games will award patrons winning hands additional tokens and subtract the players initial wager from the players token balance
* Games will record a summary record of a patrons play in the `games` table and a detail record of the hand in the `game` table

* The `ShoeOfCards` object will contain a compound array of multiple decks of 208 playing cards and the properties of each playing card 
	* name = plain english name and suit of card ( concatenation of card name and suit )
	* value = pip value
	* suit = spades, hearts, diamonds or clubs
	* rank = card rank Ace to King 1-13
	* glyph = Unicode character representing an image of the playing card
    * emoji = A shortened version of the name e.g. Ace = A and the Unicode character representing the card suit

* Games will assign the `patron` to seat 1 to make the UI less confusing and fill in the remaining seats with random house players and redirect to newround

* Game initialization
    * newRound will generate a unique handId to use as a key in the `games and game` database tables
        * verify there are enough cards remaining in the shoe of cards and if not substantiate a new one
        * reinitialize all of the game and player properties to their default values
    *  collectWager will collect wager imput by the patron and trigger game play via playRound

* Game play, each game has it's own unique logic 
    * Black Jack is based on the card game 21, with a yahPoo bonus triggered if the dealers hole card matches the `poopie` emoji
    * Cnote War is based on the card game Casino War, 100 tokens are awarded if the results of War are a tie

* Game completion
    * tokens will be awarded or subtracted via `payoutPlayers`
    * the round will be marked as complete via `endRound`
    * then results of the game flushed to the database tables vie `flushHandHistory`

## Outside resources
https://www.php.net/manual/en/
https://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
https://secure.php.net/manual/en/function.array-multisort.php
https://en.wikipedia.org/wiki/Standard_52-card_deck
https://en.wikipedia.org/wiki/Playing_cards_in_Unicode
https://bicyclecards.com/how-to-play/blackjack/
https://wizardofodds.com/games/blackjack/strategy/4-decks/
https://www.w3schools.com/howto/howto_css_cards.asp
https://wizardofodds.com/games/casino-war/
https://www.sitepoint.com/use-jsonserializable-interface/

## Notes for instructor
