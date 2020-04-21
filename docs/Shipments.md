# Shipments

Create or retrieve shipments.

> All code examples assume the `test` environment is used.

> Please see the [Installation documentation](/docs/Installation.md) for information on how to set up the project.

## Create new shipment

```
cp config/test/Shipment.php.dist config/test/Shipment.php
```

Optionally edit the file `config/test/Shipment.php` adjusting the shipment data as needed.

> Please refer to the [ParcelValue API](https://github.com/parcelvalue/api) documentation for additional information on the formatting of the data.

Run the code:

```sh
bin/cli Shipments/create
```

## Retrieve an existing shipment

```sh
bin/cli Shipments/retrieve <shipmentId>
```

## Download shipment documents

```sh
bin/cli Shipments/downloadDocuments <shipmentId>
```
