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
    '/blackjack/takeseat' => ['BlackJackController', 'takeSeats'],
    '/blackjack/choice' => ['BlackJackController', 'playHand'],
    '/casinowar' => ['CasinoWarController', 'index'],
];
