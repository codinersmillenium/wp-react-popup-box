<?php

// generate secret key -> use composer (somposer secret-key)
$config_file = __DIR__ . '/app.php';
$secret_key = bin2hex(random_bytes(32));
if (file_exists($config_file)) {
    $config = include $config_file;
} else {
    $config = [];
}
$config['jwt_secret'] = $secret_key;
$config_content = "<?php\nreturn " . var_export($config, true) . ";\n";
file_put_contents($config_file, $config_content);
echo "The secret key is successfully generated and stored in config/app.php\n";