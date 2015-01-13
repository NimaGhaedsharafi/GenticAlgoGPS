<?php 

class Population extends iPopulation
{
	function __construct($size = 20, $length, $type, $cmp_function = null)
	{
		$this->size = $size;
		$this->length = $length;
		$this->type = $type;
		$this->cmp_function = $cmp_function;
	}
	
	public function makePopulation()
	{
		$length = $this->length;
		$type = $this->type;
		
		for($i = 0; $i < $this->size; $i++)
		{
			$this->add(new Genome([ 'length' => $length, 'type' => $type ]));
			$this->members[$i]->initialize();
		}
		$this->calcTotal();
	}
	public function add($genome)
	{
		$this->members[] = $genome;
	}
	public function sort()
	{
		usort($this->members, $this->cmp_function);
	}
	public function delete($index, $flag = true)
	{
		unset($this->members[$index]);
		if( $flag )
			array_values($this->members);
	}
	public function reset()
	{
		$this->totalFitness = 0;
	}
	public function calcTotal()
	{
		$sum = 0;

		for($i = 0; $i < $this->size; $i++)
			$sum += $this->members[$i]->fitness;

		$this->totalFitness = floatval($sum);
	}
	public function totalFitness()
	{
		return $this->totalFitness;
	}
	public function best($num = 1)
	{
		$this->sort();
		return array_slice($this->members, 0, $num);
	}
	public function worst($num = 0)
	{
		$this->sort();
		return array_slice($this->members, $size - $num, $size);
	}
	public function clear()
	{
		$this->reset();
		for($i = 0; $i < $this->size; $i++)
			$this->delete($i);
		$this->members = [];
	}
}
