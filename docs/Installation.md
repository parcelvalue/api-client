# Installation

The `test` environment is used in this example.

## Install
```
git clone https://github.com/parcelvalue/api-client.git
cd api-client
cp .env.dist .env
echo test > .env

composer update
```

## Configuration
```
cp config/dev/App.php.dist config/test/App.php
```
Edit `App.php` configuration file, setting the following values:

| Name            | Description                                          | Type    | Format |
|-----------------|------------------------------------------------------|---------|--------|
| `api/url`       | The ParcelValue API URL, with trailing slash (¹)     | string  |        |
| `api/version`   | The ParcelValue API version your client is targeting | string  |  `v1`  |
| `api/clientId`  | Your ParcelValue Client Id                           | integer |        |
| `api/clientKey` | Your Client key                                      | string  |        |
| `api/serverKey` | Your API server key.                                 | string  |        |


(¹) Current value for all environments: `https://api.parcelvalue.eu/`

## Validate structure
```
composer check:structure
```

## Check coding standards (PHP_CodeSniffer)
```
composer check
```

## Analyze PHP code (PHPStan)
```
composer s:7
```

## Run unit tests
```
composer test
```

### Run unit tests (testdox output)
```
composer test:d
```

## Run all checks
```
composer all
```
