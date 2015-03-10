<?php

	namespace \NumberToString;


	class NumberToString
	{
		public function getString($number = 10, $money = "euros", $centimes = "centimes") {
			return $this->init($number, $money, $centimes);
		}



		private function init($number, $money, $centimes) {
			$string = '';
			$string_number = '';
			$string_float = '';


			// parameters float
			$float = null;

			if (strpos($number, '.') !== false) {
				list($number, $float) = explode('.', $number);
			}


			if (strlen($float) > 0 && $float[0] < 1) {
				$float = $float[1];

			} else if (strlen($float) === 1) {
				$float *= 10;
			}




			// number
			$string_number = $this->conversion($number);
			$string_number = $this->corrections($string_number);


			// if money
			$string_number .= ' '.$this->lastCorrection($string_number, $money);
			if ($number < 2) $string_number = substr($string_number, 0, -1);




			// float
			if ($float > 0 && $float < 100) {
				$string_float = $this->conversion($float);
				

				$string_float = ' et ' . $this->corrections($string_float);


				$string_float .= ' '.$centimes;
				if ($float < 2) $string_float = substr($string_float, 0, -1);


				if ($number == 0) {
					$string_number = '';
					$string_float = str_replace(" et ", "", $string_float);
					

					// is money first character is vowel
					$string_float .= " ".$this->isVowel(substr($money, 0, -1));
				}
			}



			$string = $string_number . $string_float;



			return $string;
		}


		private function isVowel($string) {
			$array = ['a','e','i','o','u','y'];

			for ($i=0; $i<count($array); $i++) { 
				if ($string[0] === $array[$i]) return "d'".$string;
			}

			return 'de '.$string;
		}


		private function lastCorrection($string, $money) {
			$dictionary = array('million', 'millions', 'milliard', 'milliards');

			for ($i=0; $i<count($dictionary); $i++) { 
				$word = $dictionary[$i];

				$length1 = strlen($string);
				$length2 = strlen($word);

				$last = substr($string, $length1-$length2, $length1);
				if ($last == $word) return $this->isVowel($money);
			}

			return $money;
		}


		private function corrections($string) {
			// supprime 'cent-' ou 'mille-' ou 'un mille'
			$string = str_replace("cent-", "cent ", $string);
			$string = str_replace("mille-", "mille ", $string);
			$string = str_replace("un mille", "mille", $string);
			$string = str_replace("-million", " million", $string);
			$string = str_replace("million-", "million ", $string);
			$string = str_replace("millions-", "millions ", $string);
			$string = str_replace("milliard-", "milliard ", $string);
			$string = str_replace("milliards-", "milliards ", $string);


			$dictionary = array(
					200 => 'deux cent',
					300 => 'trois cent',
					400 => 'quatre cent',
					500 => 'cinq cent',
					600 => 'six cent',
					700 => 'sept cent',
					800 => 'huit cent',
					900 => 'neuf cent'
				);

			// cents
			foreach ($dictionary as $words => $word) {
				$length1 = strlen($string);
				$length2 = strlen($word);

				$last = substr($string, $length1-$length2, $length1);
				if ($last == $word) $string .= "s";
			}


			return $string;
		}


		private function dictionary() {
			return array(
				0					=> 'zéro',
				1					=> 'un',
				2					=> 'deux',
				3					=> 'trois',
				4					=> 'quatre',
				5					=> 'cinq',
				6					=> 'six',
				7					=> 'sept',
				8					=> 'huit',
				9					=> 'neuf',
				10					=> 'dix',
				11					=> 'onze',
				12					=> 'douze',
				13					=> 'treize',
				14					=> 'quatorze',
				15					=> 'quinze',
				16					=> 'seize',
				17					=> 'dix-sept',
				18					=> 'dix-huit',
				19					=> 'dix-neuf',
				20					=> 'vingt',
				21					=> 'vingt et un',
				30					=> 'trente',
				31					=> 'trente et un',
				40					=> 'quarante',
				41					=> 'quarante et un',
				50					=> 'cinquante',
				51					=> 'cinquante et un',
				60					=> 'soixante',
				61					=> 'soixante et un',

				70					=> 'soixsante-dix',
				71					=> 'soixante et onze',
				72					=> 'soixante-douze',
				73					=> 'soixante-treize',
				74					=> 'soixante-quatorze',
				75					=> 'soixante-quinze',
				76					=> 'soixante-seize',
				77					=> 'soixante-dix-sept',
				78					=> 'soixante-dix-huit',
				79					=> 'soixante-dix-neuf',

				80					=> 'quatre-vingts',
				81					=> 'quatre-vingt-un',
				82					=> 'quatre-vingt-deux',
				83					=> 'quatre-vingt-trois',
				84					=> 'quatre-vingt-quatre',
				85					=> 'quatre-vingt-cinq',
				86					=> 'quatre-vingt-six',
				87					=> 'quatre-vingt-sept',
				88					=> 'quatre-vingt-huit',
				89					=> 'quatre-vingt-neuf',

				90					=> 'quatre-vingts-dix',
				91					=> 'quatre-vingt-onze',
				92					=> 'quatre-vingt-douze',
				93					=> 'quatre-vingt-treize',
				94					=> 'quatre-vingt-quatorze',
				95					=> 'quatre-vingt-quinze',
				96					=> 'quatre-vingt-seize',
				97					=> 'quatre-vingt-dix-sept',
				98					=> 'quatre-vingt-dix-huit',
				99					=> 'quatre-vingt-dix-neuf',
				100					=> 'cent',

				1000				=> 'mille',
				1000000				=> 'millions',
				1000000000 			=> 'milliard',
			);
		}


		private function conversion($number) {
			$hyphen			= '-';
			$conjunction	= '-';
			$separator		= '-';
			$dictionary		= $this->dictionary();



			if (!is_numeric($number)) {
				return false;
			}


			$string = null;

			switch (true) {
				case $number <= 21 || $number == 31 || $number == 41 || $number == 51 ||
					 $number == 61 || ($number >= 70 && $number <= 100):
					$string = $dictionary[$number];
					break;


				case $number < 100:
					$tens	= ((int) ($number / 10)) * 10;
					$units 	= $number % 10;
					$string = $dictionary[$tens];
					if ($units) {
						$string .= $hyphen . $dictionary[$units];
					}
					break;
				
				case $number < 1000:
					$hundreds  = $number / 100;
					$remainder = $number % 100;

					if ($hundreds >= 2) {
						$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
					} else {
						$string = $dictionary[100];
					}

					if ($remainder) {
						$string .= $conjunction . $this->conversion($remainder);
					}
					break;

				case $number >= 1000000000:
					$value = 1000000000;
					$milliards  = intval( $number / $value );
					$remainder = $number - $value*$milliards;


					if ($milliards >= 2) {
						$string = $dictionary[$milliards] . ' ' . $dictionary[1000000000] . 's';
					} else {
						$string = $dictionary[$milliards] . ' ' .  $dictionary[1000000000];
					}
					
					if ($remainder) {
						$string .= $conjunction . $this->conversion($remainder);
					}	
					break;

				default:
					$million = 1000000;
					$baseUnit = pow(1000, floor(log($number, 1000)));
					$numBaseUnits = (int) ($number / $baseUnit);
					$remainder = $number % $baseUnit;

					$string = $this->conversion($numBaseUnits) . ' ' . $dictionary[$baseUnit];
					if ($baseUnit == $million && $numBaseUnits < 2) $string = substr($string, 0, -1);

					if ($remainder) {
						$string .= $remainder < 100 ? $conjunction : $separator;
						$string .= $this->conversion($remainder);
					}
					break;
			}


			return $string;
		}
	}