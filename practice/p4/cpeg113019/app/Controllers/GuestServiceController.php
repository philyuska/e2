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

    public function registerSave()
    {
        $this->app->validate([
            'name' => 'required',
        ]);

        $name = $this->app->input('name');
        $redirectUrl = $this->app->input('redirect');
        $this->patron = new Patron($name);

        if ($redirectUrl) {
            $this->app->redirect($redirectUrl);
        } else {
            $this->app->redirect('/services');
        }
    }

    public function registerDestroy()
    {
        $this->patron->destroySession();
        unset($this->patron);
        $this->app->redirect('/services');
    }


    public function playerinfo()
    {
        return $this->app->view('guestservices.playerinfo', ['patron' => $this->patron]);
    }
}
