<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

config(['app.debug' => true]);

try {
    $controller = $app->make(App\Http\Controllers\PublicSite\PageController::class);
    $response = $controller->show('about/who-we-are');
    echo 'controller ok status-ish: '.get_class($response)."\n";
    echo 'content length: '.strlen($response->render())."\n";
} catch (Throwable $e) {
    echo 'CONTROLLER ERROR: '.$e->getMessage()."\n";
    echo $e->getFile().':'.$e->getLine()."\n\n";
    echo $e->getTraceAsString()."\n";
}
