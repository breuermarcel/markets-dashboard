# Finance-Dashboard

## Installation

Clone repository into your packages' folder:
```bash
git clone https://github.com/breuermarcel/finance-dashboard.git
```

Define your package repositories: dev
```json
"repositories": [
    {
        "type":"path",
        "url": "./packages/*",
        "options": {
            "symlink": true
        }
    }
],
```

Define your package repositories: live/latest version
```json
"repositories": [
    {
        "type":"package",
        "package": {
            "name": "breuermarcel/finance-dashboard",
            "version":"master",
            "source": {
                "url": "https://github.com/breuermarcel/finance-dashboard.git",
                "type": "git",
                "reference":"master"
            }
        }
    }
],
```

Require package:
```bash
composer require breuermarcel/finance-dashboard
```

Publish assets:
```bash
php artisan vendor:publish --provider="Breuermarcel\FinanceDashboard\FinanceDashboardServiceProvider" --tag="assets"
```

Migrate database:
```bash
php artisan migrate
```
