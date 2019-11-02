<?php

class Patron
{
    public $name;
    public $history;
    protected $tokens;

    public function __construct(string $name="Anonymous")
    {
        $this->name = $name;
        $this->tokens = 50;
        $this->history = array();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTokens()
    {
        return $this->tokens;
    }

    public function addTokens(int $tokens)
    {
        $this->tokens = $this->tokens + $tokens;
    }

    public function subTokens(int $tokens)
    {
        $this->tokens = $this->tokens - $tokens;
    }

    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }
}
