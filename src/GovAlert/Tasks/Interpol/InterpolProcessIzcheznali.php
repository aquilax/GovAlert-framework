<?php

namespace GovAlert\Tasks\Interpol;

class InterpolProcessIzcheznali extends Base
{

	protected $categoryId = 1;
	protected $categoryName = 'изчезнали';

	function execute($html)
	{
		$this->interpolProcess(
			[
				$this->categoryName,
				$this->categoryId,
				'%s е обявен%s за безследно изчезнал%2\$s /CC @Interpol_HQ',
				'Обявени са още %d българи за безследно изчезнали  /CC @Interpol_HQ',
				'http://www.interpol.int/notice/search/missing/(offset)/0/(Nationality)/122/(current_age_maxi)/100/(search)/1',
				'http://www.interpol.int/notice/search/missing',
				'lipsva',
				['mibulgaria', 'GovAlertEU']
			]
		);
	}
}
