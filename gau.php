<?php

// GENETIC ALGORITHM UTILLITY
abstract class GAU 
{
	#public abstract static function fitness(){}
	public static function crossover(Genome $mom, Genome $dad) 
	{

		$length = $dad->get_length();
		$type = $dad->get_type();

		$child1 = new Genome(['length' => $length, 'type' => $type]);
		$child2 = new Genome(['length' => $length, 'type' => $type]);

		$child1->initialize();
		$child2->initialize();

		// Crossover Borad
		$cb = floor(self::rndNum() * $length);

		for($i = 0; $i < $cb; $i++)
		{
			$child1->chromo[$i] = $mom->chromo[$i];
			$child2->chromo[$i] = $dad->chromo[$i];
		}
		for($i = $cb; $i < $length; $i++)
		{
			$child1->chromo[$i] = $dad->chromo[$i];
			$child2->chromo[$i] = $mom->chromo[$i];
		}
		return [$child1, $child2];
	}
	// Roullete Selection
	public static function selection($population)
	{
		$slice = self::rndNum() * $population->totalFitness();
		$silce = ceil($slice);

		$sum = 0;

		for($i = 0; $i < $population->size; $i++)
		{
			if($sum > $slice)
				return $population->members[$i];
			$sum += $population->members[$i]->fitness;
		}
		return $population->members[mt_rand(0, $population->size - 1)];
	}
	public static function mutation($population, $rate) 
	{
		$length = $population->length;
		$rate = $rate / 100;
		$members = $population->members;

		if($rate == 0)
			return;

		$number = floor($population->size * $rate);
		$indexs = array_rand($members, $number);

		if( sizeof($indexs) < 2)
			$indexs = [$indexs];

		foreach($indexs as $index)
		{
			for($i = 0; $i < $length; $i++)
			{
				if(self::rndNum() < $rate)
				{
					$temp = $members[$index]->chromo[$i];
					$nextI = $length - $i - 1;

					$members[$index]->chromo[$i] = $members[$index]->chromo[$nextI];
					$members[$index]->chromo[$nextI] = $members[$index]->chromo[$i];
				}
			}
		}
		
	}
	public static function elitism($newPopulation, $population, $rate)
	{
		$number = floor($population->size * ( $rate / 100 ));
		$bests = $population->best($number);
		foreach($bests as $best)
		{
			$newPopulation->add($best); 
		}
	}
	public static function rndNum()
	{
		return mt_rand() / mt_getrandmax();
	}
}
