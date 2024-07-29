<?php

if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    (require __DIR__ . '/../config/bootstrap.php')->run();
} else {
    require_once __DIR__ . '/../src/Support/TemplateEngine.php';

    $app = $_SERVER['HTTP_HOST'] == 'localhost' ? '../app/' : '';
    $template = new App\Support\TemplateEngine();
    $template->setTemplatePath('../templates/system/');
    $message = $template->renderValueMap('index', [
        'title' => 'WebApp',
        'version' => time(),
        'appcss' => $app . 'app.css',
        'appjs' => $app . 'app.js',
    ]);

    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Expires: 0');

    echo $message;
}
