<?php

require_once 'Include/Load.php';

$link = App::getDatabase();
$session = App::getSession();
$user = App::getUser();
$user->restrict();

if (!$user->has('id')){
    App::redirect('Posts.php');
} else {
    $post_id = App::get('id');
}

$directory = 'Mini_Blog'; /* The name of the directory where the entire project is */

$full_URL = "http". (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') .'://'. $_SERVER['HTTP_HOST'] . '/'. (empty($directory) ? '' : $directory .'/') .'Posts.php#PostN'. $post_id;

?>

<?php require_once 'Include/Header.php'; ?>
<div class="info-box">
    <div class="info">
        Link: <?= $full_URL; ?>
    </div>
</div>
