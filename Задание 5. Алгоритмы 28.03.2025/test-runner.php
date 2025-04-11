<?php
    $dir = readline("Проверяем [A/B]: ");

    $path = __DIR__ . "/" . $dir;
    $php_script = $path . "/" . $dir . ".php";
    
    if ($dir == "A") {
        foreach (glob("$path/*.dat") as $dat_file) {
            $ans_file = str_replace(".dat", ".ans", $dat_file);
    
            $output = shell_exec("php \"$php_script\" < \"$dat_file\"");
            $result = file_get_contents($ans_file);
    
            if ($output === $result) {
                echo basename($dat_file) . " OK\n";
            } else {
                echo basename($dat_file) . " FAIL\n";
            }
        }
    } else {
        for ($i = 001; $i < 007; $i+=001) {
            $file = "00" . $i;
            $output = shell_exec("php \"$php_script\" $file");
            $result = file_get_contents(__DIR__ . "\B" . "\\" . $file  . "_result.xml");
            if ($output === $result) {
                echo $file . ".xml: OK\n";
            } else {
                echo $file . ".xml: FAIL\n";
            }
        }
    }
?>