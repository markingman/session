# Session Handler

Simple session handler for PHP applications

## Status

This is a small utility library shared for convenience.  
Maintenance is best-effort and may be minimal.

## Installation

To use, require in `composer.json`, e.g:

```
composer require markingman/session
```

## Usage Overview

In `php.ini`:

```ini
session.save_path = "/var/www/disk/var/sess"
```

This is optional. It's passed to `SessionHandler::open(string $path, string $name)`.

Set the user-level session storage handler in the code, e.g.:

```php
if (session_status() === PHP_SESSION_NONE) {
    session_set_save_handler(new SessionHandler(), true);
    session_start();
}
```

Typically create a wrapper:

```php
class ExampleSession extends Session
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
```

Instantiate with `$_SESSION` in production, or an `array()` for testing:

```php
$session = new ExampleSession($_SESSION);
```

## License

This project is licensed under the MIT License.


