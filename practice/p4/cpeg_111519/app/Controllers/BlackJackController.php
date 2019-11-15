<?php
namespace App\Controllers;

use JsonSerializable;
use App\GameObjects\BlackJack;
use App\GameObjects\Patron;
use App\GameObjects\BlackJackPlayer;

class BlackJackController extends Controller implements JsonSerializable
{
    private $game;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->game = new BlackJack();
        $this->gameLoadSession();
    }

    public function index()
    {
        $this->play();
        return $this->app->view('blackjack.index', ['game' => $this->game]);
    }

    public function play()
    {
        $demo = ($this->app->param('play') ? false : true);

        $names = $this->getRandomNames();
        shuffle($names);

        if ($demo) {
            $playerName = array_shift($names);
            $player = new BlackJackPlayer($patron=null, $playerName = $playerName);
            $this->game->seatThisPlayer($player);
        } else {
            $patron = new Patron("Valued Guest");
            $player = new BlackJackPlayer($patron);
            $this->game->seatThisPlayer($player);
        }
        for ($x=1; $x<= $this->game->seats -1; $x++) {
            $playerName = array_shift($names);
            $players[$x] = new BlackJackPlayer($patron=null, $playerName = $playerName);
            $this->game->seatThisPlayer($players[$x]);
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
                if (($player->isPatron()) && $player->hasButton()) {
                    $this->gameSaveSession();
                    
                    return $this->app->view('blackjack.index', ['game' => $this->game]);
                } else {
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
    }

    public function playHand()
    {
        $choice = $this->app->input('choice');
        $seat = $this->app->input('seat');

        $player = $this->game->players[$seat];

        dump($seat);
        dump($choice);
        dd($this->game);

        if ($choice == 'hit') {
            $player->drawCard($this->game->deck->dealCard());
            $this->app->redirect('/blackjack.index?play=true');
        } else {
            $this->game->passButton($player->seat);
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
            'deck' => $this->deck,
            'dealer' => $this->dealer,
            'players' => $this->players,
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
            $gameSession = json_decode($this->getSession('cpeg_game'));
        }
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
