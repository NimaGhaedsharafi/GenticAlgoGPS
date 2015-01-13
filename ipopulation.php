<?php 

abstract class iPopulation
{
	public $members = [];
	public $size;
	public $length;
	public $totalFitness = 0;
	public $cmp_function;

	public function makePopulation(){}
	public function add($genome){}
	public function sort(){}
	public function delete($index, $flag = true){}
	public function best($num = 0){}
	public function worst($num = 0){}
	public function clear(){}
}