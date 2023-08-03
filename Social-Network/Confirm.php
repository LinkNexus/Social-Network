<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();


if (App::getUser()->confirm(App::get('id'), App::get('token'))) {
    $session->setFlash('success', 'Your Account has been successfully confirmed');
    App::redirect('Account.php');
} else {
    $session->setFlash('alert', 'Token is not valid');
    App::redirect('Login.php');
}