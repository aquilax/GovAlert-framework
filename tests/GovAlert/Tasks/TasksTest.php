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
				'2014-12-11T23:24:23+02:00',
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
				'2014-12-11T23:24:23+02:00',
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
				'2014-12-11T23:24:23+02:00',
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
				'2014-12-11T23:24:23+02:00',
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
				'2014-12-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Съобщение: Заседанието на ЦИК на 18 декември 2014 е насрочено за 10.30 часа.',
						'description' => '<div><p>СЪОБЩЕНИЕ</p><p> Заседанието на Централната избирателна комисия на 18 декември 2014 г. е насрочено за 10.30 часа. </p></div>',
						'date' => '2014-12-16',
						'url' => 'http://www.cik.bg/',
						'hash' => 'e9528811c8263cdc85568dc386217f99',
					],
				],
			], [
				'\GovAlert\Tasks\Comdos\ComdosResheniq',
				[
					'Comdos/ComdosResheniq.html',
				],
				'2014-12-02T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
				'2014-11-11T23:24:23+02:00',
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
			], [
				'\GovAlert\Tasks\Kfn\Kfn_Analizi',
				[
					'Kfn/Kfn_Analizi.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Анализ: Преглед на международната среда, октомври 2014 г.',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.fsc.bg/public/upload/files/menu/Pregled_na_mejdunarodnata_sreda_oktomvri_2014.pdf',
						'hash' => 'c2a36d20c439df8ffb2866a783e9abb5',
					]
				]
			], [
				'\GovAlert\Tasks\Kfn\Kfn_Novini',
				[
					'Kfn/Kfn_Novini.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Съобщение във връзка с провеждането на изпитите за придобиване на право за извършване на дейност като брокер на ценни книжа и инвестиционен консултант',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.fsc.bg/Saobshtenie-vav-vrazka-s-provejdaneto-na-izpitite-za-pridobivane-na-pravo-za-izvarshvane-na-deynost-kato-broker-na-cenni-knija-i-investicionen-konsultant-news-3877-bg',
						'hash' => 'abbafbe9f0b37dcb84ca6b99dc770c36',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mh\Mh_Naredbi',
				[
					'Min_mh/Mh_Naredbi.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Наредба за изменение и допълнение на Наредба № 6 от 2007 г. за утвърждаване на медицински стандарт за трансплантация на органи, тъкани и клетки',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=391&categoryid=7368',
						'hash' => '7794c7607fa9e0113ed5473e35ae0857',

					]
				]
			], [
				'\GovAlert\Tasks\Min_mh\Mh_Normativni',
				[
					'Min_mh/Mh_Normativni.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Проект на Постановление на Министерския съвет за одобряване на вътрешнокомпенсирани промени на утвърдените разходи по области на политики/ бюджетни програми по бюджета на Министерство на здравеопазването за 2014 г.',
						'description' => null,
						'date' => '2014-12-16',
						'url' => 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=393&categoryid=7398',
						'hash' => '8f8260a925ab872f348250f62bc10122',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mh\Mh_Novini',
				[
					'Min_mh/Mh_Novini.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Министър Москов Ще Посети Мбал „Д-Р Тота Венкова” Ад, Гр. Габрово',
						'description' => 'Утре, 18 декември, министърът на здравеопазването д-р Петър Москов и заместник-министър д-р Ваньо Шарков ще посетят МБАЛ „Д-р Тота Венкова” АД, гр. Габрово по случай...',
						'date' => '2014-12-17',
						'url' => 'http://www.mh.government.bg/News.aspx?pageid=401&newsid=4457',
						'hash' => '4e45e81d60b82f474ea1a3be76cafc5d',
						'media' => null,
					]
				]
			], [
				'\GovAlert\Tasks\Min_mh\Mh_Otcheti',
				[
					'Min_mh/Mh_Otcheti.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Тримесечен отчет на МЗ за изпълнение на бюджета (към 30.09.2014 г.)',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=532&categoryid=7319',
						'hash' => 'deef77a93d03a7ac39eeca77db790df0',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mh\Mh_Postanovleniq',
				[
					'Min_mh/Mh_Postanovleniq.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Постановление № 233 от 31 юли 2014 г. за изменение и допълнение на Наредбата за условията, правилата и реда за регулиране и регистриране на цените на лекарствените продукти, приета с Постановление № 97 на Министерския съвет от 2013 г.',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=381&categoryid=7108',
						'hash' => 'fa0ba7719dfeb52ba02a512894520a78',
					]
				],
			], [
				'\GovAlert\Tasks\Min_mh\Mh_Saobshteniq',
				[
					'Min_mh/Mh_Saobshteniq.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Съобщение: Съобщение заверка от Министерството на здравеопазването на документи и други книжа, които са предназначени за използване в чужбина и се легализират от Министерството на външните работи',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=426&categoryid=7386&home=true',
						'hash' => '01a000420b62cae16f254e50df29b437',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mi\Mi_Aktivi',
				[
					'Min_mi/Mi_Aktivi.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Продажба на активи, собственост на "Овча купел" ЕООД, гр. София',
						'description' => null,
						'date' => '2014-12-10',
						'url' => 'http://www.mi.government.bg/bg/competitions/prodajba-na-aktivi-sobstvenost-na-ovcha-kupel-eood-gr-sofiya-935-c37-1.html?p=e30=',
						'hash' => 'c27feaddc27f31f0f0fe5d8c4dfe4328',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mi\Mi_Drugi',
				[
					'Min_mi/Mi_Drugi.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Tърг за отдаване под наем на активи, находящи се в с. Елешница, Oбщина Разлог, собственост на "Редки метали" ЕООД (л), Бухово',
						'description' => null,
						'date' => '2014-11-12',
						'url' => 'http://www.mi.government.bg/bg/competitions/targ-za-otdavane-pod-naem-na-aktivi-nahodyashti-se-v-s-eleshnica-obshtina-razlog-sobstvenost-na-redki-927-c42-1.html?p=e30=',
						'hash' => '5c05c3ac85771891fea2687cb1a418e9',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mi\Mi_Fininst',
				[
					'Min_mi/Mi_Fininst.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Прилагане на правилата за избор на финансови институции към 30.09.2014 г.',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.mi.government.bg/files/useruploads/files/updu/info_prilagane_pravila-30_09_2014.doc',
						'hash' => '5dbd00717130b7e71b192c7315af65eb',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mi\Mi_KoncentraciqFin',
				[
					'Min_mi/Mi_KoncentraciqFin.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Наличие на концентрация на финансови средства към 30.09.2014 г.',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.mi.government.bg/files/useruploads/files/updu/spravka_koncentraciq-za_ka4vane.xlsx',
						'hash' => '3be528976138e3c4780d3dee90e520ce',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mi\Mi_Makrobiuletin',
				[
					'Min_mi/Mi_Makrobiuletin.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Основни макроикономически показатели за октомври 2014 г.',
						'description' => null,
						'date' => '2014-11-11T23:24:23+02:00',
						'url' => 'http://www.mi.government.bg/files/useruploads/files/macrobuletin/bg_macro_bulletin_10-2014.pdf',
						'hash' => '1fe02d8bca093b5e5566700635ff4da3',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mi\Mi_Obqvi',
				[
					'Min_mi/Mi_Obqvi.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Обява: Откриване на производство по предоставяне на разрешение за проучване на строителни материали в площ "Кошарите", област Варна',
						'description' => null,
						'date' => '2014-11-25',
						'url' => 'http://www.mi.government.bg/bg/competitions/otkrivane-na-proizvodstvo-po-predostavyane-na-razreshenie-za-prouchvane-na-stroitelni-materiali-v-plosht-933-c38-1.html?p=e30=',
						'hash' => 'cd13292a433297600112eac6adfb7f2f',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mi\Mi_Obsajdane',
				[
					'Min_mi/Mi_Obsajdane.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Обществено обсъждане: Проект на ПМС за допълнение на Наредба за допълнителните мерки съгласно Директива за определяне на екодизайна към продуктите, свързани с енергопотреблението',
						'description' => 'С наредбата за допълнителните мерки от 2010 г. се определиха процедурите за оценяване на съответствието на конкретните продуктови групи от обхвата на приетите регламенти, съгласно Директива за изискванията за екодизайн към продуктите, свързани с енергопотреблението, с изискванията за екопроектиране.',
						'date' => '2014-12-17',
						'url' => 'http://www.mi.government.bg/bg/discussion-news/proekt-na-pms-za-dopalnenie-na-naredba-za-dopalnitelnite-merki-saglasno-direktiva-za-opredelyane-na-e-1986-m268-a0-1.html',
						'hash' => '2cf3dae4d89e5c8b8a6c6f0430ee082b',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mrrb\Mrrb_Obqvi',
				[
					'Min_mrrb/Mrrb_Obqvi.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Обява: Одобрена е Методика за оценка на геоложкия риск',
						'description' => null,
						'date' => '2014-12-15',
						'url' => 'http://www.mrrb.government.bg/?controller=articles&id=6027',
						'hash' => '05a9822b1abffc9d443edb3127bc6dad',
					]
				]
			], [
				'\GovAlert\Tasks\Min_mrrb\Mrrb_Informaciq',
				[
					'Min_mrrb/Mrrb_Informaciq.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Информация: Правила за устройството, организацията на работа и дейността на Съвета по регионална политика към министъра на регионалното развитие и благоустройството',
						'description' => null,
						'date' => null,
						'url' => 'http://www.mrrb.government.bg/?controller=articles&id=6001',
						'hash' => 'e3c1d7b2b85a28fc6a169fc83def0381',

					]
				]
			], [
				'\GovAlert\Tasks\Nap\NapAktualno',
				[
					'Nap/NapAktualno.html',
				],
				'2014-11-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Без достъп до електронни услуги в събота от 09:00 ч. до 17:00 ч.',
						'description' => null,
						'date' => '2014-12-18',
						'url' => 'http://www.nap.bg/news?id=1925',
						'hash' => 'e8e9b1dff95531895c5fc840ee5e446d',
					]
				]
//			], [
//				'\GovAlert\Tasks\Parliament\ParlSabitiq',
//				[
//					'Parliament/ParlSabitiq.html',
//				],
//				'2014-12-16T23:24:23+02:00',
//				1,
//				[
//					[
//						'title' => 'Събитие [2014-12-17 15:30] Заседание на Комисия по земеделието и храните',
//						'description' => '<li><a href="/bg/parliamentarycommittees/members/2336/sittings/ID/7145">Заседание на Комисия по земеделието и храните</a></li>',
//						'date' => '2014-12-16T23:24:23+02:00',
//						'url' => 'http://parliament.bg/bg/parliamentarycommittees/members/2336/sittings/ID/7145',
//						'hash' => 'a4fc3dae885837c4a9b6345193e4544c',
//					]
//				]
			], [
				'\GovAlert\Tasks\Parliament\ParlZakoni',
				[
					'Parliament/ParlZakoni.html',
				],
				'2014-12-16T23:24:23+02:00',
				1,
				[
					[
						'title' => 'ДВ-103/2014/ Закон за ратифициране на Договора за заем между Република България и ЕЙЧ ЕС БИ СИ БАНК ПИ ЕЛ СИ, СОСИЕТЕ ЖЕНЕРАЛ, СИТИБАНК, ЕН ЕЙ (клон Лондон) и "УНИКРЕДИТ БУЛБАНК" АД - в ролята на регистратори и упълномощени водещи организатори, и "УНИКРЕДИТ БАНК" АГ (клон Лондон) - в ролята на агент',
						'description' => null,
						'date' => '2014-12-16T23:24:23+02:00',
						'url' => 'http://parliament.bg/bg/laws/ID/15122/',
						'hash' => '97a627a43a99e3f4df972a9ca821054d',
					]
				]
			], [
				'\GovAlert\Tasks\Tso\TsoNovini',
				[
					'Tso/TsoNovini.html',
				],
				'2014-12-16T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Новина: Отстранени са тежките аварии по електропреносната мрежа',
						'description' => '<span>В периодът 3- 13 декември, в резултат на влошена климатична обстановка</span>',
						'date' => '2014-12-16T23:24:23+02:00',
						'url' => 'http://www.tso.bg/default.aspx/novini/bg',
						'hash' => '5b8b536ce523716441e49f3c93a6934d',
					]
				]
			], [
				'\GovAlert\Tasks\Tso\TsoSaobshteniq',
				[
					'Tso/TsoSaobshteniq.html',
				],
				'2014-12-16T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Съобщение: Промяна в ръководството на ЕСО ЕАД',
						'description' => '<span>На вниманието на всички заинтересовани</span>',
						'date' => '2014-12-16T23:24:23+02:00',
						'url' => 'http://www.tso.bg/default.aspx/saobshtenija/bg',
						'hash' => 'f701f68c98ca157e88d3611b3d9d4b5e',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrBlagoevgrad',
				[
					'Mvr/MvrBlagoevgrad.html',
				],
				'2014-12-16T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Благоевград] Коледно тържество за децата на полицейските служители в ОДМВР - Благоевград',
						'description' => null,
						'date' => '2014-12-18',
						'url' => 'http://www.blagoevgrad.mvr.bg/Prescentar/Novini/news-20141218.htm',
						'hash' => 'fb71a14fa0c550afd917e8a6440b3350',
					]
				]
//			], [
//				'\GovAlert\Tasks\Mvr\MvrBurgas',
//				[
//					'Mvr/MvrBurgas.html',
//				],
//				'2014-12-16T23:24:23+02:00',
//				1,
//				[
//					[
//						'title' => '[Благоевград] Коледно тържество за децата на полицейските служители в ОДМВР - Благоевград',
//						'description' => null,
//						'date' => '2014-12-18',
//						'url' => 'http://www.blagoevgrad.mvr.bg/Prescentar/Novini/news-20141218.htm',
//						'hash' => 'fb71a14fa0c550afd917e8a6440b3350',
//					]
//				]
			], [
				'\GovAlert\Tasks\Mvr\MvrDobrich',
				[
					'Mvr/MvrDobrich.html',
				],
				'2014-12-06T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Добрич] График на СПО по линия на "Пътна полиция" при ОД МВР Добрич',
						'description' => null,
						'date' => '2014-12-05',
						'url' => 'http://dobrich.mvr.bg/Prescentar/Novini/05122014.htm',
						'hash' => '60f56803eeb8755e9ad8a1c284a80135',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrGabrovo',
				[
					'Mvr/MvrGabrovo.html',
				],
				'2014-12-06T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Габрово] Пенчо Пенчев е новият началник на сектор „Пътна полиция” при ОДМВР-Габрово',
						'description' => null,
						'date' => '2014-12-17',
						'url' => 'http://www.gabrovo.mvr.bg/PressOffice/News/news141217_01.htm',
						'hash' => '5f97a626db490fc6fea8ec229b37c61e',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrGabrovoIzdirvani',
				[
					'Mvr/MvrGabrovoIzdirvani.html',
				],
				'2013-12-11T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Габрово] ОДМВР-Габрово издирва Дилянка Маркова /на 47 г./ от Габрово',
						'description' => null,
						'date' => '2013-12-12',
						'url' => 'http://www.gabrovo.mvr.bg/PressOffice/Wanted/DMarkova.htm',
						'hash' => '15dfdca0109da85124a4c01166bf8c2c',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrHaskovo',
				[
					'Mvr/MvrHaskovo.html',
				],
				'2014-12-11T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Хасково] „Ауди”, „БМВ” и „Мерцедес” само през декември на прегледи и в понеделник',
						'description' => null,
						'date' => '2014-12-08',
						'url' => 'http://haskovo.mvr.bg/Prescentar/Novini/nov_141208_01.htm',
						'hash' => '61a1938aa3b35532c7a36961a6275617',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrKampanii',
				[
					'Mvr/MvrKampanii.html',
				],
				'2014-12-11T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Национална благотворителна кампания за подпомагане на децата на загиналите и пострадалите при изпълнение на служебните задължения служители от системата на МВР',
						'description' => null,
						'date' => '2014-12-05',
						'url' => 'http://press.mvr.bg/Kampanii/mvr_1866.htm',
						'hash' => '89c3e0f493cdf71e312cbe9c69588011',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrKardjali',
				[
					'Mvr/MvrKardjali.html',
				],
				'2014-12-11T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Кърджали] Спасители и полицаи предприемат превантивни мерки във връзка с възможно повишаване на нивото на река Арда',
						'description' => null,
						'date' => '2014-12-09',
						'url' => 'http://www.kardjali.mvr.bg/PressOffice/News/news_91214.htm',
						'hash' => 'c5d494df7b80658647e1648f62b423f3',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrKiustendil',
				[
					'Mvr/MvrKiustendil.html',
				],
				'2014-12-03T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Кюстендил] Осигурено е полицейско присъствие в районите на обявените за изплащане на депозитите от КТБ клонове на банки',
						'description' => null,
						'date' => '2014-12-04',
						'url' => 'http://www.kustendil.mvr.bg/PressOffice/News/141204.htm',
						'hash' => '055d9fa0855876cdff5e1b814517b5b5',

					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrLovech',
				[
					'Mvr/MvrLovech.html',
				],
				'2014-12-06T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Ловеч] Полицаи от ОД МВР-Ловеч и пожарникари от РД ПБЗН предприемат превантивни мерки за гарантиране на безопасността по време на студентския празник',
						'description' => null,
						'date' => '2014-12-05',
						'url' => 'http://www.lovech.mvr.bg/PressOffice/News/news141205_01.htm',
						'hash' => 'b62485643fc2009a0ce0e49555be5045',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrLovechIzdirvani',
				[
					'Mvr/MvrLovechIzdirvani.html',
				],
				'2007-10-29T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Ловеч] ИЗДИРВАНО ЛИЦЕ',
						'description' => null,
						'date' => '2007-10-28',
						'url' => 'http://www.lovech.mvr.bg/PressOffice/Wanted/petar.htm',
						'hash' => 'f21bfbae5cad360e79acf60652b660a3',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrMontana',
				[
					'Mvr/MvrMontana.html',
				],
				'2007-10-29T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Монтана] Проблеми на досъдебното производство бяха обсъдени на работни срещи с Прокуратурата',
						'description' => null,
						'date' => '2014-12-19',
						'url' => 'http://www.montana.mvr.bg/PressOffice/News/news_20141219.htm',
						'hash' => '540adae3991d66af089d6f7df60121cf',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrMontanaIzdirvani',
				[
					'Mvr/MvrMontanaIzdirvani.html',
				],
				'2013-07-23T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Монтана] ПЛАМЕН МЛАДЕНОВ КУЗМАНОВ ОТ ГР. СОФИЯ',
						'description' => null,
						'date' => '2013-07-23',
						'url' => 'http://www.montana.mvr.bg/PressOffice/Wanted/5611226644.htm',
						'hash' => '73f5a372e74a93e12d45569a087b4389',
					]
				]
			], [
				'\GovAlert\Tasks\Mvr\MvrNovini',
				[
					'Mvr/MvrNovini.html',
				],
				'2013-07-23T23:24:23+02:00',
				1,
				[
					[
						'title' => 'Покана за брифинг',
						'description' => null,
						'date' => '2014-12-26',
						'url' => 'http://press.mvr.bg/NEWS/news141226_02.htm',
						'hash' => 'a033bb1fd6eafa785849cd4c4bd45fdb',
					]
				]
			], [

				'\GovAlert\Tasks\Mvr\MvrPazardjik',
				[
					'Mvr/MvrPazardjik.html',
				],
				'2013-07-23T23:24:23+02:00',
				1,
				[
					[
						'title' => '[Пазарджик] ВАЖНО ! Поради ремонт или рехабилитация на някои главни пътища е въведена временна организация на движението по тях',
						'description' => null,
						'date' => '2014-11-18',
						'url' => 'http://pazardjik.mvr.bg/Prescentar/Novini/rexabilitazia+na+patni+otsechki.htm',
						'hash' => '8c951344a1e0e79e7aaf83eedb03159d',
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
			->setMethods(['loadURL', 'setPageLoad'])
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
			->setMethods(['saveItems', 'checkHash', 'checkTitle'])
			->disableOriginalConstructor()
			->getMock();
		$processorMock->expects($this->once())
			->method('saveItems')
			->with($this->callback(function ($subject) use ($limit, $items) {
				return array_slice($subject, 0, $limit) == $items;
			}));
		$processorMock->method('checkHash')
			->willReturn(true);
		$processorMock->method('checkTitle')
			->willReturn(true);

		/**
		 * @var \GovAlert\Tasks\Task $testClass
		 */
		$testClass = new $className($dbMock, $logMock, $loaderMock, $processorMock);
		$testClass->run();
	}
} 