<?php

namespace MarkIngman\Session;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class SessionHandlerTest extends TestCase
{
	protected string $dir;
	protected SessionHandler $handler;

	public function testOpenAndClose(): void
	{
		$this->assertTrue($this->handler->open($this->dir, 'PHPSESSID'));
		$this->assertTrue($this->handler->close());
	}

	public function testWriteAndRead(): void
	{
		$id = bin2hex(random_bytes(16));
		$data = 'example_session_data';

		$this->assertEquals('', $this->handler->read($id));
		$this->assertTrue($this->handler->write($id, $data));
		$this->assertEquals($data, $this->handler->read($id));
	}

	public function testReadInvalidId(): void
	{
		$this->assertFalse($this->handler->read('invalid_id'));
	}

	public function testWriteInvalidId(): void
	{
		$this->assertFalse($this->handler->write('invalid_id', 'data'));
	}

	public function testDestroy(): void
	{
		$id = bin2hex(random_bytes(16));
		$this->handler->write($id, 'data');
		$this->assertFileExists($this->dir . '/' . SessionHandler::STORE_PREFIX . $id);
		$this->assertTrue($this->handler->destroy($id));
		$this->assertFileDoesNotExist($this->dir . '/' . SessionHandler::STORE_PREFIX . $id);
	}

	public function testDestroyInvalidId(): void
	{
		$this->assertFalse($this->handler->destroy('invalid_id'));
	}

	public function testGcDeletesExpired(): void
	{
		$id = bin2hex(random_bytes(16));
		$path = $this->dir . '/' . SessionHandler::STORE_PREFIX . $id;
		file_put_contents($path, 'data');
		touch($path, time() - 3600); // Set old timestamp

		$this->assertSame(1, $this->handler->gc(1));
		$this->assertFileDoesNotExist($path);
	}

	public function testGcKeepsRecent(): void
	{
		$id = bin2hex(random_bytes(16));
		$path = $this->dir . '/' . SessionHandler::STORE_PREFIX . $id;
		file_put_contents($path, 'data');

		$this->assertSame(0, $this->handler->gc(3600));
		$this->assertFileExists($path);
	}

	protected function setUp(): void
	{
		$this->dir = sys_get_temp_dir() . '/phpunit_sess_' . bin2hex(random_bytes(4));
		mkdir($this->dir);
		$this->handler = new SessionHandler();
		$this->handler->open($this->dir, 'PHPSESSID');
	}

	protected function tearDown(): void
	{
		// Clean up test files
		if (($glob = glob($this->dir . '/*')) === false) {
			throw new RuntimeException('Could not clean up test files');
		}

		foreach ($glob as $file) {
			unlink($file);
		}
		rmdir($this->dir);
	}
}
