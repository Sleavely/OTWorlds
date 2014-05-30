<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('maps', function($table)
    {
      $table->increments('id');
      $table->string('name', 20);
      $table->string('description', 120);
      $table->integer('width');
      $table->integer('height');
      $table->integer('version');
    });
    
    Schema::create('tiles', function($table)
    {
      $table->integer('mapid');
      $table->bigIncrements('tileid');
      $table->integer('posx');
      $table->integer('posy');
      $table->integer('posz');
      $table->integer('itemid')->nullable();
      $table->integer('aid')->default(0);
      $table->integer('uid')->default(0);
      $table->integer('houseid')->default(0);
      $table->integer('protectionzone')->default(0);
      $table->integer('nologout')->default(0);
      $table->integer('pvp')->default(1);

      $table->index('mapid');
      $table->unique(array('mapid', 'posx', 'posy', 'posz'));
      //$table->foreign('mapid')->references('id')->on('maps');
    });
    
    Schema::create('items', function($table)
    {
      $table->integer('tileid');
      $table->bigIncrements('propid');
      $table->integer('itemid');
      $table->integer('stackorder')->default(0);
      $table->integer('aid')->default(0);
      $table->integer('uid')->default(0);
      $table->integer('count')->default(1);
      $table->text('text');
      $table->integer('charges')->default(0);
      $table->integer('depotid')->default(0);
      $table->integer('housedoorid')->default(0);

      $table->index('tileid');
      //$table->foreign('tileid')->references('tileid')->on('tiles');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
    //Drop in reverse order to avoid issues with foreign keys
		Schema::drop('items');
    Schema::drop('tiles');
    Schema::drop('maps');
	}

}
