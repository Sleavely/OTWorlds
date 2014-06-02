<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permissions', function($table)
    {
      $table->increments('pid');
      $table->integer('mapid');
      $table->integer('userid');
      $table->boolean('owner')->default(false);
      $table->boolean('edit')->default(false);
      $table->boolean('view')->default(false);
      $table->timestamps();

      $table->index('mapid');
      $table->unique(array('mapid', 'userid'));
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permissions');
	}

}
