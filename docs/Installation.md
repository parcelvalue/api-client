# Installation

## Install (test environment)
```
git clone https://github.com/parcelvalue/api-client.git
cd api-client
cp .env.dist .env
echo test > .env
composer update
```

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
