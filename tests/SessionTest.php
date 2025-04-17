<?php

namespace MarkIngman\Session;

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
	public function testValidateReturnsTrueWithSameContext(): void
	{
		$store = [];
		$session = new Session($store);
		$this->assertTrue($session->validate([1 => '127.0.0.1'])); // saves hash
		$this->assertTrue($session->validate([1 => '127.0.0.1'])); // matches hash
	}

	public function testValidateReturnsFalseWithDifferentContext(): void
	{
		$store = [];
		$session = new Session($store);
		$this->assertTrue($session->validate([1 => '127.0.0.1'])); // saves hash
		$this->assertFalse($session->validate([1 => '192.168.1.1'])); // hash mismatch
	}

	public function testValidateReturnsTrueWithSameMultiContext(): void
	{
		$store = [];
		$session = new Session($store);
		$this->assertTrue($session->validate([1 => '127.0.0.1', 2 => 'Mozilla 12345'])); // saves hash
		$this->assertTrue($session->validate([1 => '127.0.0.1', 2 => 'Mozilla 12345'])); // matches hash
	}

	public function testValidateReturnsFalseWithDifferentMultiContext(): void
	{
		$store = [];
		$session = new Session($store);
		$this->assertTrue($session->validate([1 => '127.0.0.1', 2 => 'Mozilla 12345'])); // saves hash
		$this->assertFalse($session->validate([1 => '127.0.0.1', 2 => 'Mozilla 789'])); // hash mismatch
	}

	public function testSetGetValue(): void
	{
		require_once __DIR__ . '/fixtures/TestSession.php';

		$store = [];
		$session = new TestSession($store);

		$session->test = 123;
		$this->assertEquals(123, $session->test);

		$this->assertTrue(isset($session->test));
		unset($session->test);
		$this->assertNull($session->test);
		$this->assertFalse(isset($session->test));

		$this->assertNull($session->not_set);

		$session->test = 123;
		$this->assertEquals(123, $session->test);

		$session->drop();
		$this->assertNull($session->test);

		$this->assertEquals('', $session->get_example());
		$session->set_example('example');
		$this->assertEquals('example', $session->get_example());
	}
}
