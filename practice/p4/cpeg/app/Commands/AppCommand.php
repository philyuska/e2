<?php

namespace App\Commands;

class AppCommand extends Command
{
    /**
     *
     */
    public function test()
    {
        dump('It works! You invoked your first command.');
    }

    public function migrate()
    {
        $this->app->db()->createTable('patron', [
        'name' => 'varchar(255)',
        'token_balance' => 'int',
    ]);
    
        $this->app->db()->createTable('games', [
        'game' => 'varchar(12)',
        'hand_id' => 'varchar(23)',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'patron_id' => 'int',
        'seat' => 'int',
        'wager' => 'int',
        'hand_summary' => 'varchar(255)',
        'outcome' => 'varchar(45)',
        'token_win' => 'int',
        'token_loss' => 'int',
    ]);

        $this->app->db()->createTable('game', [
        'hand_id' => 'varchar(23)',
        'patron_id' => 'int',
        'turn' => 'varchar(255)',
    ]);
    
        dump('Migration complete; check the database for your new tables.');
    }
    
    public function fresh()
    {
        $this->migrate();
        $this->seed();
    }

    public function seed()
    {
    }
}
