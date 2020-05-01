<?php
namespace Searcher\Limitations;

use Searcher\Search;

class Limitations {

    /**
     * Источник данных настроек
     * @var $file_settings
     */
    public $file_settings;

    /**
     * Проверяем размер файла.
     * @param $path_to_file - путь до файла с настройками
     * @return boolean
     */
    public function checkFileSize($path_to_file) {
        if( isset($this->file_settings['max_size_kb']) ) {
            $file_size_kb = filesize($path_to_file) / 1024;

            if( $file_size_kb > $this->file_settings['max_size_kb'] ) {
                throw new \InvalidArgumentException('FILE[size_error]');
            }
        }
        return true;
    }

    /**
     * /**
     * Проверяем расширение файла.
     * @param $path_to_file - путь до файла с настройками
     * @return boolean
     */
    public function checkMimeType($path_to_file) {
        $ft = Search::getFileType($path_to_file);

        if( isset($this->file_settings['mime_type']) ) {
            foreach($this->file_settings['mime_type'] as $key => $value) {
                if( $value && $key == $ft ) {
                    return true;
                }
            }
            throw new \InvalidArgumentException('FILE[type_error]');
        }
        return true;
    }
}