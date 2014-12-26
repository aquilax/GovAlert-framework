<?php

namespace GovAlert\Tasks\Interpol;

class InterpolIzdirvani extends Base
{

	protected $categoryId = 0;
	protected $categoryName = 'издирвани';
	protected $categoryURLs = [
		['http://www.interpol.int/notice/search/missing/(offset)/%d/(Nationality)/122/(current_age_maxi)/100/(search)/1', 50],
		['http://www.interpol.int/notice/search/wanted/(offset)/%d/(RequestingCountry)/122/(current_age_maxi)/100/(search)/1', 100],
	];

	protected function execute($html)
	{
		$this->interpolLoad(
			[
				$this->categoryName,
				$this->categoryId,
				$this->categoryURLs
			]
		);
	}
} 