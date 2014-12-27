<?php

namespace GovAlert\Tasks\Interpol;

class InterpolProcessIzdirvani extends Base
{

	protected $categoryId = 0;
	protected $categoryName = 'издирвани';

	function execute($html)
	{
		$this->interpolProcess(
			[
				$this->categoryName,
				$this->categoryId,
				'%s е обявен%s за издирване /CC @Interpol_HQ',
				'Обявени са още %d души за издирване  /CC @Interpol_HQ',
				'http://www.interpol.int/notice/search/wanted/(offset)/%d/(Nationality)/122/(current_age_maxi)/100/(search)/1',
				'http://www.interpol.int/notice/search/wanted',
				['mibulgaria', 'GovAlertEU'],
			]
		);
	}

} 