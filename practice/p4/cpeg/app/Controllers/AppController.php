<?php
namespace App\Controllers;

class AppController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $welcomes = ['Welcome', 'Aloha!', 'Welkom', 'Bienvenidos', 'Bienvenu', 'Welkomma'];
        
        return $this->app->view('index', [
            'welcome' => $welcomes[array_rand($welcomes)]
        ]);
    }

    public function services()
    {
        return $this->app->view('services.index');
    }

    public function register()
    {
        return $this->app->view('services.register');
    }

    public function playerinfo()
    {
        return $this->app->view('services.playerinfo');
    }

    public function about()
    {
        return $this->app->view('about');
    }
}
