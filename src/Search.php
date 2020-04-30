<?php
namespace Searcher;
use Symfony\Component\Yaml\Yaml;
use Searcher\Limitations\Limitations;

class Search {

    private $file;
    private $settings;

    public function setFile($path_to_file) {
        if( !file_exists($path_to_file) ) {
            throw new \InvalidArgumentException('FILE[not_exist]');
        }

        if(!$this->settings['file']){
            $this->file = fopen($path_to_file, 'r');
            return true;
        }

        $file_limitations = new Limitations();
        $file_limitations->file_settings = $this->settings['file'];

        $fs = $file_limitations->checkFileSize($path_to_file);
        $fmt = $file_limitations->checkMimeType($path_to_file);
    }

    public function addSettings($patch_to_settings_file) {
        if( !file_exists($patch_to_settings_file) ) {
            throw new \InvalidArgumentException('FILE[not_exist]');
        }

        $this->settings = Yaml::parseFile($patch_to_settings_file);
        return true;
    }

    public static function getFileType($path_to_file){
        preg_match('/\.(\w+)$/', $path_to_file, $match);
        if(!$match){
            return false;
        }
        return $match[1];
    }

}