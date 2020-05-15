<?php
    // Build tool for the ChilliBits website

    // Constants
    $languages = ["en"];
    $file_exceptions = ["build.php", "strings.json", "urls.json"];
    $strings_file = "strings.json";
    $urls_file = "urls.json";
    
    // Loop through every language
    foreach($languages as $lang) {
        // If directory already exists, clear it
        if(file_exists("../".$lang."_test") && is_dir("../$lang")) clearContentsOfDir("../$lang");
        // Copy all necessary files to the directory
        recursiveCopy(".", "../".$lang."_test", $file_exceptions);
        // Replace strings with the translation
        foreach(rglob("../".$lang."_test/*.html") as $file) {
            replaceUrls($file, $lang);
            replaceStrings($file, $lang);
        }
        foreach(rglob("../".$lang."_test/*.php") as $file) {
            replaceUrls($file, $lang);
            replaceStrings($file, $lang);
        }
    }
    exit;

    // --------------------- Functions ----------------------

    function clearContentsOfDir($path) {
        foreach(glob("$path/*") as $file) {
            if(is_dir($file)) clearContentsOfDir($file);
            unlink($file);
        }
        return;
    }

    function recursiveCopy($src, $dst, $file_exceptions) {
        $dir = opendir($src);
        @mkdir($dst);
        while($file = readdir($dir)) {
            if ($file != '.' && $file != '..' && !in_array($file, $file_exceptions)) {
                if(is_dir($src.'/'.$file)) {
                    recursiveCopy($src.'/'.$file, $dst.'/'.$file, $file_exceptions);
                } else {
                    copy($src.'/'.$file, $dst.'/'.$file);
                }
            }
        }
        closedir($dir);
    }

    function replaceStrings($file, $lang) {
        global $strings_file;
    
        // Decode string.json file
        $content = file_get_contents($strings_file);
        $json = json_decode($content, true);
        $json = $json[$lang];

        // Load current file
        $html_code = file_get_contents("../".$lang."_test/$file");

        // Replace html lang tag
        $html_code = str_replace('lang="es"', 'lang="'.$lang.'"', $html_code);

        // Replace lang placeholders
        $html_code = str_replace('es', $lang, $html_code);

        // Replace string occurences
        foreach($json as $key => $value) $html_code = str_replace("str_$key", $value, $html_code);

        // Save current file
        file_put_contents($file, $html_code);
    }

    function replaceUrls($file, $lang) {
        global $urls_file;
    
        // Decode string.json file
        $content = file_get_contents($urls_file);
        $json = json_decode($content, true);
        $json = $json[$lang];

        // Load current file
        $html_code = file_get_contents("../$lang/$file");

        // Replace string occurences
        foreach($json as $key => $value) $html_code = str_replace("url_$key", $value, $html_code);

        // Save current file
        file_put_contents($file, $html_code);
    }

    function rglob($pattern, $flags = 0) {
        $files = glob($pattern, $flags); 
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
        }
        return $files;
    }
?>