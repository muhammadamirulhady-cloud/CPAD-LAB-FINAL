<?php
use App\Auth\JwtService;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Database;
use App\Middleware\AuthMiddleware;
use App\Middleware\RateLimit;
use App\Repositories\BookRepository;
use App\Repositories\UserRepository;
use Slim\App;

return function (App $app): void {
    $pdo = Database::get();
    $jwt = new JwtService();
    $auth = new AuthMiddleware($jwt);

    $bookCtrl = new BookController(new BookRepository($pdo), $pdo);
    $authCtrl = new AuthController(new UserRepository($pdo), $jwt, $pdo);

    // --- ADD THIS GLOBAL OPTIONS WILDCARD HANDLER FOR CORS PREFLIGHTS ---
    $app->options('/{routes:.+}', function ($request, $response) {
        return $response; // Just pass through, Cors middleware will catch it and attach headers
    });
    // ---------------------------------------------------------------------

    // Apply Rate Limiter specifically on login as required by the manual
    $loginMw = new RateLimit(
        (int)($_ENV['LOGIN_RATE_LIMIT'] ?? 5),
        (int)($_ENV['LOGIN_WINDOW_SECONDS'] ?? 60),
        'login'
    );

    $app->post('/auth/register', [$authCtrl, 'register']);
    $app->post('/auth/login', [$authCtrl, 'login'])->add($loginMw);
    
    $app->get('/api/books', [$bookCtrl, 'index']);
    $app->get('/api/books/{id}', [$bookCtrl, 'show']);

    $app->group('/api/books', function ($g) use ($bookCtrl) {
        $g->post('', [$bookCtrl, 'create']);
        $g->put('/{id}', [$bookCtrl, 'update']);
        $g->delete('/{id}', [$bookCtrl, 'delete']);
    })->add($auth);
};