<?php

class Bnb_KoreditiraneDrujestva extends Bnb
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