<?php

abstract class psftd_file {
    protected $dir = '';
    protected $url_func = '';
    protected $file_extension;
    protected $filelist = array();
    protected $format = '';
    protected $output = '';

    function __construct( $theme = 'parent' ) {
        $this->set_dir( $theme );
        $this->get_filelist();
        $this->set_output();
    }

    function set_dir( $theme ) {
        if( $theme == 'child' ) {
            $this->dir = get_stylesheet_directory() ;
            $this->url_func = 'get_stylesheet_directory_uri()' ;
        } else {
            $this->dir = get_template_directory() ;
            $this->url_func = 'get_template_directory_uri()' ;
        }
    }

    function get_filelist() {
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dir), RecursiveIteratorIterator::LEAVES_ONLY);

        foreach($objects as $name => $object){
            if ( $object->getExtension() == $this->file_extension ) {
                //$file_basename = $object->getBasename( '.' . $this->file_extension );
                $fullpath = $object->getPathname();
                $relativepath = str_replace($this->dir, '', $fullpath);
                $filename = str_replace('/', '-', $relativepath);
                $this->filelist[$filename] = $relativepath;
            }
        }
    }
    
    function set_output() {
        foreach($this->filelist as $basename => $relativepath) {
                $this->output .= sprintf($this->format, $basename, $relativepath);
        }
    }

    function get_filelist_length() {
        return count($this->filelist);
    }
    
    function dir_output() {
        return "\$dir = $this->url_func ;\n";
    }
    function output() {
        return $this->output;
    }

}

class psftd_get_js extends psftd_file {
    protected $file_extension = 'js';
    protected $format = "wp_enqueue_script( '%s', \$dir.'%s', array() );\n";
}

class psftd_get_css extends psftd_file {
    protected $file_extension = 'css';
    protected $format = "wp_enqueue_style( '%s', \$dir.'%s', array() );\n";
}
