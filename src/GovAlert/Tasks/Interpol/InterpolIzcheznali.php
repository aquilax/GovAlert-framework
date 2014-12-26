<?php

namespace GovAlert\Tasks\Interpol;

class InterpolIzcheznali extends Base
{

	protected $categoryId = 1;
	protected $categoryName = 'изчезнали';
	protected $categoryURLs = [
		['http://www.interpol.int/notice/search/missing/(offset)/%d/(Nationality)/122/(current_age_maxi)/100/(search)/1', 0],
	];

	protected function execute($html)
	{
		$this->interpolLoad(
			[
				$this->categoryName,
				$this->categoryId,
				$this->categoryURLs,
			]
		);
	}
} 