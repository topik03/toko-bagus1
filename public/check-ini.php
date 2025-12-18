<?php
echo "Loaded php.ini: " . php_ini_loaded_file() . "<br><br>";

$settings = [
    'upload_max_filesize',
    'post_max_size',
    'max_file_uploads',
    'file_uploads',
    'memory_limit',
    'max_execution_time'
];

foreach ($settings as $setting) {
    echo $setting . ": " . ini_get($setting) . "<br>";
}
?>
