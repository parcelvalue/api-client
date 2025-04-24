<?php

declare(strict_types=1);

use WebServCo\Framework\Application;
use WebServCo\Framework\Exceptions\ApplicationException;

require __DIR__ . '/../vendor/autoload.php';

try {
    // Initialize and run the app.
    $app = new Application(
        // publicPath, web accessible project directory path
        __DIR__,
        // projectPath, parent of publicPath if not set
        null,
        // projectNamespace, "Project" if not set
        'ParcelValue\ApiClient',
    );
    $app->run();
} catch (ApplicationException $e) {
    echo $e->getMessage();
    exit;
}
