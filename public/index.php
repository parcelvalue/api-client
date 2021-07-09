<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

try {
    // Initialize and run the app.
    $app = new \WebServCo\Framework\Application(
        __DIR__, // publicPath, web accessible project directory path
        null, // projectPath, parent of publicPath if not set
        'ParcelValue\ApiClient', // projectNamespace, "Project" if not set
    );
    $app->run();
} catch (WebServCo\Framework\Exceptions\ApplicationException $e) {
    echo $e->getMessage();
    exit;
}
