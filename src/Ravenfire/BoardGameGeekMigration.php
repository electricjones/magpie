<?php

namespace Ravenfire\Magpie\Ravenfire;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use Ravenfire\Magpie\Data\Migrations\AbstractMigration;

class BoardGameGeekMigration extends AbstractMigration
{

    public function up()
    {
        Manager::schema()->create(static::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->integer('game_id');
            $table->foreign('game_id')->references('id')->on('games');
            $table->integer('bgg_foreign_id');
            $table->integer('number_of_players')->nullable(true);
            $table->integer('for_player_ages')->nullable(true);
            $table->integer('average_playingtime')->nullable(true);
            $table->string('boardgame_mechanic')->nullable(true);
            $table->string('thumbnail')->nullable(true);
            $table->string('image')->nullable(true);
            $table->string('boardgame_family')->nullable(true);
            $table->string('boardgame_category')->nullable(true);
            $table->string('boardgame_designer')->nullable(true);
            $table->string('boardgame_version')->nullable(true);
//            $table->string('boardgame_implementation');
//            $table->string('poll');
            $table->string('comments')->nullable(true);;
//            $table->string('statistics');
            $table->string('boardgame_rank')->nullable(true);
            $table->timestamps();
        });
    }

    static public function getTableName(): string
    {
        return "board_game_geek";
    }
}