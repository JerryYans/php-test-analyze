<?php

function pf_require_class($class){
	  global $cached_files;
    $f = $prefix . "/" . $file;
    if (in_array($f,$cached_files)) {
        return true;
    } else {
        $cached_files[] = $f;
    }
    return false;
}

function pf_require_file($file){
}