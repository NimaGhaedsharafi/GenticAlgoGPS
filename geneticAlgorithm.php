<?php 

Class GeneticAlgorithm
{
	private $population;
	private $generation = 10;
	private $newPopulation;
	private $debug = false;
	private $justFind = false;

	private $population_size = 20;
	private $length = 0;

	private $mutation_rate = 10;
	private $elitism_rate = 10;
	
	private $fitness_function = null;
	private $mutation_function = null;
	private $selection_function = null;
	private $crossover_function = null;
	private $elitism_function = null;
	
	private $total_generation = 0;

	function __construct(array $initialize = array())
	{
		$this->initialize($initialize);
	}
	public function initialize(array $initialize)
	{
		foreach ($initialize as $key => $value)
		{
			if( in_array($key, get_class_vars(get_class($this))) )
			{
				$this->$key = $value;
			}
		}
	}
	public function circo()
	{
		while( $this->total_generation < $this->generation)
		{
			$this->SteadyNewPopulation();
			$this->total_generation = $this->total_generation + 1;
			$this->execute();
			if($this->justFind and $this->findResult())
				break;
			if($this->debug)
				echo $this->status();
		}
	}
	private function execute()
	{
		return $this
					->fitness()
					->crossover()
					->mutate()
					->elitism()
					->save()
				;
	}
	private function get_selection_function()
	{
		return $this->selection_function;
	}
	private function get_mutation_function()
	{
		return $this->mutation_function;
	}	
	private function get_crossover_function()
	{
		return $this->crossover_function;
	}	
	private function get_fitness_function()
	{
		return $this->fitness_function;
	}
	private function get_elitism_function()
	{
		return $this->elitism_function;
	}	
	private function crossover()
	{
		while(sizeof($this->newPopulation->members) < $this->normalDNANumber())
		{
			$selection = $this->get_selection_function();
			$crossover = $this->get_crossover_function();

			$mom = $selection($this->population);
			$dad = $selection($this->population);

			$childs = $crossover($mom, $dad);

			$this->newPopulation->add($childs[0]);
			$this->newPopulation->add($childs[1]);
		}
		return $this;
	}
	private function mutate()
	{
		$mutation = $this->get_mutation_function();
		$mutation($this->population, $this->mutation_rate);

		return $this;
	}
	private function fitness()
	{
		$fitnessFunc = $this->get_fitness_function();

		for($i = 0; $i < $this->population_size; $i++)
		{
			$this->population->members[$i]->set_fitness(0.0);
			$this->population->members[$i]->set_fitness($fitnessFunc($this->population->members[$i]));
		}

		$this->population->calcTotal();

		return $this;
	}
	private function elitism()
	{
		$elitism = $this->get_elitism_function();

		$elitism($this->newPopulation, $this->population, $this->elitism_rate);

		return $this;
	}
	private function save()
	{
		for($i = 0; $i < $this->normalDNANumber(); $i++)
			$this->population->members[$i] = clone $this->newPopulation->members[$i];
	}
	private function normalDNANumber()
	{
		return $this->population_size - floor($this->population_size * ( $this->elitism_rate / 100 )) - floor($this->population_size * ( $this->mutation_rate / 100));
	}
	private function SteadyNewPopulation()
	{
		$this->newPopulation->clear();
	}
	private function findResult()
	{
		$best = $this->population->best()[0];
		$fitnessFunc = $this->get_fitness_function();

		return $fitnessFunc($best) == 1;
	}
	private function name($array)
	{
		$name = '';
		for($i = 0; $i < sizeof($array); $i++)
			$name .= chr( $array[$i]);
		return $name;
	}
	public function __toString()
	{
		$this->population->sort();
		$this->fitness();
		$string = '';
		$string .= $this->status();
		$string .= '<br /> Generation: #' . $this->total_generation ;
		$string .= '<br /> Max number of Generation: ' . $this->generation;
		$string .= '<br /> Population Size : ' . $this->population_size ;
		$string .= '<br /> Mutaion Rate : ' . $this->mutation_rate . '%';
		$string .= '<br /> Elitism Rate : ' . $this->elitism_rate . '%';
		return $string;
	}
	private function showFitness()
	{
		$string = '';
		$members = $this->population->members;
		$count = 1;

		foreach($members as $member)
		{
			$name = $this->name($member->chromo);
			$string .= '<tr>';
			$string .= '<td>' . $count .'</td><td>' . $name . '</td><td>';
			$string .= floatval($member->fitness) * 100 . '%</td>' ;
			$string .= '</tr>';
			$count += 1;
		}
		return $string;
	}
	private function status()
	{
		return '<table><thead><td>#</td><td>Word</td><td>Similarity</td></thead>' .	$this->showFitness() . '</table>';
	}
}