<?php
    $dir = readline("Проверяем [A/B/C/D]: ");
    $path = __DIR__ . "/" . $dir;
    $php_script = $path . "/" . $dir . ".php";

    foreach (glob("$path/*.dat") as $dat_file) {
        $ans_file = str_replace(".dat", ".ans", $dat_file);

        $output = shell_exec("php \"$php_script\" < \"$dat_file\"");
        $result = file_get_contents($ans_file);
        
        $output = preg_replace('/\s+$/m', '', $output);
        $result = preg_replace('/\s+$/m', '', $result);
        
        if ($dir != "C") {
            if ($output == $result) {
                echo basename($dat_file) . " OK\n";
            } else {
                echo basename($dat_file) . " FAIL\n";
            }
        } else {
            $output_exp = explode("\n", $output);
            $result_exp = explode("\n", $result);

            $flag = true;

            for ($i = 0; $i < count($output_exp); $i += 1) {
                $output_exp_exp = explode(" ", $output_exp[$i]);
                $result_exp_exp = explode(" ", $result_exp[$i]);

                if (abs($output_exp_exp[1] - $result_exp_exp[1]) > 0.0013) {
                    $flag = false;
                }
            }

            if ($flag) {
                echo basename($dat_file) . " OK\n";
            } else {
                echo basename($dat_file) . " FAIL\n";
            }
        }
    }
?>