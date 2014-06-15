<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinimapTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create a table
    Schema::create('minimaps', function($table)
    {
      $table->increments('id');
      $table->integer('mapid');
      $table->timestamp('updated_at');
      $table->boolean('locked')->default(false);
    });
    
    // Alter map table to so we can compare updated_at later
    if(!Schema::hasColumn('maps', 'updated_at'))
    {
      Schema::table('maps', function($table)
      {
        $table->timestamps();
      });
    }
    
    // Create entries for each map
    $maps = Map::all();
    foreach($maps as $map)
    {
      $minimap = new Minimap;
      $minimap->mapid = $map->id;
      $minimap = $map->minimap()->save($minimap);
    }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
    Schema::drop('minimaps');
	}

}
