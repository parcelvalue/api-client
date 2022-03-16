# Installation

## Install

```bash
# clone project
git clone https://github.com/parcelvalue/api-client.git
# open project directory
cd api-client
# install dependencies
composer update
```

## Configuration

Create configuration file from the example provided:

```bash
cp config/.env.ini.dist config/.env.ini
```

Edit `config/.env.ini` configuration file, setting the following values:

| Name                 | Description                                          | Type    | Format |
|----------------------|------------------------------------------------------|---------|--------|
| `app_api_url`        | The ParcelValue API URL, with trailing slash (¹)     | string  |        |
| `app_api_version`    | The ParcelValue API version your client is targeting | string  |  `v3`  |
| `app_api_client_id`  | Your ParcelValue Client Id                           | integer |        |
| `app_api_client_key` | Your Client key                                      | string  |        |
| `app_api_server_key` | Your API server key.                                 | string  |        |


(¹) Current value for all environments: `https://api.parcelvalue.eu/`

## Validate structure

```bash
composer check:structure
```

## Check coding standards (PHP_CodeSniffer)

```bash
composer check
```

## Analyze PHP code (PHPStan)

```bash
composer s:8
```

## Run unit tests

```bash
composer test
```

### Run unit tests (testdox output)

```bash
composer test:d
```

## Run all of the above checks

```bash
composer all
```
