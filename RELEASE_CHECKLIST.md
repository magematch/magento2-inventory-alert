# Release Checklist

Use this checklist before publishing a new GitHub release.

## Code Quality

- Confirm `composer.json` package metadata and repository URLs are current.
- Run `composer validate --no-check-lock --strict`.
- Run PHP lint on all module files.
- Confirm XML files are well-formed.

## Functional Checks

- Confirm stock notification settings are visible under Inventory configuration.
- Confirm cron executes and sends low stock emails when conditions are met.
- Confirm template output includes valid low stock table markup.

## Release Prep

- Update `CHANGELOG.md` with version/date changes.
- Commit all changes with a clear release message.
- Create and push annotated tag (example: `v1.0.0`).
- Publish GitHub release notes.
- Trigger Packagist update (manual refresh or webhook sync).

## Install Verification Matrix

- Verify stable install works: `composer require magematch/magento2-inventory-alert:^1.0`.
- Verify dev fallback works for pre-tag testing: `composer require magematch/magento2-inventory-alert:"dev-main@dev"`.
- Run validation against PHP `8.2` and `8.4` (same matrix as CI).
