<?php

require_once 'Include/Load.php';

App::getUser()->logout();
App::getSession()->setFlash('success', 'You have been successfully disconnected');
App::redirect('Login.php');
