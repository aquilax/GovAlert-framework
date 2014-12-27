<?php

namespace GovAlert\Tasks\Bnb;

class Bnb_KoreditiraneDrujestva extends Base
{

	protected $categoryId = 8;
	protected $categoryName = 'Дружества, специализирани в кредитиране';
	protected $channelNameBig = 'СТАТИСТИКА НА ДРУЖЕСТВАТА, СПЕЦИАЛИЗИРАНИ В КРЕДИТИРАНЕ';
	protected $categoryURL = 'http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSLendingCorporations';

	protected function execute($html)
	{
		$this->statsHandling(
			$html,
			$this->categoryId,
			$this->categoryName,
			$this->channelNameBig,
			$this->categoryURL
		);
	}

}