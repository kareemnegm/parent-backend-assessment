<?php

// if (!function_exists('load_json')) {
//     function load_json($file)
//     {
//         $file = base_path('json_files' . DIRECTORY_SEPARATOR . $file  . '.json');
//         $json = file_get_contents($file);
//         $json = json_decode($json, true);

//         return $json;
//     }
// }


if (!function_exists('load_json')) {
    function load_json($directory)
    {
        $jsonFiles = [];
        $directory = base_path($directory);

        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                    $jsonFile = $directory . DIRECTORY_SEPARATOR . $file;
                    $json = file_get_contents($jsonFile);
                    $jsonData = json_decode($json);

                    if ($jsonData !== null) {
                        $filename = pathinfo($file, PATHINFO_FILENAME);
                        $jsonFiles[$filename] = $jsonData; // Store data without additional array
                    }
                }
            }
        }

        return $jsonFiles;
    }
}

