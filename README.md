# Rameera Low Stock Notification

`Rameera_LowStockNotification` sends a low-stock summary email to an admin recipient on a scheduled cron interval.

## Features

- Detects low stock products from Magento reports inventory data.
- Sends an email summary with product name, SKU, and quantity.
- Configurable enable/disable toggle in admin.
- Configurable recipient email and email template.
- Compatible with modern Magento 2.4 installations.

## Compatibility

- Magento Open Source / Adobe Commerce `2.4.4` and later in the `2.4.x` line.
- PHP `8.1`, `8.2`, `8.3`, and `8.4`.

## Installation

### Install from app/code

Place the module under:

`app/code/Rameera/LowStockNotification`

Then run:

```bash
php bin/magento module:enable Rameera_LowStockNotification
php bin/magento setup:upgrade
php bin/magento cache:flush
```

### Install with Composer (VCS repository)

```bash
composer require arjundhi/module-low-stock-notification
php bin/magento module:enable Rameera_LowStockNotification
php bin/magento setup:upgrade
php bin/magento cache:flush
```

## Configuration

In admin, go to:

`Stores > Configuration > Catalog > Inventory > Stock Notification Email Setting`

Configure:

- **Enable**
- **Email Template**
- **Admin Email Address**

## Cron

- Cron group: `stock_notification_cron_group`
- Job: `low_stock_notification_cronjob`
- Schedule: every minute (`*/1 * * * *`)

You can adjust the schedule in `etc/crontab.xml`.

## Module Structure

- `Cron/Notification.php` builds low-stock data and triggers notification emails.
- `Helper/Email.php` handles transport/template email sending.
- `etc/adminhtml/system.xml` defines admin configuration fields.
- `etc/crontab.xml` and `etc/cron_groups.xml` define cron scheduling.
- `etc/email_templates.xml` registers the transactional email template.
- `view/frontend/email/stock_notification.html` is the default email template.

## CI Matrix

This repository includes a GitHub Actions workflow at `.github/workflows/ci.yml`.

Validation runs on:

- PHP `8.2`
- PHP `8.4`

It validates Composer metadata, PHP syntax, and XML well-formedness.

### Install commands by environment

Stable production install:

```bash
composer require arjundhi/module-low-stock-notification:^1.0
```

Staging/dev install (before first stable tag is visible):

```bash
composer require arjundhi/module-low-stock-notification:"dev-main@dev"
```

## License

This project is licensed under the MIT License. See `LICENSE` for details.
