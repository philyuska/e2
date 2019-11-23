<?php
namespace App\GameObjects;

use JsonSerializable;

class Patron implements JsonSerializable
{
    public $name;
    public $history;
    public $tokens;

    public function __construct(string $name=null)
    {
        $patronProps = $this->loadSession();

        if ($patronProps) {
            $this->name = $patronProps['name'];
            $this->tokens = $patronProps['tokens'];
            $this->history = $patronProps['history'];
        } elseif ($name) {
            $this->name = $name;
            $this->tokens = 50;
            $this->history = array();

            $this->saveSession();
        }
    }

    public function getName()
    {
        return ($this->name ? $this->name : "");
    }

    public function isRegistered()
    {
        return ($this->name ? true : false);
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

    public function setHistory(string $entry)
    {
        $this->history[] = $entry;
    }

    public function saveSession()
    {
        $patronProps = json_encode($this);
        $this->setSession('cpeg_patron', $patronProps);
    }

    public function loadSession()
    {
        if ($this->getSession('cpeg_patron')) {
            $patronProps = json_decode($this->getSession('cpeg_patron'), $assoc=true);
            return $patronProps;
        }

        return null;
    }

    public function destroySession()
    {
        $this->unsetSession('cpeg_patron');
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'tokens' => $this->tokens,
            'history' => $this->history,
        ];
    }

    /**
     * Set a session value
     */
    private function setSession($key, $value)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     */
    private function getSession($key, $default = null)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return $_SESSION[$key] ?? $default;
    }

    /**
     * Destroy a session value
     */
    private function unsetSession($key)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function debug()
    {
        print "<pre>";
        print_r($this);
        print "</pre>";
    }
}
