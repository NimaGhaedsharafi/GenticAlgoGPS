<?php 

class Genome
{
	public $type;
	public $length;
	public $fitness;
	public $chromo = [];

	function __construct($args = array())
	{
		$this->type 	= $args['type'];
		$this->length 	= $args['length'];
	}
	public function initialize()
	{
		$this->fitness 	= (float) $this->randFit();
		$this->chromo 	= $this->randChromo();
	}
	private function randChromo()
	{
		$chromo = [];

		for($i = 0; $i < $this->get_length(); $i++)
			array_push($chromo, array_rand($this->get_type(), 1));
	
		return $chromo;
	}
	public function get_type()
	{
		return $this->type;
	}
	public function get_length()
	{
		return $this->length;
	}
	private function randFit()
	{
		return 0/*mt_rand(0, $this->length) / $this->length*/;
	}
	public function set_fitness($num)
	{
		$this->fitness = floatval($num);
	}
}
