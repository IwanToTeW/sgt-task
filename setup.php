<?php

$commands = [
    'docker run --rm -v $(pwd):/var/www/html -w /var/www/html laravelsail/php83-composer composer install',
    './vendor/bin/sail up -d',
    './vendor/bin/sail php artisan migrate:fresh --seed',
    './vendor/bin/sail npm i',
    './vendor/bin/sail npm run build',
    './vendor/bin/sail php artisan test',
];

foreach ($commands as $command) {
    echo "Running: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo $output . "\n";
}

?>
