# Session Handler

Simple PHP session handler

## Installation

To use, require in `composer.json`, e.g:

```
composer require markingman/session
```

## Configuration

`session.save_path = "/var/www/disk/var/sess"`  
`session.save_handler = files`  
`session.save_handler = SessionHandler`

Consider  
`session.sid_length`  
`session.sid_bits_per_character`  
`SessionHandler::VALID_SID_REGX`
	


