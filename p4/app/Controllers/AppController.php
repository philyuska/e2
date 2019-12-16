<?php
namespace App\Controllers;

use App\CpegObjects\Patron;

class AppController extends Controller
{
    public $patron = null;
    
    public function __construct($app)
    {
        parent::__construct($app);
        $this->patron = new Patron();
    }
        
    public function index()
    {
        $this->app->redirect('/blackjack');
    }

    public function about()
    {
        return $this->app->view('about');
    }

    public function register()
    {
        $data['previousUrl'] = $this->app->old('previousUrl');
        return $this->app->view('guestservices.register', $data);
    }

    public function registerNew()
    {
        $this->app->validate([
            'name' => 'required',
        ]);

        $name = $this->app->input('name');
        $redirectUrl = $this->app->input('redirect');
        $this->patron = new Patron($name);

        $results = $this->app->db()->findByColumn('patron', 'name', '=', $this->patron->name);
        if ($results) {
            $this->patron->id = $results[0]['id'];
            $this->patron->tokens = $results[0]['token_balance'];
        } else {
            $data = [
                'name' => $this->patron->getName(),
                'token_balance' => $this->patron->getTokens(),
            ];
            
            $this->app->db()->insert('patron', $data);
            $results = $this->app->db()->findByColumn('patron', 'name', '=', $this->patron->name);

            $this->patron->id = $this->patron->id = $results[0]['id'];
        }
        $this->patron->saveSession();

        if ($redirectUrl) {
            $this->app->redirect($redirectUrl);
        } else {
            $this->app->redirect('/patron');
        }
    }

    public function registerDestroy()
    {
        $this->patron->destroySession();
        unset($this->patron);
        $this->app->redirect('/register');
    }

    public function patron()
    {
        $sql = '
        SELECT
        p.id as id,
        p.name as name,
        p.token_balance as token_balance,
        
        count(g.patron_id) as games_played,
        count(g.token_win) as games_won,
        count(g.token_loss) as games_lost,
        sum(g.token_win) as tokens_won,
        sum(g.token_loss) as tokens_lost
       
        FROM patron p
        JOIN games as g on ( g.patron_id = p.id )
        WHERE p.id = :patron_id
        GROUP BY p.id
        ';

        $data = [
            'patron_id' => $this->patron->getId(),
        ];
        $executed = $this->app->db()->run($sql, $data);
        $patronInfo = $executed->fetch();

        $history = $this->app->db()->findByColumn('games', 'patron_id', '=', $this->patron->getId());

        return $this->app->view('guestservices.patron', [
            'patronInfo' => $patronInfo,
            'history' => $history,
            ]);
    }

    public function game()
    {
        $hand_id = $this->app->param('hand_id');

        $patronInfo = $this->app->db()->findById('patron', $this->patron->getId());
        $games = $this->app->db()->findByColumn('games', 'hand_id', '=', $hand_id);

        $sql = 'SELECT * FROM game WHERE hand_id = :hand_id AND patron_id = :patron_id';
        $data = [
            'hand_id' => $hand_id,
            'patron_id' => 0,
        ];
        $executed = $this->app->db()->run($sql, $data);
        $dealer = $executed->fetchAll();

        $sql = 'SELECT * FROM game WHERE hand_id = :hand_id AND patron_id = :patron_id';
        $data = [
            'hand_id' => $hand_id,
            'patron_id' => $this->patron->getId(),
        ];
        $executed = $this->app->db()->run($sql, $data);
        $detail = $executed->fetchAll();

        return $this->app->view('guestservices.game', [
            'patronInfo' => $patronInfo,
            'game' => $games[0],
            'dealer' => $dealer,
            'detail' => $detail,
            ]);
    }
}
