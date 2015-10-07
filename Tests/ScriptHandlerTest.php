<?php
namespace Fbourigault\ComposerMkdir\Tests;

use Fbourigault\ComposerMkdir\ScriptHandler;
use Composer\Util\Filesystem;

class ScriptHandlerTest extends \PHPUnit_Framework_TestCase
{

    private $tmp;

    private $cwd;

    private $umask;

    public function setUp()
    {
        $this->cwd = getcwd();
        $this->tmp = sys_get_temp_dir() . '/' . uniqid('phpunit-', true);
        $this->umask = umask(0);
        mkdir($this->tmp);
        chdir($this->tmp);
    }

    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->removeDirectory($this->tmp);
        chdir($this->cwd);
        umask($this->umask);

        unset($this->umask);
        unset($this->tmp);
        unset($this->cwd);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMkdirsMissingConfig()
    {
        $message = 'The mkdir handler needs to be configured through the extra.fbourigault-composer-mkdir setting.';
        $this->setExpectedException('InvalidArgumentException', $message);
        $event = $this->getEventMock(array());
        ScriptHandler::mkdirs($event);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMkdirsConfigNotArray()
    {
        $message = 'The extra.fbourigault-composer-mkdir setting must be an array.';
        $this->setExpectedException('InvalidArgumentException', $message);
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => "not-an-array"
        ));
        ScriptHandler::mkdirs($event);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMkdirsLegacy()
    {
        $message = 'Since 2.0, mode is no longer supported. See UPGRADE-2.0.md for further details.';
        $this->setExpectedException('InvalidArgumentException', $message);
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                "path" => "var/log",
                "mode" => 0770
            )
        ));
        ScriptHandler::mkdirs($event);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMkdirsString()
    {
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                "var"
            )
        ));
        ScriptHandler::mkdirs($event);
        $this->assertTrue($this->isDir("var"));
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMkdirsParents()
    {
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                "var/log",
            )
        ));
        ScriptHandler::mkdirs($event);

        $this->assertTrue($this->isDir("var/log"));
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMkdirsMultiple()
    {
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                "log",
                "var/log",
            )
        ));
        ScriptHandler::mkdirs($event);

        $this->assertTrue($this->isDir("var/log"));
        $this->assertTrue($this->isDir("var"));
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMkdirsExists()
    {
        touch($this->tmp . '/var');
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                "var"
            )
        ));
        ScriptHandler::mkdirs($event);
        $this->assertFalse($this->isDir("var"));
    }

    private function getEventMock(array $extra)
    {
        $package = $this->getMock('Composer\Package\RootPackageInterface');
        $package->expects($this->once())
            ->method('getExtra')
            ->willReturn($extra);

        $composer = $this->getMockBuilder('Composer\Composer')
            ->disableOriginalConstructor()
            ->getMock();
        $composer->expects($this->once())
            ->method('getPackage')
            ->willReturn($package);

        $event = $this->getMockBuilder('Composer\Script\Event')
            ->disableOriginalConstructor()
            ->getMock();
        $event->expects($this->once())
            ->method('getComposer')
            ->willReturn($composer);

        return $event;
    }

    private function isDir($dir)
    {
        return is_dir($this->tmp . '/' . $dir);
    }
}
