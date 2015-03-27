<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ParseCsv extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'parse:csv';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	private $categories;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->categories = Category::all();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		array_map([$this, 'parseCsv'], file(app_path() .'/commands/list.csv'));
	}

	private function parseCsv($data) {
		$row = explode(';', $data);
		$category = $this->checkCategory($row[2]);
		$this->info('Creating ' . $row[0] . ' with category: '.$category->id);
		WordCard::create(['word' => $row[0], 'answer' => $row[1], 'category_id' => $category->id]);
	}

	private function determineCategory($str) {
		$category = $this->checkCategory($str);

		if ($category->count() == 0) {
			$this->info("Creating new category '{$str}'");
			return Category::create(['name' => $str, 'subscription_price' => 5, 'test_price' => 100]);
		} else {
			var_dump($category->first()->id);
			return $category->first();
		}

	}

	private function checkCategory($str) {
//		return Category::where('name', $str)->first();
		return $this->categories->filter(function ($category) use ($str) {
			return $category->name == $str;
		})->values()->first();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		//TODO arument file
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		//TODO option optional delimeter
		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
