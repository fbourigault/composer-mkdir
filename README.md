Composer mkdir
==============

This tool allows you to create directories when a composer install or update is run.

[![Build Status](https://travis-ci.org/fbourigault/composer-mkdir.svg?branch=master)](https://travis-ci.org/fbourigault/composer-mkdir)
[![Code Coverage](https://scrutinizer-ci.com/g/fbourigault/composer-mkdir/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fbourigault/composer-mkdir/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fbourigault/composer-mkdir/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fbourigault/composer-mkdir/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d3ab29c2-e77b-4749-bfcd-b1a2bb02b6d8/mini.png)](https://insight.sensiolabs.com/projects/d3ab29c2-e77b-4749-bfcd-b1a2bb02b6d8)
[![Latest Stable Version](https://poser.pugx.org/fbourigault/composer-mkdir/v/stable.svg)](https://packagist.org/packages/fbourigault/composer-mkdir)
[![License](https://poser.pugx.org/fbourigault/composer-mkdir/license.svg)](https://packagist.org/packages/fbourigault/composer-mkdir)

Usage
-----

```json
{
    "require": {
        "fbourigault/composer-mkdir": "~1.0"
    },
    "scripts": {
        "post-install-cmd": [
            "Fbourigault\\ComposerMkdir\\ScriptHandler::mkdirs
        ],
        "post-update-cmd": [
            "Fbourigault\\ComposerMkdir\\ScriptHandler::mkdirs
        ]
    },
    "extra": {
        "fbourigault-composer-mkdir": [
            "var/log",
            {
                "path": "tmp",
                "mode": "2770"
            }
        ]
    }
}
```

Parent directories are created if required.

You can provide directories as string :
```json
{
    "extra": {
        "fbourigault-composer-mkdir": [
            "var/log"
        ]
    }
}
```

or as object :
```json
{
    "extra": {
        "fbourigault-composer-mkdir": [
            {
                "path": "tmp",
                "mode": "2770"
            }
        ]
    }
}
```

The object form allows you to specify the mode.
