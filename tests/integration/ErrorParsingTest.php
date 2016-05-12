<?php

namespace Art4\JsonApiClient\Integration\Tests;

use Art4\JsonApiClient\Utils\Helper;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ErrorParsingTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

	/**
	 * @test
	 */
	public function testParseErrors()
	{
		$string = $this->getJsonString('09_errors.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('errors'));
		$this->assertFalse($document->has('meta'));
		$this->assertTrue($document->has('jsonapi'));
		$this->assertFalse($document->has('links'));
		$this->assertFalse($document->has('included'));
		$this->assertFalse($document->has('data'));

		$errors = $document->get('errors');
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorCollectionInterface', $errors);
		$this->assertCount(3, $errors->getKeys());

		$this->assertTrue($errors->has('0'));
		$error0 = $errors->get('0');

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error0);
		$this->assertCount(4, $error0->getKeys());
		$this->assertTrue($error0->has('code'));
		$this->assertSame('123', $error0->get('code'));
		$this->assertTrue($error0->has('source'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSourceInterface', $error0->get('source'));
		$this->assertTrue($error0->has('source.pointer'));
		$this->assertSame('/data/attributes/first-name', $error0->get('source.pointer'));
		$this->assertTrue($error0->has('title'));
		$this->assertSame('Value is too short', $error0->get('title'));
		$this->assertTrue($error0->has('detail'));
		$this->assertSame('First name must contain at least three characters.', $error0->get('detail'));

		$this->assertTrue($errors->has('1'));
		$error1 = $errors->get('1');

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error1);
		$this->assertCount(4, $error1->getKeys());
		$this->assertTrue($error1->has('code'));
		$this->assertSame('225', $error1->get('code'));
		$this->assertTrue($error1->has('source'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSourceInterface', $error1->get('source'));
		$this->assertTrue($error1->has('source.pointer'));
		$this->assertSame('/data/attributes/password', $error1->get('source.pointer'));
		$this->assertTrue($error1->has('title'));
		$this->assertSame('Passwords must contain a letter, number, and punctuation character.', $error1->get('title'));
		$this->assertTrue($error1->has('detail'));
		$this->assertSame('The password provided is missing a punctuation character.', $error1->get('detail'));

		$this->assertTrue($errors->has('2'));
		$error2 = $errors->get('2');

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error2);
		$this->assertCount(3, $error2->getKeys());
		$this->assertTrue($error2->has('code'));
		$this->assertSame('226', $error2->get('code'));
		$this->assertTrue($error2->has('source'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSourceInterface', $error2->get('source'));
		$this->assertTrue($error2->has('source.pointer'));
		$this->assertSame('/data/attributes/password', $error2->get('source.pointer'));
		$this->assertTrue($error2->has('title'));
		$this->assertSame('Password and password confirmation do not match.', $error2->get('title'));
		$this->assertFalse($error2->has('detail'));

		$this->assertFalse($errors->has('3'));

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}
}
