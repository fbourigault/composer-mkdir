<?php
namespace Fbourigault\ComposerMkdir;

use Composer\Script\Event;
use InvalidArgumentException;

class ScriptHandler
{

    public static function mkdirs(Event $event)
    {
        $extras = $event->getComposer()
            ->getPackage()
            ->getExtra();

        if (! isset($extras['fbourigault-composer-mkdir'])) {
            $message = 'The mkdir handler needs to be configured through the extra.fbourigault-composer-mkdir setting.';
            throw new InvalidArgumentException($message);
        }

        if (! is_array($extras['fbourigault-composer-mkdir'])) {
            $message = 'The extra.fbourigault-composer-mkdir setting must be an array.';
            throw new InvalidArgumentException($message);
        }

        /* Since 2.0, mode is no longer supported */
        $legacy = array_filter($extras['fbourigault-composer-mkdir'], function ($directory) {
            return !is_string($directory);
        });
        if (!empty($legacy)) {
            $message = 'Since 2.0, mode is no longer supported. See UPGRADE-2.0.md for further details.';
            throw new InvalidArgumentException($message);
        }



        /* Remove existing directories from creation list */
        $directories = array_filter($extras['fbourigault-composer-mkdir'], function ($directory) {
            return !file_exists($directory);
        });

        foreach ($directories as $directory) {
            mkdir($directory, 0777, true);
        }
    }
}
