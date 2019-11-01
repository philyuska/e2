<?php

class Patron
{
    public $name;
    public $wallet;
    public $history;

    public function __construct(string $name="Anonymous")
    {
        $this->name = $name;
        $this->wallet = 0;
        $this->history = array();
    }

    public function getName()
    {
        return $this->name;
    }

    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }
}
