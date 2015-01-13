<?php 

class Letters extends GAU
{
	public static function fitness($built, $desired)
	{
		$rate = 0;
		$length = strlen($desired);

		for($i = 0; $i < $length; $i++)
		{
			$original_letter = substr($desired, $i, 1);

			$cmp = strcmp($original_letter, chr($built->chromo[$i]));
			if($cmp == 0 )
				$rate += 1;
		}
		
		return (float) ($rate / $length);
	}
	static function buildword ($array)
	{
		$name = '';
		for($i = 0; $i < sizeof($array); $i++)
			$name .= chr( $array[$i]);
		return $name;
	}
}

