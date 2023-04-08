# Markets-Dashboard
## Installation
> Clone repository into your packages' folder:
```bash
git clone https://github.com/breuermarcel/markets-dashboard.git
```

> Define your package repositories: dev
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

> Define your package repositories: live/latest version
```json
"repositories": [
    {
        "type":"package",
        "package": {
            "name": "breuermarcel/markets-dashboard",
            "version":"main",
            "source": {
                "url": "https://github.com/breuermarcel/markets-dashboard.git",
                "type": "git",
                "reference":"main"
            }
        }
    }
],
```

> Require package:
```bash
composer require breuermarcel/markets-dashboard
```

> Migrate database:
```bash
php artisan migrate
```

## Todos 
- [ ] remove api logic
- [ ] composer.json: add requirements 
- [ ] add translations
- [ ] rename to markets-dashboard
- [ ] use npm and not cdn
