# CLI Library *documentation*

## Console

```php
use Framework\CLI\Console;

$console = new Console();
$console->run(); 
```

### Custom Language

```php
use Framework\CLI\Console;
use Framework\Language\Language;

$language = new Language('pt-br');
$console = new Console($language);
$console->run(); 
```

### Adding a Custom Command

```php
use Custom\CustomCommand;
use Framework\CLI\Console;

$console = new Console();
$customCommand = new CustomCommand($console);
$console->addCommand($customCommand);
$console->run(); 
```
