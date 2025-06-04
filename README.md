# Shopware 6 Development Environment Helper

Simple plugin to load images from a live server instead of downloading them locally.

## Installation

```Bash
composer require --dev subcore/shopware-dev-env-helper

symfony console plugin:refresh

symfony console plugin:install SubcoreSwDevEnvHelper --activate
```

## Konfiguration

Simply go to the plugin configuration and set the local domain (eg `myshop.wip`) and the live domain (eg `myshop.com`).