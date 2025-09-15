# Session Handler

Simple PHP session handler

## Installation

To use, require in `composer.json`, e.g:

```
composer require markingman/session
```

## Configuration

In `php.ini`:

`session.save_path = "/var/www/disk/var/sess"`

This is optional. It's passed to `SessionHandler::open(string $path, string $name)`.

Note `session.name` is also passed to `SessionHandler::open(string $path, string $name)`.

Can set `session.save_handler = user` but that's also optional, it's ignored if the user-level session storage handler is set.

Set the user-level session storage handler in the code:

`session_set_save_handler(new MarkIngman\Session\SessionHandler(), true)`

Then the start the session with `session_start()`.

In `php.ini`, consider:

`session.sid_length`  
`session.sid_bits_per_character`  
`SessionHandler::VALID_SID_REGX`
	


