<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if ($user->has('id')){
    $comment_id = App::get('id');
    $comment = $link->query('SELECT * FROM comments WHERE id = :id', ['id' => $comment_id])->fetch();

    if ($user->deleteComment($comment_id, $comment)){
        $post_id = $comment->post_id;
        $link->query('DELETE FROM comments WHERE id = :id', ['id' => $comment_id]);
        $session->setFlash('success', 'Your Comment has been successfully been deleted');
        App::redirect('DisplayPost?id='. $post_id);
    }
}
App::redirect('Posts.php');




