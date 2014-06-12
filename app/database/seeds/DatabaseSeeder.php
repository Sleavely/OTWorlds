<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('MapSeeder');
		$this->command->info('Maps table seeded!');
	}

}

class MapSeeder extends Seeder {
	public function run()
	{
		Map::create(array(
			'name' => 'Testmap',
			'description' => 'This is a test map from the MapSeeder class',
			'width' => 1024,
			'height' => 1024,
			'version' => 960
		));
	}
}