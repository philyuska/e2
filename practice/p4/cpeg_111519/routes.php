<?php

return [
    '/' => ['AppController', 'index'],
    '/about' => ['AppController', 'about'],
    '/services' => ['GuestServiceController', 'index'],
    '/register' => ['GuestServiceController', 'register'],
    '/register-save' => ['GuestServiceController', 'registerSave'],
    '/playerinfo' => ['GuestServiceController', 'playerinfo'],
    '/blackjack' => ['BlackJackController', 'index'],
    '/blackjack-action' => ['BlackJackController', 'playHand'],
    '/casinowar' => ['CasinoWarController', 'index'],
];
