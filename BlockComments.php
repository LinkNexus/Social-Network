<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if ($user->has('id') && $user->has('index')){
    $post_id = App::get('id');
    $index = App::get('index');
    $post = $link->query('SELECT * FROM posts WHERE id = :id', ['id' => $post_id])->fetch();

    $user->blockComments($post, $index, $post_id);
}
App::redirect('Posts.php');
