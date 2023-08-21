<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if ($user->has('id')){
    $post_id = App::get('id');
    $post = $link->query('SELECT * FROM posts WHERE id = :id', ['id' => $post_id])->fetch();

    if ($post->user_id === $session->getKey('user_infos')->id) {
        $current_image = $post->image;
        App::deleteFile($current_image, '/Uploads/Posts/');
        $link->query('DELETE FROM posts WHERE id = :id', ['id' => $post_id]);
        $session->setFlash('success', 'Your Post has been successfully been deleted');
    }

}
App::redirect('Posts.php');


