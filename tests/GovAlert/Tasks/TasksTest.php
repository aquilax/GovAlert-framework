<?php
/**
 * Created by PhpStorm.
 * User: aquilax
 * Date: 12/17/14
 * Time: 3:01 PM
 */

namespace GovAlert\Tasks;


class TasksTest extends \PHPUnit_Framework_TestCase
{

	public function tasksProvider()
	{
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
				[
					'Cik/CikDnevenRed.html'
				],
				'2014-12-11T23:24:23+01:00',
				1,
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
				[
					'Cik/CikJalbi.html',
				],
				'2014-12-11T23:24:23+01:00',
				3,
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
			], [
				'\GovAlert\Tasks\Cik\CikProtokol',
				[
					'Cik/CikProtokol.html'
				],
				'2014-12-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Протокол №147 за 24.11.2014',
						'description' => null,
						'date' => null,
						'url' => 'http://www.cik.bg/f/972',
						'hash' => '14bf2a4d01bb0883bf4c29f087c416f4',
					]
				],
			], [
				'\GovAlert\Tasks\Cik\CikResheniq',
				[
					'Cik/CikResheniq.html',
				],
				'2014-12-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Решение №1389-НС/16.12.2014 - поправка на техническа грешка в Решение № 1158-НС от 30 септември 2014 г. на ЦИК за предизборна агитация в гр. Долни чифлик, област Варна',
						'description' => 'поправка на техническа грешка в Решение № 1158-НС от 30 септември 2014 г. на ЦИК за предизборна агитация в гр. Долни чифлик, област Варна',
						'date' => '2014-12-16',
						'url' => 'http://www.cik.bg/reshenie/?no=1389&date=16.12.2014',
						'hash' => 'b2ce72f3f3b02f146602edafa8ac1f0f',
					],
				],
			], [
				'\GovAlert\Tasks\Cik\CikSaobshteniq',
				[
					'Cik/CikSaobshteniq.html',
				],
				'2014-12-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Съобщение: Заседанието на ЦИК на 18 декември 2014 е насрочено за 10.30 часа.',
						'description' => '<div><p>СЪОБЩЕНИЕ</p><p> Заседанието на Централната избирателна комисия на 18 декември 2014 г. е насрочено за 10.30 часа. </p></div>',
						'date' => '2014-12-16',
						'url' => 'http://www.cik.bg/reshenie/?no=1389&date=16.12.2014',
						'hash' => 'b2ce72f3f3b02f146602edafa8ac1f0f',
						'url' => 'http://www.cik.bg/',
						'hash' => 'e9528811c8263cdc85568dc386217f99',
					],
				],
			], [
				'\GovAlert\Tasks\Comdos\ComdosResheniq',
				[
					'Comdos/ComdosResheniq.html',
				],
				'2014-12-02T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Решение №2-441/09.12 за Висше транспортно училище "Тодор Каблешков"',
						'description' => null,
						'date' => '2014-12-09',
						'url' => 'http://www.comdos.bg/Начало/Decision-View/p/view?DecisionID=713',
						'hash' => '3b6cf505c5b94495a2ed3d9120b9c496'
					],
				],
			], [
				'\GovAlert\Tasks\Constcourt\ConstcourtNovini',
				[
					'Constcourt/ConstcourtNovini.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Новина: Честване на 50 години конституционно правосъдие в Черна Гора 26-29 ноември 2014 г.',
						'description' => null,
						'date' => '2014-12-02',
						'url' => 'http://constcourt.bg/news/Post/890/Честване-на-50-години-конституционно-правосъдие-в-Черна-Гора-26-29-ноември-2014-г',
						'hash' => '47f94838be5695a0d4865c5d81c9c723',
					]
				]
			], [
				'\GovAlert\Tasks\Constcourt\ConstcourtSaobchteniq',
				[
					'Constcourt/ConstcourtSaobchteniq.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Съобщение по дело: КС образува конституционно дело № 13/2014 г.',
						'description' => null,
						'date' => '2014-12-04',
						'url' => 'http://constcourt.bg/caseannouncements/Post/891/Конституционният-съд-образува-конституционно-дело-13-2014-г',
						'hash' => 'e18ba7ea2c9c3f9d8ea7d20a0f775039',
					]
				],
			], [
				'\GovAlert\Tasks\Government\GovDokumenti',
				[
					'Government/GovDokumenti.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Нов документ: Заповед на заместник министър-председателя по координация на европейските политики и институционалните въпроси за обявяване на процедура за публично изслушване на кандидати за председател на ДАБЧ',
						'description' => null,
						'date' => null,
						'url' => 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0211&n=129&g=',
						'hash' => 'bb3b2e23b6eece23bb75a18bec3b9612',
					]
				],
			], [
				'\GovAlert\Tasks\Government\GovNovini',
				[
					'Government/GovNovini.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Германия застава зад България за разрешаването на ситуацията около „Южен поток”',
						'description' => null,
						'date' => '2014-12-15',
						'url' => 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0213&n=910&g=',
						'hash' => '3c8b70507f3d9eccee67f69ea8a869fb',
					]
				],
			], [
				'\GovAlert\Tasks\Government\GovNovini2',
				[
					'Government/GovNovini2.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Осигуряване на европейски стандарт на правосъдие е целта на приета от правителството актуализирана стратегия за продължаване на реформата в съдебната система',
						'description' => null,
						'date' => '2014-12-17',
						'url' => 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0212&n=3345&g=',
						'hash' => '5cabaab6feb25d4e21999940c862e44d',
					]
				],
			], [
				'\GovAlert\Tasks\Government\GovPorachki',
				[
					'Government/GovPorachki.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'СЪОБЩЕНИЕ ЗА ПРОМЯНА НА ДАТАТА НА ПУБЛИЧНОТО ЗАСЕДАНИЕ ПО ОТВАРЯНЕ И ОПОВЕСТЯВАНЕ НА ЦЕНОВИТЕ ОФЕРТИ - Обявеното публично заседание за 13 ноември 2014 г. (четвъртък) от 15.00 ч., в сградата на Министерския съвет – гр. София, бул. „Княз Ал. Дондуков“ № 1, по отваряне и оповестяване на ценовите оферти на участниците в открита процедура за възлагане на обществена поръчка с предмет: „Извършване на независим финансов одит по изпълнение на дейностите и отчитане на разходите по Фонд Техническа помощ (ФТП) и Помощ за подготовка на проекти (ППП) по Българо-швейцарската програма за сътрудничество“ се отменя. Същото ще бъде проведено на 17.11.2014 г. (понеделник) ) от 15.00 ч., в сградата на Министерския съвет – гр. София, бул. „Княз Ал. Дондуков“ № 1.',
						'description' => null,
						'date' => null,
						'url' => 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0235&n=693&g=',
						'hash' => '9a1541c1bf37ca11cdc3696867e073c6',
					]
				]
			], [
				'\GovAlert\Tasks\Government\GovResheniq',
				[
					'Government/GovResheniq.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Решение: Одобрен е Национален план за 2015 г. за насърчаване на равнопоставеността на жените и мъжете',
						'description' => null,
						'date' => null,
						'url' => 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0228&n=6581&g=',
						'hash' => '5168df717e06b4dfe4473869c6b25c8b',
					]
				]
			], [
				'\GovAlert\Tasks\Government\GovSabitiq',
				[
					'Government/GovSabitiq.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Събитие: 18 и 19 декември - Премиерът Бойко Борисов ще участва в редовното заседание на Европейския съвет',
						'description' => null,
						'date' => null,
						'url' => 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0217&n=3853&g=',
						'hash' => 'e290fe7ebfa5b13492f6c857dc129adf',
					]
				]
			], [
				'\GovAlert\Tasks\Government\GovZasedaniq',
				[
					'Government/GovZasedaniq.html',
				],
				'2014-11-11T23:24:23+01:00',
				1,
				[
					[
						'title' => 'Дневен ред на заседанието на МС на 17.12.2014 г.',
						'description' => null,
						'date' => null,
						'url' => 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0225&n=274&g=',
						'hash' => '9bee5df3ae49590e902321323810e9f0',
					]
				]

			]
		];
	}

	/**
	 * @dataProvider tasksProvider
	 */
	public function testTask($className, $fixtures, $date, $limit, $items)
	{
		$dbMock = $this->getMockBuilder('\GovAlert\Common\Database')
			->setMethods(['time'])
			->disableOriginalConstructor()
			->getMock();

		$dbMock->method('time')
			->willReturn(strtotime($date));

		$logMock = $this->getMockBuilder('\GovAlert\Common\Logger')
			->disableOriginalConstructor()
			->getMock();

		$loaderMock = $this->getMockBuilder('\GovAlert\Common\Loader')
			->setMethods(['loadURL'])
			->disableOriginalConstructor()
			->getMock();

		$i = 0;
		foreach ($fixtures as $fixture) {
			$loaderMock->expects($this->at($i++))
				->method('loadURL')
				->willReturn(file_get_contents(FIXTURES_BASE . $fixture));
		}

		$loaderMock->expects($this->any())
			->method('loadURL')
			->willReturn('<html />');

		$processorMock = $this->getMockBuilder('\GovAlert\Common\Processor')
			->setMethods(['saveItems', 'checkHash'])
			->disableOriginalConstructor()
			->getMock();
		$processorMock->expects($this->once())
			->method('saveItems')
			->with($this->callback(function ($subject) use ($limit, $items) {
				return array_slice($subject, 0, $limit) == $items;
			}));
		$processorMock->method('checkHash')
			->willReturn(true);

		$testClass = new $className($dbMock, $logMock, $loaderMock, $processorMock);
		$testClass->run();
	}
} 