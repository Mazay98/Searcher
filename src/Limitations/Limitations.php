<?php
namespace Searcher\Limitations;

use Searcher\Search;

class Limitations {

    public $file_settings;

    public function checkFileSize($path_to_file) {
        if( isset($this->file_settings['max_size_kb']) ) {
            $file_size_kb = filesize($path_to_file) / 1024;

            if( $file_size_kb > $this->file_settings['max_size_kb'] ) {
                throw new \InvalidArgumentException('FILE[size_error]');
            }
        }
        return true;
    }

    public function checkMimeType($path_to_file) {
        $ft = Search::getFileType($path_to_file);

        if( isset($this->file_settings['mime_type']) ) {
            foreach($this->file_settings['mime_type'] as $key=>$value) {
                if($value && $key == $ft){
                    return true;
                }
            }
            throw new \InvalidArgumentException('FILE[type_error]');
        }
        return true;
    }
}