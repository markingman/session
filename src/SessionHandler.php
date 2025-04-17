<?php

namespace MarkIngman\Session;

use DirectoryIterator;
use SessionHandlerInterface;

class SessionHandler implements SessionHandlerInterface
{
	const string VALID_SID_REGX = '/^sess_[a-zA-Z0-9]{26,48}$/';

	protected string $dir;

	public function open(string $path, string $name): bool
	{
		$this->dir = $path . DIRECTORY_SEPARATOR;// presumes valid path

		return true;
	}

	public function close(): bool
	{
		return true;
	}

	public function read(string $id): string|false
	{
		if (!$this->validate_id($id)) {
			return false;
		}

		return file_get_contents($this->dir . $id) ?: '';
	}

	public function write(string $id, string $data): bool
	{
		if (!$this->validate_id($id)) {
			return false;
		}

		return (false !== file_put_contents($this->dir . $id, $data));
	}

	public function destroy(string $id): bool
	{
		if (!$this->validate_id($id)) {
			return false;
		}

		return @unlink($this->dir . $id);
	}

	public function gc(int $max_lifetime): int|false
	{
		$i = 0;

		foreach (new DirectoryIterator($this->dir) as $it) {
			if ($it->isDot() or $it->isDir()) {
				continue;
			}

			if ((filemtime($it->getPathname()) + $max_lifetime) < time()) {
				if (@unlink($it->getPathname())) {
					$i++;
				}
			}
		}

		return $i;
	}

	public function validate_id(string $id): bool
	{
		if (!preg_match(static::VALID_SID_REGX, $id)) {
			return false;
		}

		return true;
	}
}
