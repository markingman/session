<?php

namespace MarkIngman\Session;

use function hash;
use function is_array;

class Session implements SessionInterface
{
	const string VALIDATE_KEY = '@';

	/**
	 * @param array<string, string|bool|int|array<string|int, string>|null> $store Pass $_SESSION in production
	 */
	public function __construct(
		protected array &$store
	) {
	}

	/** @param array<int, string> $a E.g: [1 => REMOTE_ADDR, 2 => HTTP_USER_AGENT] */
	public function validate(array $a): bool
	{
		if (!isset($this->store[static::VALIDATE_KEY]) or !is_array($this->store[static::VALIDATE_KEY])) {
			$hashed = [];
			foreach ($a as $k => $v) {
				$hashed[$k] = $this->hash($v);
			}
			$this->store[static::VALIDATE_KEY] = $hashed;
		} else {
			foreach ($a as $k => $v) {
				if (empty($this->store[static::VALIDATE_KEY][$k]) or $this->store[static::VALIDATE_KEY][$k] !== $this->hash($v)) {

					return false;
				}
			}
		}

		return true;
	}

	/** @return string|bool|int|array<string|int, string>|null */
	public function get(string $k): string|bool|int|array|null
	{
		return $this->store[$k] ?? null;
	}

	/** @param string|bool|int|array<string|int, string>|null $v */
	public function set(string $k, string|bool|int|array|null $v): void
	{
		$this->store[$k] = $v;
	}

	public function isset(string $k): bool
	{
		return isset($this->store[$k]);
	}

	public function unset(string $k): void
	{
		unset($this->store[$k]);
	}

	public function drop(): void
	{
		$this->store = [];
	}

	protected function hash(string $v): string
	{
		return hash('sha256', $v);
	}
}
