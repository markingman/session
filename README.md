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
`session.save_handler = user` (optional)  

`require_once /path/to/class/SessionHandler.php;
session_set_save_handler(new MarkIngman\Session\SessionHandler(), true);
session_start();`

Consider  
`session.sid_length`  
`session.sid_bits_per_character`  
`SessionHandler::VALID_SID_REGX`
	


