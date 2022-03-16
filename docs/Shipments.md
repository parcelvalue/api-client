# Shipments

Note: this project only handles the simplified “one step” endpoint (create and confirm a new shipment using the `service` option).

Create or retrieve shipments.

Please see the [Installation documentation](Installation.md) for information on how to set up the project.

## Create new shipment

```bash
cp config/Shipment.php.dist config/Shipment.php
```

Optionally edit the file `config/Shipment.php` adjusting the shipment data as needed.

Please refer to the [ParcelValue API](https://parcelvalue.github.io/api/) documentation for additional information on the formatting of the data.

Run the code:

```bash
bin/cli Shipments/create
```

## Retrieve an existing shipment

```bash
bin/cli Shipments/retrieve <shipmentId>
```

## Download shipment documents

```bash
bin/cli Shipments/downloadDocuments <shipmentId>
```

## Get tracking info

```bash
bin/cli Shipments/getTrackingInfo <shipmentId>
```
