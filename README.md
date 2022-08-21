# Package for quickly generating widgets of models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dinhdjj/filament-model-widgets.svg?style=flat-square)](https://packagist.org/packages/dinhdjj/filament-model-widgets)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/dinhdjj/filament-model-widgets/run-tests?label=tests)](https://github.com/dinhdjj/filament-model-widgets/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/dinhdjj/filament-model-widgets/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/dinhdjj/filament-model-widgets/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/dinhdjj/filament-model-widgets.svg?style=flat-square)](https://packagist.org/packages/dinhdjj/filament-model-widgets)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require dinhdjj/filament-model-widgets
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-model-widgets-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-model-widgets-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-model-widgets-views"
```

## Usage

```php
$filamentModelWidgets = new Dinhdjj\FilamentModelWidgets();
echo $filamentModelWidgets->echoPhrase('Hello, Dinhdjj!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [dinhdjj](https://github.com/dinhdjj)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
