<?php

namespace App\Controllers;

use App\GameObjects\Patron;

class GuestServiceController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $name = $this->app->param('name', 'Valued Guest');
        $patron = new Patron($name);
        return $this->app->view('guestservices.index', ['name' => $patron]);
    }

    public function register()
    {
        return $this->app->view('guestservices.register');
    }

    public function registerSave()
    {
        $this->app->validate([
            'name' => 'required',
        ]);
        # If the above validation fails, the user is redirected back to the product page
        # and none of the following code will execute
        
        # Extract data from the form submission
        $name = $this->app->input('name');

        $this->app->redirect('/services?name='.$name);
    }


    public function playerinfo()
    {
        return $this->app->view('guestservices.playerinfo');
    }
}
