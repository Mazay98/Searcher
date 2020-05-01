<?php
namespace Searcher\FTP;

use Searcher\Search;

class Ftp {

    /**
     * Возвращает путь к скаченному файлу
     * @param $settings_ftp - подключение к ftp
     * @return mixed
     */
    public static function getFilePath($settings_ftp) {
        self::issetFieldFtp($settings_ftp);

        $local_file = __DIR__ . '/Files/remotefile.txt';
        $file_size_start = filesize($local_file);
        $fp = fopen($local_file, 'w');

        $conn_id = ftp_connect($settings_ftp['host']);
        ftp_login($conn_id, $settings_ftp['login'], $settings_ftp['password']);

        $ret = ftp_nb_fget($conn_id, $fp, $settings_ftp['path'], FTP_BINARY);
        while( $ret == FTP_MOREDATA ) {
            $ret = ftp_nb_continue($conn_id);
        }
        if( $ret != FTP_FINISHED ) {
            throw new \InvalidArgumentException('FTP[error]');
        }

        fclose($fp);
        ftp_close($conn_id);

        $file_size_stop = filesize($local_file);

        if( ($file_size_start == $file_size_stop) && ($file_size_start && $file_size_stop) == 0 ) {
            return false;
        }

        return $local_file;
    }

    /**
     * Проверяет данные для входа
     * @param $settings_ftp - подключение к ftp
     * @return array
     */
    private static function issetFieldFtp($settings_ftp) {
        if( !isset($settings_ftp['host']) || empty($settings_ftp['host']) ) {
            throw new \InvalidArgumentException('FTP_SETTING_FIELD[host]');
        }
        if( !isset($settings_ftp['path']) || empty($settings_ftp['path']) ) {
            throw new \InvalidArgumentException('FTP_SETTING_FIELD[path]');
        }
        if( !isset($settings_ftp['login']) || empty($settings_ftp['login']) ) {
            throw new \InvalidArgumentException('FTP_SETTING_FIELD[login]');
        }
        if( !isset($settings_ftp['password']) || empty($settings_ftp['password']) ) {
            throw new \InvalidArgumentException('FTP_SETTING_FIELD[password]');
        }
        return true;
    }
}