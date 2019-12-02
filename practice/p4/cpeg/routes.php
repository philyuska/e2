<?php

return [
    '/' => ['AppController', 'index'],
    '/about' => ['AppController', 'about'],
    '/services' => ['GuestServiceController', 'index'],
    '/register' => ['GuestServiceController', 'register'],
    '/register-save' => ['GuestServiceController', 'registerSave'],
    '/register-destroy' => ['GuestServiceController', 'registerDestroy'],
    '/playerinfo' => ['GuestServiceController', 'playerinfo'],
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
