<?php

namespace Tests\TestFiles\Ftp;

use PHPUnit\Framework\TestCase;
use Searcher\FTP\Ftp;
use Symfony\Component\Yaml\Yaml;

class SettingsTest extends TestCase {

    private $settings_connect_ftp = [
    ];

    function testSuccessGetFileFtp() {
        $file_path = Ftp::getFilePath($this->settings_connect_ftp);
        $this->assertEquals(empty($file_path), false);
    }

    function testNoLoginFtp() {
        $settings = $this->settings_connect_ftp;
        $settings['login'] = '';
        $err_msg = '';
        try {
            $file_path = Ftp::getFilePath($settings);
        } catch( \Exception $exception ) {
            $err_msg = $exception->getMessage();
        }
        $this->assertEquals($err_msg, 'FTP_SETTING_FIELD[login]');
    }
}