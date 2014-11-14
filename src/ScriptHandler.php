<?php
namespace Fbourigault\ComposerMkdir;

use Composer\Script\Event;

class ScriptHandler
{

    public static function mkdirs(Event $event)
    {
        $extras = $event->getComposer()
            ->getPackage()
            ->getExtra();

        if (! isset($extras['fbourigault-composer-mkdir'])) {
            $message = 'The mkdir handler needs to be configured through the extra.fbourigault-composer-mkdir setting.';
            throw new \InvalidArgumentException($message);
        }

        if (! is_array($extras['fbourigault-composer-mkdir'])) {
            $message = 'The extra.fbourigault-composer-mkdir setting must be an array.';
            throw new \InvalidArgumentException($message);
        }

        foreach ($extras['fbourigault-composer-mkdir'] as $dir) {
            self::mkdir($dir);
        }
    }

    public static function mkdir($dir)
    {
        $path = $dir;
        $mode = 0777;
        if (is_array($dir)) {
            list ($path, $mode) = self::parsePathAndMode($dir);
        }

        if(file_exists($path)) {
            return;
        }
        mkdir($path, $mode, true);
    }

    public static function parsePathAndMode($dir)
    {
        if (! isset($dir['path'])) {
            $message = 'Directories provided as array must have the path key.';
            throw new \InvalidArgumentException($message);
        }

        if (! isset($dir['mode'])) {
            $message = 'Directories provided as array must have the mode key.';
            throw new \InvalidArgumentException($message);
        }

        return array(
            $dir['path'],
            $dir['mode']
        );
    }
}
