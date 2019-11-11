<?php
namespace App\Controllers;

use App\GameObjects\BlackJack;
use App\GameObjects\Patron;
use App\GameObjects\BlackJackPlayer;

class BlackJackController extends Controller
{
    private $game;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->game = new BlackJack($seats=5);
    }

    public function index()
    {
        $this->play($demo=true);
        //return $this->app->view('blackjack.index');

        return $this->app->view('blackjack.index', [
'game' => $this->game,

]);
    }

    public function play(bool $demo=false)
    {
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

        while ($this->game->getCurrentRound() < 1) {
            $this->game->newRound();

            foreach ($this->game->players as $player) {
                if ($player->isPatron()) {
                    $player->collectAnte();
                    print " round " . $this->game->getCurrentRound() . " Ante : " . $player->getName() . " tokens " .
        $player->getTokens() . " remaining\n";
                }
            }

            $this->game->dealHand();

            $this->game->peekHand();

            if ($this->game->continueRound()) {
                foreach ($this->game->players as $player) {
                    if ($player->hasButton()) {
                        while (($player->handTotal() < 21) && ($this->game->shouldHit($player))) {
                            $player->drawCard($this->game->deck->dealCard());
                        }
                        $this->game->passButton($player->seat);
                    }
                }

                $this->game->dealer->showHand();

                while ($this->game->dealer->handTotal() < 17) {
                    $this->
                game->dealer->drawCard($this->game->deck->dealCard());
                }
                $this->game->determineOutcome();
            } elseif ($this->game->getBlackJack()) {
                $this->game->dealer->showHand();
            }

            $this->game->payoutPlayers();
        }

        // return $this->app->view('blackjack.results', [
                // 'players' => $this->game
                // ]);

                // return $this->app->view('products.show', [
                // 'product' => $product
                // ]);
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
