Composer mkdir
==============

This tool allows you to create directories when a composer install or update is run.

[![Build Status](https://travis-ci.org/fbourigault/composer-mkdir.svg?branch=master)](https://travis-ci.org/fbourigault/composer-mkdir)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d3ab29c2-e77b-4749-bfcd-b1a2bb02b6d8/mini.png)](https://insight.sensiolabs.com/projects/d3ab29c2-e77b-4749-bfcd-b1a2bb02b6d8)
[![Latest Stable Version](https://poser.pugx.org/fbourigault/composer-mkdir/v/stable.svg)](https://packagist.org/packages/fbourigault/composer-mkdir)
[![License](https://poser.pugx.org/fbourigault/composer-mkdir/license.svg)](https://packagist.org/packages/fbourigault/composer-mkdir)

Usage
-----

```json
{
    "require": {
        "fbourigault/composer-mkdir": "^2.0"
    },
    "scripts": {
        "post-install-cmd": [
            "Fbourigault\\ComposerMkdir\\ScriptHandler::mkdirs"
        ],
        "post-update-cmd": [
            "Fbourigault\\ComposerMkdir\\ScriptHandler::mkdirs"
        ]
    },
    "extra": {
        "fbourigault-composer-mkdir": [
            "var/cache",
            "var/log"
        ]
    }
}
```

Parent directories are created if required.
