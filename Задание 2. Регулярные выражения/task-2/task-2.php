<?php
    $path = "path/to/directory";
    $php_script = $path. "/INTARO_2.php";

    $patern = "/http\:\/\/asozd\.duma\.gov\.ru\/main\.nsf\/\(Spravka\)\?OpenAgent\&RN\=(\d+\-\d+)\&\d+/";

    foreach (glob("$path/*.txt") as $data_file) {
        $content_file = file_get_contents($data_file);
        $updated_content = preg_replace_callback($patern, "replace", $content_file);
        file_put_contents($data_file, $updated_content);
    }

    function replace($matches) {
        $link = "http://sozd.parlament.gov.ru/bill/";
        $new_link = $link . $matches[1];
        return $new_link;
    }
?>