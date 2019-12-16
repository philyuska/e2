<?php

return [
    '/' => ['AppController', 'index'],
    '/about' => ['AppController', 'about'],
    '/services' => ['AppController', 'register'],
    '/register' => ['AppController', 'register'],
    '/register-new' => ['AppController', 'registerNew'],
    '/register-destroy' => ['AppController', 'registerDestroy'],
    '/patron' => ['AppController', 'patron'],
    '/game' => ['AppController', 'game'],
    '/blackjack' => ['BlackJackController', 'index'],
    '/blackjack/takeseat' => ['BlackJackController', 'seatPlayers'],
    '/blackjack/newround' => ['BlackJackController', 'newRound'],
    '/blackjack/collectWager' => ['BlackJackController', 'collectWager'],
    '/blackjack/choice' => ['BlackJackController', 'playHand'],
    '/blackjack/leavetable' => ['BlackJackController', 'leaveTable'],
    '/cnotewar' => ['CnoteWarController', 'index'],
    '/cnotewar/takeseat' => ['CnoteWarController', 'seatPlayers'],
    '/cnotewar/newround' => ['CnoteWarController', 'newRound'],
    '/cnotewar/collectWager' => ['CnoteWarController', 'collectWager'],
    '/cnotewar/leavetable' => ['CnoteWarController', 'leaveTable'],
];
