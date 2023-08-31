<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if ($user->has('id')){
    $post_id = App::get('id');
    $post = $link->query('SELECT * FROM posts WHERE id = :id', ['id' => $post_id])->fetch();

    if ($user->deletePost($post_id, $post)){
        $session->setFlash('success', 'Your Post has been successfully been deleted');
    }
}
App::redirect('Posts.php');


