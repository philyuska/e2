<?php
namespace App\Controllers;

use JsonSerializable;
use App\GameObjects\BlackJack;
use App\GameObjects\Patron;
use App\GameObjects\BlackJackPlayer;

class BlackJackController extends Controller implements JsonSerializable
{
    private $game;
    public $patron = null;

    public function __construct($app)
    {
        parent::__construct($app);
        //$this->destroySession();
        $this->game = new BlackJack($this->gameLoadSession());
        $this->patron = new Patron();
    }

    public function index()
    {
        $this->demo();
        //return $this->app->view('blackjack.index', ['game' => $this->game, 'patron' => $this->patron]);
    }

    public function demo()
    {
        $this->game = new BlackJack();
        $names = $this->getRandomNames();

        for ($x=1; $x<=$this->game->seats; $x++) {
            $playerName = array_shift($names);
            $player = new BlackJackPlayer($playerProps=null, $patron=null, $playerName = $playerName);
            $this->game->seatThisPlayer($player);
        }

        $this->game->newRound();

        foreach ($this->game->players as $player) {
            if ($player->isPatron()) {
                $player->collectAnte();
            }
        }

        $this->game->dealHand();

        $this->game->peekHand();

        if ($this->game->continueRound()) {
            foreach ($this->game->players as $player) {
                $this->autoPlayHand($player);
            }

            $this->game->dealer->showHand();

            while ($this->game->dealer->handTotal() < 17) {
                $this->game->dealer->drawCard($this->game->deck->dealCard());
            }
            $this->game->determineOutcome();
        } elseif ($this->game->getBlackJack()) {
            $this->game->dealer->showHand();
        }

        return $this->app->view('blackjack.index', ['game' => $this->game]);
    }

    public function takeSeats()
    {
        $this->game = new BlackJack($this->gameLoadSession());
        $this->patron = new Patron();

        if (! $this->patron->isRegistered()) {
            $data['previousUrl'] = "/blackjack/takeseat";
            $this->app->redirect('/register', $data);
        }

        if ($this->game->seatsAvailable) {
            $names = $this->getRandomNames();

            $player = new BlackJackPlayer($playerProps=null, $this->patron, null);
            $this->game->seatThisPlayer($player, 1);


            // $demo = ($this->patron->getName() ? false : true);

            // if ($demo) {
            //     $playerName = array_shift($names);
            //     $player = new BlackJackPlayer($playerProps=null, $patron=null, $playerName = $playerName);
            //     $this->game->seatThisPlayer($player);
            // } else {
            //     $player = new BlackJackPlayer($playerProps=null, $this->patron, null);
            //     $this->game->seatThisPlayer($player, 1);
            // }

            for ($x=1; $x< $this->game->seats; $x++) {
                $playerName = array_shift($names);
                $player = new BlackJackPlayer($playerProps=null, $patron=null, $playerName = $playerName);
                $this->game->seatThisPlayer($player);
            }
        }

        $this->playRound();
    }

    public function playRound()
    {
        $this->game->newRound();

        foreach ($this->game->players as $player) {
            if ($player->isPatron()) {
                $player->collectAnte();
            }
        }

        $this->game->dealHand();

        $this->game->peekHand();

        if ($this->game->continueRound()) {
            foreach ($this->game->players as $player) {
                if (($player->isPatron()) && $player->hasButton()) {
                    $this->gameSaveSession();
                    return $this->app->view('blackjack.index', ['game' => $this->game]);
                } elseif ($player->hasButton()) {
                    $this->autoPlayHand($player);
                }
            }

            $this->game->dealer->showHand();

            while ($this->game->dealer->handTotal() < 17) {
                $this->game->dealer->drawCard($this->game->deck->dealCard());
            }
            $this->game->determineOutcome();
        } elseif ($this->game->getBlackJack()) {
            $this->game->dealer->showHand();
        }

        $this->gameSaveSession();

        $this->game->payoutPlayers();
        return $this->app->view('blackjack.index', ['game' => $this->game]);
    }

    public function continuePlay()
    {
        foreach ($this->game->players as $player) {
            if ($player->hasButton()) {
                $this->autoPlayHand($player);
            }
        }

        $this->game->dealer->showHand();

        while ($this->game->dealer->handTotal() < 17) {
            $this->game->dealer->drawCard($this->game->deck->dealCard());
        }

        $this->game->determineOutcome();

        $this->game->payoutPlayers();

        $this->destroySession();
        return $this->app->view('blackjack.play', ['game' => $this->game]);
    }

    public function playHand()
    {
        $choice = $this->app->input('choice');
        $seat = $this->app->input('seat');

        $player = $this->game->players[$seat];

        if ($choice == 'hit') {
            $player->drawCard($this->game->deck->dealCard());
            $this->gameSaveSession();
            return $this->app->view('blackjack.play', ['game' => $this->game]);
        } else {
            $this->game->passButton($player->seat);
            $this->continuePlay();
        }
    }

    private function autoPlayHand(BlackJackPlayer $player)
    {
        while (($player->handTotal() < 21) && ($this->game->shouldHit($player))) {
            $player->drawCard($this->game->deck->dealCard());
        }
        $this->game->passButton($player->seat);
    }

    public function debug()
    {
        dump($this);
    }

    public function jsonSerialize()
    {
        return [
            'deck' => $this->game->deck,
            'dealer' => $this->game->dealer,
            'players' => $this->game->players,
        ];
    }

    public function gameSaveSession()
    {
        $gameSession = json_encode($this->game);
        $this->setSession('cpeg_game', $gameSession);
    }

    public function gameLoadSession()
    {
        if (isset($_SESSION['cpeg_game'])) {
            $gameSession = json_decode($this->getSession('cpeg_game'), $assoc=true);
            return $gameSession;
        }

        return null;
    }

    public function destroySession()
    {
        $this->unsetSession('cpeg_game');
    }

    /**
     * Set a session value
     */
    private function setSession($key, $value)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     */
    private function getSession($key, $default = null)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return $_SESSION[$key] ?? $default;
    }

    /**
     * Destroy a session value
     */
    private function unsetSession($key)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    private function getRandomNames()
    {
        $randomNames = array(

                'Fastfingers',
                'Smuggie',
                'Fat Tony',
                'Snake Eyes',
                'The Godfather',
                'Snap',
                'Guttermouth',
                'Stab Happy',
                'Headlock',
                'T-Bone',
                'Ice',
                'Vito',
                'Ice Box',
                'Wheels',
                'Angel Face',
                'Magnolia',
                'Baby',
                'Mama',
                'Baby Blue',
                'Margarita',
                'Bambi',
                'Miss Demeanor',
                'Bandit',
                'Missy',
                'Banker',
                'The Monalisa',
                'Bonnie',
                'Nails',
                'Brooklyn',
                'Pearl',

                );

        shuffle($randomNames);
        return $randomNames;
    }
}
