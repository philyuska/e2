<?php
namespace App\Controllers;

use JsonSerializable;
use App\CpegObjects\CnoteWar;
use App\CpegObjects\Patron;
use App\CpegObjects\CnoteWarPlayer;

class CnoteWarController extends Controller implements JsonSerializable
{
    private $game;
    public $patron = null;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->game = new CnoteWar($this->loadGameSession());
        $this->patron = new Patron();
    }

    public function index()
    {
        $action = $this->app->param('action');

        if (! $action) {
            $this->demo();
            return $this->app->view('cnotewar.index', ['game' => $this->game, 'scene'=> $action ]);
        }

        if ($action == 'takeseat') {
            $this->seatPlayers();
        }

        if ($action == 'turn') {
            return $this->app->view('cnotewar.index', ['game' => $this->game, 'scene' => $action ]);
        }

        if ($action == 'newhand') {
            return $this->app->view('cnotewar.index', ['game' => $this->game, 'scene' => $action ]);
        }

        $this->app->redirect('/cnotewar');
    }

    public function demo()
    {
        $this->destroyGameSession();
        $this->game = new CnoteWar();
        $names = $this->getRandomNames();

        for ($x=1; $x<= $this->game->seats; $x++) {
            $playerName = array_shift($names);
            $players[$x] = new CnoteWarPlayer($playerProps=null, $patron=null, $playerName = $playerName);
            $this->game->seatThisPlayer($players[$x]);
        }

        $this->game->newRound();

        $this->game->dealHand();

        $this->game->determineOutcome();
        
        $this->game->gotoWar();
    }

    public function seatPlayers()
    {
        if ($this->patron->isRegistered()) {
            if ($this->game->getSeatsAvailable()) {
                $names = $this->getRandomNames();

                $player = new CnoteWarPlayer($playerProps=null, $this->patron, $playerName=null);
                $this->game->seatThisPlayer($player, $seat=1);

                for ($x=1; $x< $this->game->seats; $x++) {
                    $playerName = array_shift($names);
                    $player = new CnoteWarPlayer($playerProps=null, $patron=null, $playerName = $playerName);
                    $this->game->seatThisPlayer($player);
                }
            }

            $this->saveGameSession();
            return $this->app->redirect("/cnotewar/newround");
        } else {
            $data['previousUrl'] = "/cnotewar/takeseat";
            $this->app->redirect('/register', $data);
        }
    }

    public function newRound()
    {
        return $this->app->view('cnotewar.newround', ['game' => $this->game ]);
    }

    public function leaveTable()
    {
        $this->destroyGameSession();
        $this->app->redirect('/cnotewar');
    }

    public function collectWager()
    {
        $this->app->validate([
            'wager' => 'required|min:1|max:50',
        ]);

        $wager = $this->app->input('wager');
        $this->game->players[$this->app->input('seat')]->collectWager($tokens=$wager);

        $this->playRound();
    }

    public function playRound()
    {
        $this->game->newRound();

        $this->game->dealHand();

        $this->game->determineOutcome();
        $this->game->gotoWar();

        $this->game->payoutPlayers();
        $this->game->endRound();
        $this->flushHandHistory();
        $this->saveGameSession();

        return $this->app->redirect('/cnotewar/?action=newhand');
    }

    private function flushHandHistory()
    {
        foreach ($this->game->dealer->handHistory['turn'] as $turn) {
            $gameRec = array();
            $gameRec['hand_id'] = $this->game->dealer->handHistory['handId'];
            $gameRec['patron_id'] = 0;
            $gameRec['turn'] = $turn;

            $this->app->db()->insert('game', $gameRec);
        }


        foreach ($this->game->players as $player) {
            if ($player->isPatron()) {
                $sql = 'UPDATE patron SET token_balance = :token_balance WHERE id = :id';
                $data = [
                    'token_balance' =>  $player->patron->getTokens(),
                    'id' => $player->patron->getId()
                ];
                $executed = $this->app->db()->run($sql, $data);

                $this->app->db()->insert('games', $player->patron->gamesRec);

                foreach ($player->patron->gameRecs as $gameRec) {
                    $this->app->db()->insert('game', $gameRec);
                }
            }
        }


        // foreach ($this->game->players as $player) {
        //     if ($player->isPatron()) {
        //                  $this->app->db()->insert('gamehistory', $player->patron->history);
        //     }
        // }
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
