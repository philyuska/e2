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
    '/blackjack/takeseat' => ['BlackJackController', 'takeSeat'],
    '/blackjack/leavetable' => ['BlackJackController', 'leaveTable'],
    '/blackjack/ante' => ['BlackJackController', 'ante'],
    '/blackjack/ante-collect' => ['BlackJackController', 'anteCollect'],
    '/blackjack/play' => ['BlackJackController', 'play'],
    '/blackjack/choice' => ['BlackJackController', 'playHand'],
    '/blackjack/turn' => ['BlackJackController', 'takeTurn'],
    '/blackjack/gameover' => ['BlackJackController', 'gameOver'],
    '/casinowar' => ['CasinoWarController', 'index'],
];
