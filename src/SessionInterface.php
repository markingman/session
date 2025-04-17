<?php

namespace MarkIngman\Session;

interface SessionInterface
{
	/** @param array<int, string> $a E.g: [1 => REMOTE_ADDR, 2 => HTTP_USER_AGENT] */
	public function validate(array $a): bool;

	public function drop(): void;
}
