# Univio Logs module

# Description

The Univio Logs module enables the redirection of all log outputs to `/dev/stdout`, replacing traditional file-based logging.

## Installation details

To install the module, execute the following commands:
```bash
composer require univio/module-logs
```
```bash
bin/magento module:enable Univio_Logs
```

## Configuration

To enable log redirection, update the `env.php` file with the following configuration:
```php
    'logs' => [
        'stdout_enabled' => '1'
    ]
```
