<?php

namespace MarkIngman\Session;

class TestSession extends Session
{
	public function get_example(): string
	{
		$value = $this->get('example');
		return is_string($value) ? $value : '';
	}

	public function set_example(string $value): void
	{
		$this->set('example', $value);
	}
}
