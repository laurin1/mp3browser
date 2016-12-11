<?php

namespace library\mp3browser;

use mp3browser\Index;

class IndexTest extends \PHPUnit_Framework_TestCase{

	public function test_(){

		$oReflectionMethod = new \ReflectionMethod(Index::class, "getMP3Link");

		$oReflectionMethod->setAccessible(true);

		$sTest =
			$oReflectionMethod->invoke(
				new Index(),
				"/mp3files/",
				"my.mp3");

		\PHPUnit_Framework_Assert::assertSame(
			'<a href="C:/inetpub/mp3browser.laurin1.dyndns.org/vendor/phpunit/phpunit/phpunit?src=/mp3filesmy.mp3">my.mp3</a>',
			$sTest);


	}

}
