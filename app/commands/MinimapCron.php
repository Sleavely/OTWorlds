<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MinimapCron extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'minimap:cron';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Looking for maps to update.');
		$five_mins_ago = \Carbon\Carbon::now()->subMinutes(5);
		$formatted_date = $five_mins_ago->toDateTimeString();
		
		$minimaps = Minimap::where('locked', 0)->where('updated_at', '<', $formatted_date)->get();
		$minimaps->load('map');
		foreach($minimaps as $minimap)
		{
			// Skip if the actual map hasn't been updated since last repaint
			if($minimap->map->updated_at->lt( $minimap->updated_at )) continue;
			
			$this->info('Found minimap belonging to '.$minimap->map->name.' (id: '.$minimap->map->id.')');
			$this->info('Calling for refresh.');
			$this->call('minimap:refresh', array('map' => $minimap->map->id));
		}
		$this->info('All done!');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
