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
        mkdir($this->tmp, 0777, true);
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

    public function testMkdirsMissingConfig()
    {
        $message = 'The mkdir handler needs to be configured through the extra.fbourigault-composer-mkdir setting.';
        $this->setExpectedException('InvalidArgumentException', $message);
        $event = $this->getEventMock(array());
        ScriptHandler::mkdirs($event);
    }

    public function testMkdirsConfigNotArray()
    {
        $message = 'The extra.fbourigault-composer-mkdir setting must be an array.';
        $this->setExpectedException('InvalidArgumentException', $message);
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => "not-an-array"
        ));
        ScriptHandler::mkdirs($event);
    }

    public function testMkdirsString()
    {
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                "var"
            )
        ));
        ScriptHandler::mkdirs($event);
        $this->assertTrue($this->isDir("var"));
        $this->assertEquals(0777, $this->getMode("var"));
    }

    public function testMkdirsArrayMissingPath()
    {
        $message = 'Directories provided as array must have the path key.';
        $this->setExpectedException('InvalidArgumentException', $message);
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                array(
                    "mode" => 2770
                )
            )
        ));
        ScriptHandler::mkdirs($event);
    }

    public function testMkdirsArrayMissingMode()
    {
        $message = 'Directories provided as array must have the mode key.';
        $this->setExpectedException('InvalidArgumentException', $message);
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                array(
                    "path" => "tmp"
                )
            )
        ));
        ScriptHandler::mkdirs($event);
    }

    public function testMkdirsArray()
    {
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                array(
                    "path" => "tmp",
                    "mode" => 0770
                )
            )
        ));
        ScriptHandler::mkdirs($event);

        $this->assertTrue($this->isDir("tmp"));
        $this->assertEquals(0770, $this->getMode("tmp"));
    }

    public function testMkdirsParents()
    {
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                array(
                    "path" => "var/log",
                    "mode" => 0770
                )
            )
        ));
        ScriptHandler::mkdirs($event);

        $this->assertTrue($this->isDir("var/log"));
        $this->assertEquals(0770, $this->getMode("var"));
        $this->assertEquals(0770, $this->getMode("var/log"));
    }

    public function testMkdirsMultiple()
    {
        $event = $this->getEventMock(array(
            "fbourigault-composer-mkdir" => array(
                "log",
                array(
                    "path" => "var/log",
                    "mode" => 0770
                )
            )
        ));
        ScriptHandler::mkdirs($event);

        $this->assertTrue($this->isDir("var/log"));
        $this->assertTrue($this->isDir("var"));
    }

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

    private function getMode($dir)
    {
        return fileperms($this->tmp . '/' . $dir) & 0777;
    }
}
