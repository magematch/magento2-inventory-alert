# Inventory Alert for Magento 2

> Free, open-source Magento 2 extension  
> by **Arjun Dhiman** — 
> [Adobe Commerce Certified Master](https://magematch.com/developers/arjun-dhiman)  
> Part of the [MageMatch](https://magematch.com) 
> developer ecosystem

`MageMatch_LowStockNotification` monitors inventory thresholds and delivers a scheduled low-stock digest email to the configured admin recipient.

## Features

- Scans inventory using Magento's built-in reports low-stock collection.
- Delivers a digest email listing each at-risk product with name, SKU, and current quantity.
- Enable/disable toggle and recipient address configurable in admin.
- Swappable transactional email template.
- Zero legacy code — built on `DataPatchInterface`, constructor promotion, and `declare(strict_types=1)`.
- Compatible with Magento 2.4.4+ and PHP 8.1 through 8.4.

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
composer require magematch/magento2-inventory-alert:^1.0
```

Staging/dev install (before first stable tag is visible):

```bash
composer require magematch/magento2-inventory-alert:"dev-main@dev"
```

---
## Installation
```bash
composer require magematch/magento2-inventory-alert
bin/magento module:enable MageMatch_InventoryAlert
bin/magento setup:upgrade
bin/magento cache:clean
```

## Compatibility
- Magento Open Source 2.4.x
- Adobe Commerce 2.4.x
- PHP 8.1, 8.2, 8.3

## Support & Custom Development
Need custom Magento development?  
Find vetted Adobe Commerce developers at  
**[magematch.com](https://magematch.com)**

## License
MIT License — free to use commercially
