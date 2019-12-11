<?php

return [
    #'/' => ['AppController', 'index'],
    '/' => ['BlackJackController', 'index'],
    '/about' => ['AppController', 'about'],
    #'/services' => ['GuestServiceController', 'index'],
    '/services' => ['GuestServiceController', 'register'],
    '/register' => ['GuestServiceController', 'register'],
    '/register-new' => ['GuestServiceController', 'registerNew'],
    '/register-destroy' => ['GuestServiceController', 'registerDestroy'],
    '/patron' => ['GuestServiceController', 'patron'],
    '/game' => ['GuestServiceController', 'game'],
    '/blackjack' => ['BlackJackController', 'index'],
    '/blackjack/takeseat' => ['BlackJackController', 'seatPlayers'],
    '/blackjack/leavetable' => ['BlackJackController', 'leaveTable'],
    '/blackjack/ante' => ['BlackJackController', 'ante'],
    '/blackjack/choice' => ['BlackJackController', 'playHand'],
    '/casinowar' => ['CasinoWarController', 'index'],
    '/casinowar/takeseat' => ['CasinoWarController', 'seatPlayers'],
    '/casinowar/leavetable' => ['CasinoWarController', 'leaveTable'],
    '/casinowar/ante' => ['CasinoWarController', 'ante'],
    '/casinowar/choice' => ['CasinoWarController', 'playHand'],
];
