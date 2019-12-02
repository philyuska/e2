<?php
namespace App\Controllers;

use JsonSerializable;
use App\GameObjects\CasinoWar;
use App\GameObjects\Patron;
use App\GameObjects\CasinoWarPlayer;

class CasinoWarController extends Controller implements JsonSerializable
{
    private $game;
    public $patron = null;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->game = new CasinoWar($this->loadGameSession());
        $this->patron = new Patron();
    }

    public function index()
    {
        $action = $this->app->param('action');

        if (! $action) {
            $this->demo();
            return $this->app->view('casinowar.index', ['game' => $this->game, 'scene'=> $action ]);
        }

        if ($action == 'takeseat') {
            $this->seatPlayers();
        }

        if ($action == 'turn') {
            return $this->app->view('casinowar.index', ['game' => $this->game, 'scene' => $action ]);
        }

        if ($action == 'newhand') {
            return $this->app->view('casinowar.index', ['game' => $this->game, 'scene' => $action ]);
        }

        $this->app->redirect('/casinowar');
    }

    public function demo()
    {
        $this->destroyGameSession();
        $this->game = new CasinoWar();
        $names = $this->getRandomNames();

        for ($x=1; $x<= $this->game->seats; $x++) {
            $playerName = array_shift($names);
            $players[$x] = new CasinoWarPlayer($playerProps=null, $patron=null, $playerName = $playerName);
            $this->game->seatThisPlayer($players[$x]);
        }

        $this->game->newRound();

        $this->game->dealHand();

        if ($this->game->continueRound()) {
            foreach ($this->game->players as $player) {
                if ($player->hasButton()) {
                    while (($player->handTotal() <> $this->game->dealer->handTotal())) {
                        $player->drawCard($this->game->deck->dealCard());
                    }
                    $this->game->passButton($player->seat);
                }
            }
        }
    }

    public function seatPlayers()
    {
        if ($this->patron->isRegistered()) {
            if ($this->game->getSeatsAvailable()) {
                $names = $this->getRandomNames();

                $player = new CasinoWarPlayer($playerProps=null, $this->patron, $playerName=null);
                $this->game->seatThisPlayer($player);

                for ($x=1; $x< $this->game->seats; $x++) {
                    $playerName = array_shift($names);
                    $player = new CasinoWarPlayer($playerProps=null, $patron=null, $playerName = $playerName);
                    $this->game->seatThisPlayer($player);
                }
            }

            $this->saveGameSession();
            return $this->app->view('casinowar.index', ['game' => $this->game, 'scene' => 'newhand' ]);
        } else {
            $data['previousUrl'] = "/casinowar/?action=takeseat";
            $this->app->redirect('/register', $data);
        }
    }

    public function leaveTable()
    {
        $this->destroyGameSession();
        $this->app->redirect('/casinowar');
    }

    public function ante()
    {
        $this->app->validate([
            'ante' => 'required|min:1|max:50',
        ]);

        $ante = $this->app->input('ante');
        $this->game->players[$this->app->input('seat')]->collectAnte($tokens=$ante);

        $this->playRound();
    }

    public function playRound()
    {
        $demo = ($this->app->param('play') ? false : true);

        $names = $this->getRandomNames();
        shuffle($names);

        if ($demo) {
            $playerName = array_shift($names);
            $player = new CasinoWarPlayer($patron=null, $playerName = $playerName);
            $this->game->seatThisPlayer($player);
        } else {
            $patron = new Patron("Valued Guest");
            $player = new CasinoWarPlayer($patron);
            $this->game->seatThisPlayer($player);
        }
        for ($x=1; $x<= $this->game->seats -1; $x++) {
            $playerName = array_shift($names);
            $players[$x] = new CasinoWarPlayer($patron=null, $playerName = $playerName);
            $this->game->seatThisPlayer($players[$x]);
        }

        $this->game->newRound();

        foreach ($this->game->players as $player) {
            if ($player->isPatron()) {
                $player->collectAnte();
            }
        }

        $this->game->dealHand();

        //$this->game->peekHand();

        if ($this->game->continueRound()) {
            foreach ($this->game->players as $player) {
                if ($player->hasButton()) {
                    while (($player->handTotal() <> $this->game->dealer->handTotal())) {
                        $player->drawCard($this->game->deck->dealCard());
                    }
                    $this->game->passButton($player->seat);
                }
            }
        }
        
        $this->game->payoutPlayers();
    }

    public function jsonSerialize()
    {
        return [
            'deck' => $this->game->deck,
            'dealer' => $this->game->dealer,
            'players' => $this->game->players,
        ];
    }

    public function saveGameSession()
    {
        $gameSession = json_encode($this->game);
        $this->app->sessionSet('cpeg_game', $gameSession);
    }

    public function loadGameSession()
    {
        if ($this->app->sessionGet('cpeg_game')) {
            $gameSession = json_decode($this->app->sessionGet('cpeg_game'), $assoc=true);
            return $gameSession;
        }

        return null;
    }

    public function destroyGameSession()
    {
        $this->unsetSession('cpeg_game');
    }

    /**
     * Destroy a session key
     */
    public function unsetSession($key)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function getRandomNames()
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
