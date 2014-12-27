<?php

namespace GovAlert\Common;


class UtilsTest extends \PHPUnit_Framework_TestCase
{

	public function transliterateProvider()
	{
		return [
			['YAYU', 'ЯЮ'],
			['YURUKOV', 'ЮРУКОВ'],
		];
	}

	/**
	 * @dataProvider transliterateProvider
	 */
	public function testTransliterate($given, $expected)
	{
		$this->assertEquals($expected, Utils::transliterate($given));
	}

	public function cleanSpacesProvider()
	{
		return [
			['    ', ''],
			[' ; ', ';'],
		];
	}

	/**
	 * @dataProvider cleanSpacesProvider
	 */
	public function testCleanSpaces($given, $expected)
	{
		$this->assertEquals($expected, Utils::cleanSpaces($given));
	}

	public function bgMonthProvider()
	{
		return [
			['Януари', '01'],
			['ноември', '11'],
			['кифла', 'кифла'],
		];
	}

	/**
	 * @dataProvider bgMonthProvider
	 */
	public function testBgMonth($given, $expected)
	{
		$this->assertEquals($expected, Utils::bgMonth($given));
	}

	public function fixCaseProvider()
	{
		return [
			['КИФЛА', 'Кифла'],
			['кифла', 'Кифла'],
			['Кифла', 'Кифла'],
		];
	}

	/**
	 * @dataProvider fixCaseProvider
	 */
	public function testFixCase($given, $expected)
	{
		$this->assertEquals($expected, Utils::fixCase($given));
	}
}