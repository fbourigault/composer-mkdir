Composer mkdir
==============

This tool allows you to create directories when a composer install or update is run.

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
