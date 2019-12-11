<?php
namespace App\Controllers;

use App\GameObjects\Patron;

class GuestServiceController extends Controller
{
    public $patron = null;
    
    public function __construct($app)
    {
        parent::__construct($app);
        $this->patron = new Patron();
    }
        
    public function index()
    {
        return $this->app->view('guestservices.index', ['patron' => $this->patron]);
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
        $this->app->redirect('/services');
    }


    public function patron()
    {
        $patronInfo = $this->app->db()->findById('patron', $this->patron->getId());
        $history = $this->app->db()->findByColumn('games', 'player_id', '=', $this->patron->getId());

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

        $sql = 'SELECT * FROM game WHERE hand_id = :hand_id AND player_id = :player_id';
        $data = [
            'hand_id' => $hand_id,
            'player_id' => 0,
        ];
        $executed = $this->app->db()->run($sql, $data);
        $dealer = $executed->fetchAll();

        //dd($dealer);

        $sql = 'SELECT * FROM game WHERE hand_id = :hand_id AND player_id = :player_id';
        $data = [
            'hand_id' => $hand_id,
            'player_id' => $this->patron->getId(),
        ];
        $executed = $this->app->db()->run($sql, $data);
        $detail = $executed->fetchAll();

        //$detail = $this->app->db()->findByColumn('game', 'hand_id', '=', $hand_id);

        return $this->app->view('guestservices.game', [
            'patronInfo' => $patronInfo,
            'game' => $games[0],
            'dealer' => $dealer,
            'detail' => $detail,
            ]);

        // # SQL statement with named parameters
        // $sql = 'SELECT name FROM products WHERE available < :available AND perishable = :perishable';

        // $data = [
        //     'available' => 10,
        //     'perishable' => 1
        // ];

        // $executed = $this->app->db()->run($sql, $data);

        // # A PDO method such as fetch, fetchAll, fetchColumn, fetchObject, etc. should be used to extract the results
        // dump($executed->fetchAll());
    }
}
