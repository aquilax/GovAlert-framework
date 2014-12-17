<?php
/**
 * Created by PhpStorm.
 * User: aquilax
 * Date: 12/17/14
 * Time: 3:01 PM
 */

namespace GovAlert\Tasks;


class TasksTest extends \PHPUnit_Framework_TestCase {

	public function tasksProvider(){
		return [
			[
//				'\GovAlert\Tasks\Bas\BasZemetreseniq',
//				'Bas/BasZemetreseniq.xml',
//				[]
//			], [
//				'\GovAlert\Tasks\Adminreg\ArKonkursi',
//				'Adminreg/ArKonkursi.html',
//				[]
//			], [
//				'\GovAlert\Tasks\Bnb\Bnb_BrutenVanshenDalg',
//				'Bnb/Bnb_BrutenVanshenDalg.html',
//				[]
//			], [
				'\GovAlert\Tasks\Cik\CikDnevenRed',
				'Cik/CikDnevenRed.html',
				[
					[
						'title' => 'Дневен ред за 16.12.2014',
						'description' => null,
						'date' => null,
						'url' => 'http://www.cik.bghttp://www.cik.bg/f/980',
						'hash' => 'e510e00ad8f7ec1058c20248d733589d',
					]
				]
			], [
				'\GovAlert\Tasks\Cik\CikJalbi',
				'Cik/CikJalbi.html',
				[
					[
						'title' => 'Решение № 1371-НС/ 18.11.2014',
						'description' => null,
						'date' => null,
						'url' => 'http://www.cik.bg/reshenie/?no=1371&date=18.11.2014',
						'hash' => '57b529a5cde7819b4359d696a45f90f9',
					], [
						'title' => 'Жалба до ВАС срещу Решение № 1371-НС от 18.11.2014 г., постъпила на 24.11.2014 г.',
						'description' => null,
						'date' => null,
						'url' => 'http://www.cik.bg/f/j_2_360',
						'hash' => '805fa624bfcd2998783739206242e482',
					], [
						'title' => 'Определение № 14206 на ВАС от 27.11.2014 г. по адм. дело № 14736/ 2014 г.',
						'description' => null,
						'date' => null,
						'url' => 'http://www.sac.government.bg/court22.nsf/d038edcf49190344c2256b7600367606/b0c90f2a3a6eb233c2257d9d002fd237?OpenDocument',
						'hash' => 'faa5aac3e812ced156045740a13db0f4',
					],
				]
			]
		];
	}

	/**
	 * @dataProvider tasksProvider
	 */
	public function testTask($className, $fixture, $items) {
		$dbMock = $this->getMockBuilder('\GovAlert\Common\Database')
			->setMethods(['time'])
			->disableOriginalConstructor()
			->getMock();

		$dbMock->method('time')
			->willReturn(strtotime('2014-12-11T23:24:23+01:00'));

		$logMock = $this->getMockBuilder('\GovAlert\Common\Logger')
			->disableOriginalConstructor()
			->getMock();

		$fixture = file_get_contents(FIXTURES_BASE . $fixture);

		$loaderMock = $this->getMockBuilder('\GovAlert\Common\Loader')
			->setMethods(['loadURL'])
			->disableOriginalConstructor()
			->getMock();

		$loaderMock->method('loadURL')
			->willReturn($fixture);

		$processorMock = $this->getMockBuilder('\GovAlert\Common\Processor')
			->setMethods(['saveItems', 'checkHash'])
			->disableOriginalConstructor()
			->getMock();
		$processorMock->expects($this->once())
			->method('saveItems')
			->with($this->equalTo($items));
		$processorMock->method('checkHash')
			->willReturn(true);

		$testClass = new $className($dbMock, $logMock, $loaderMock, $processorMock);
		$testClass->run();
	}
} 