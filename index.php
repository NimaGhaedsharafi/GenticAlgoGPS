<?php

$text = "Nima";
	
$size = 100;
$text = strtolower($text);
$type = array_combine(range(ord('a'), ord('z')), range('a', 'z'));

$cmp_func = function ($word1, $word2)
{
	if($word1->fitness > $word2->fitness)
		return 1;
	else if($word1->fitness < $word2->fitness )
		return -1;
	else
		return 0;
};


$population = new Population($size, strlen($text), $type, $cmp_func);
$newPopulation = new Population($size, strlen($text), $type, $cmp_func);
$population->makePopulation();

$ga = new GeneticAlgorithm([
		'justFind' => true,
		'population_size' => $size,
		'population' => $population,
		'newPopulation' => $newPopulation,
		'generation' => 60,
		'mutation_rate' => 10,
		'elitism_rate' => 25,
		'debug' => true,
		'length' => $population->length,
		'fitness_function'  =>  function($built) use( $text ) { return Letters::fitness($built, $text); },
		'mutation_function'  => function($population, $rate) { return Letters::mutation($population, $rate); },
		'selection_function' => function($population) { return Letters::selection($population); },
		'crossover_function' => function($mom, $dad){ return Letters::crossover($mom, $dad); },
		'elitism_function' 	 => function($newPopulation, $population, $rate){ return Letters::elitism($newPopulation, $population, $rate); },
	]);

$ga->circo();
echo $ga;

?>