<?php
namespace Searcher;
use Symfony\Component\Yaml\Yaml;
use Searcher\Limitations\Limitations;

class Search {

    /**
     * Источник данных
     * @var $file
     */
    private $file;
    /**
     * Настройки проекта
     * @var $settings
     */
    private $settings;

    /**
     * Возвращает ассоциативный массив, с позициями найденных слов
     * @param $path_to_file - источник данных
     * @param $needle - строка поиска
     * @param $path_to_settings - источник настроек
     * @return array
     */
    public function search($path_to_file, $needle, $path_to_settings = '') {
        try {
            $this->addSettings($path_to_settings);
            $this->setFile($path_to_file);
            $positions = $this->getPosition($needle);
            return $positions;
        } catch( \Exception $exception ) {
            echo $exception;
        }
    }

    /**
     * Возвращает расширение файла(exe,zip,txt ...)
     * @param $path_to_file - Путь до файла
     * @return mixed
     */
    public static function getFileType($path_to_file) {
        preg_match('/\.(\w+)$/', $path_to_file, $match);
        if( !$match ) {
            return false;
        }
        return $match[1];
    }

    /**
     * Добавляет источник данных с настройками в формате yaml
     * @param $path_to_settings_file - Путь до файла
     * @return boolean
     */
    private function addSettings($path_to_settings_file) {
        if( !$path_to_settings_file ) {
            return false;
        }

        if( !file_exists($path_to_settings_file) ) {
            throw new \InvalidArgumentException('FILE[not_exist]');
        }

        $this->settings = Yaml::parseFile($path_to_settings_file);
        return true;
    }

    /**
     * Добавляет источник данных
     * @param $path_to_file - Путь до файла
     * @return boolean
     */
    private function setFile($path_to_file) {
        if( !file_exists($path_to_file) ) {
            throw new \InvalidArgumentException('FILE[not_exist]');
        }

        if( !isset($this->settings['file']) ) {
            $this->file = fopen($path_to_file, 'r');
            return true;
        }

        $file_limitations = new Limitations();
        $file_limitations->file_settings = $this->settings['file'];

        $file_limitations->checkFileSize($path_to_file);
        $file_limitations->checkMimeType($path_to_file);
        return true;
    }

    /**
     * Ищет в файле текст
     * @param $needle - Слово для поиска
     * @return array - массив вхождений
     */
    private function getPosition($needle) {
        $string_counter = 1;
        $position = [];
        while( !feof($this->file) ) {
            $string = fgets($this->file);
            $find_word = mb_stripos($string, $needle);

            $count_word_in_string = mb_substr_count(mb_strtolower($string), mb_strtolower($needle));

            if( $count_word_in_string > 1 ) {
                $words_pos_array = [];
                $pos = 0;

                while( ($pos = mb_stripos($string, $needle, $pos + 1)) !== false ) {
                    $words_pos_array[] = $pos + 1;
                }
                $position[] = [
                    'string' => $string_counter,
                    'positions' => $words_pos_array
                ];
                $string_counter++;
                continue;
            }

            if( $find_word !== false ) {
                $position[] = [
                    'string' => $string_counter,
                    'positions' => $find_word + 1
                ];
            }
            $string_counter++;
        }
        return $position;
    }
}