<?php

namespace MarkIngman\Session;

/**
 * @property string|null $not_set
 * @property int|null $test
 * @property string $example
 */
class TestSession extends Session
{
	public function get_example(): string
	{
		return (isset($this->store['example']) and is_string($this->store['example'])) ?
			$this->store['example'] : '';
	}

	public function set_example(string $value): void
	{
		$this->store['example'] = $value;
	}
}
