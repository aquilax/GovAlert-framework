<?php

namespace GovAlert\Common;


class UtilsTest extends \PHPUnit_Framework_TestCase{

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

}