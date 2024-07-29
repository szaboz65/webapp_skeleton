<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Home??? not used!!!
    $app->get('/public/', \App\Action\Home\HomeAction::class)->setName('home');

    // dbupdate
    $app->get('/public/api/dbupdate', \App\Action\DBUpdate\DBUpdateAction::class)->setName('dbupdate');

    // session
    $app->post('/public/api/login', \App\Action\Session\LoginAction::class)->setName('login');
    $app->post('/public/api/forgot', \App\Action\Session\ForgotPasswordAction::class)->setName('forgot');
    $app->post('/public/api/pwreset', \App\Action\Session\ResetPasswordAction::class)->setName('pwreset');

    // Password protected area
    $app->group(
        '/public/api',
        function (RouteCollectorProxy $app) {
            // usertype actions
            $app->get('/roles', \App\Action\User\RoleFindAction::class);
            $app->get('/roleitems', \App\Action\User\RoleItemsAction::class);
            $app->get('/roleitems/{roles}', \App\Action\User\RoleItemsAction::class);
            $app->get('/usertypes', \App\Action\User\UsertypeFindAction::class);
            $app->get('/usertypeitems', \App\Action\User\UsertypeItemsAction::class);
            $app->get('/usertype/{utypeid}', \App\Action\User\UsertypeReadAction::class);
            $app->put('/usertype/{utypeid}', \App\Action\User\UsertypeUpdateAction::class);
            // user actions
            $app->get('/users', \App\Action\User\UserFindAction::class);
            $app->get('/user/{user_id}', \App\Action\User\UserReadAction::class);
            $app->post('/user', \App\Action\User\UserCreateAction::class);
            $app->put('/user/{user_id}', \App\Action\User\UserUpdateAction::class);
            // $app->delete('/user/{user_id}', \App\Action\User\UserDeleteAction::class);
            $app->get('/user/{user_id}/pref', \App\Action\User\UserprefReadAction::class);
            $app->put('/user/{user_id}/pref', \App\Action\User\UserprefUpdateAction::class);
            $app->get('/user/{user_id}/photo', \App\Action\User\PhotoReadAction::class);
            $app->put('/user/{user_id}/photo', \App\Action\User\PhotoUpdateAction::class);
            $app->get('/user/{user_id}/roleitems', \App\Action\User\UserRoleItemsAction::class);
            // session actions
            $app->get('/session', \App\Action\Session\SessionReadAction::class);
            $app->get('/logout', \App\Action\Session\LogoutAction::class);
        }
    );
};
