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
		require_once __DIR__ . '/../fixtures/TestSession.php';

		$store = [];
		$session = new TestSession($store);

		$session->set('test', 123);
		$this->assertEquals(123, $store['test'] ?? null);
		$this->assertEquals(123, $session->get('test'));

		$this->assertTrue($session->isset('test'));
		$session->unset('test');
		$this->assertNull($session->get('test'));
		$this->assertFalse(isset($store['test']));
		$this->assertFalse($session->isset('test'));
		$session->set('test', 123);
		$this->assertEquals(123, $store['test'] ?? null);
		$this->assertEquals(123, $session->get('test'));

		$store['test2'] = 456;
		$this->assertTrue($session->isset('test2'));
		$this->assertEquals(456, $session->get('test2'));

		$this->assertNull($session->get('not_set'));

		$session->set('test', 123);
		$this->assertEquals(123, $session->get('test'));

		$session->drop();
		$this->assertNull($session->get('test'));

		$this->assertEquals('', $session->get_example());
		$session->set_example('example');
		$this->assertEquals('example', $session->get_example());
	}
}
