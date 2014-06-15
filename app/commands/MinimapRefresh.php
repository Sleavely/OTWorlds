<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MinimapRefresh extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'minimap:refresh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Repaint minimap for a given map.';

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
		//
		$map = Map::findOrFail($this->argument('map'));
		$minimap = $map->minimap;
		
		if(!$minimap->locked || $this->option('force'))
		{
			// Call dibs on editing this minimap
			$this->info('Locking and processing map '.$map->id);
			$minimap->updated_at = new \DateTime;
			$minimap->locked = true;
			$minimap->save();
			
			// Load the tiles. May take some time. =)
			$tiles = Tile::where('mapid', $map->id)->where('posz', 7)->get();
			
			// Prepare the canvas, maestro!
			\OTWorlds\MinimapPainter::$filename = $minimap->path;
			\OTWorlds\MinimapPainter::load();
			
			foreach($tiles as $tile)
			{
				\OTWorlds\MinimapPainter::paint(
          intval($tile->posx),
          intval($tile->posy),
          intval($tile->itemid)
        );
			}
			
			// Finish up
			\OTWorlds\MinimapPainter::save();
			
			// Let other processes edit this map again
			$minimap->locked = false;
			$minimap->save();
			$this->info('Done. Processed '.$tiles->count().' tiles.');
		}
		else
		{
			$this->error('Minimap is locked. Either another process is using it, or the last one crashed. Use --force to ignore.');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('map', InputArgument::REQUIRED, 'Map ID to repaint.'),
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
			array('force', 'F', InputOption::VALUE_NONE, 'Force refresh, even if there is a lock.')
		);
	}

}
