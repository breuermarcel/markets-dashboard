# Finance-Dashboard

## Installation

You can install the package via composer:

```bash
composer require breuermarcel/finance-dashboard
```

Or install locally to "packages":
```json
"repositories": [
    {
        "type":"path",
        "url": "./packages/*"
    }
],
```
Require package in composer.json
```bash
composer require breuermarcel/finance-dashboard
```

Publish assets
```bash
php artisan vendor:publish --provider="Breuermarcel\FinanceDashboard\FinanceDashboardServiceProvider" --tag="assets"
```
