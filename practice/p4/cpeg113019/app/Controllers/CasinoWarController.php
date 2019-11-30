<?php
namespace App\Controllers;

use App\GameObjects\CasinoWar;
use App\GameObjects\Patron;
use App\GameObjects\CasinoWarPlayer;

class CasinoWarController extends Controller
{
    private $game;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->game = new CasinoWar($seats=5);
    }

    public function index()
    {
        $this->play();
        return $this->app->view('casinowar.index', ['game' => $this->game]);
    }

    public function play()
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

    public function debug()
    {
        dump($this);
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
