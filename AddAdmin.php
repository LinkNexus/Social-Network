<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$superAdmin = App::getSuperAdmin();
$admin = App::getAdmin();
$user = App::getUser();

$admin->restrict();

if (!$admin->has('id') && !$superAdmin->has('answer') && !$user->has('value')){
    App::redirect('Posts.php');
}

if ($admin->has('id')) {
    $result = $link->query('SELECT * FROM users WHERE id = :id', [
        'id' => App::get('id')
    ])->fetch();

    if (str_contains($session->getKey('user_infos')->status, 'admin')) {
        if ($admin->sendAdminRequest($result)) {
            $session->setFlash('success', 'An Admin Request has been successfully sent to the User');
        } else {
            $session->setFlash('success', 'The Boss Approval for the Admin Request is awaited');
        }
    }
}

if ($superAdmin->has('answer')){
    $answer = App::get('answer');

    if ($superAdmin->has('id')){
        $result = $link->query('SELECT * FROM users WHERE id = :id', [
            'id' => App::get('id')
        ])->fetch();

        if($superAdmin->superAdminApproval($result, $answer)){
            $session->setFlash('success', 'An Admin Request has been successfully sent to the User');
        }
    } else {
        App::redirect('Posts.php');
    }
}

if ($user->has('value')){
    $result = $link->query('SELECT * FROM users WHERE id = :id', [
        'id' => $session->getKey('user_infos')->id
    ])->fetch();

    if ($user->confirmAdminRequest($result, App::get('value'))){
        $session->setFlash("success", "Congratulations! You're now an Administrator of this Website");
    }

}

App::redirect('Posts.php');

