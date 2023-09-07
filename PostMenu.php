<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if (!$user->has('id')){
    App::redirect('Posts.php');
}
$id = App::get('id');
$post = $link->query('SELECT * FROM posts WHERE id = :id', ['id' => $id])->fetch();

?>
<?php require_once 'Include/Header.php'; ?>
<div id="navBar">
    <h2>Post Menu</h2>
    <?php if ($post): ?>

        <?php if ($session->getKey('user_infos')->id === $post->user_id): ?>
            <a href="Post.php?id=<?php echo $id; ?>" class="nav-links">
                <span class="material-symbols-outlined">edit</span>
                <span class="icon-text">Modify Post</span>
            </a>
        <?php endif; ?>

        <?php if($session->getKey('user_infos')->id === $post->user_id || str_contains($session->getKey('user_infos')->status, 'admin')): ?>
            <a href="#" class="nav-links" id="deletePost">
                <span class="material-symbols-outlined">delete</span>
                <span class="icon-text">Delete Post</span>
            </a>
            <?php if ($post->comments_blocked == 0): ?>
                <a href="BlockComments.php?index=1&id=<?php echo $id ?>" class="nav-links">
                    <span class="material-symbols-outlined">token</span>
                    <span class="icon-text">Block Comments</span>
                </a>
            <?php else: ?>
                <a href="BlockComments.php?index=0&id=<?php echo $id ?>" class="nav-links">
                    <span class="material-symbols-outlined">token</span>
                    <span class="icon-text">Allow Comments</span>
                </a>
            <?php endif; ?>

        <?php endif; ?>

        <?php if (str_contains($session->getKey('user_infos')->status, 'admin')): ?>

            <a href="#" class="nav-links" id="warnUser">
                <span class="material-symbols-outlined">edit</span>
                <span class="icon-text">Warn User</span>
            </a>
            <a href="#" class="nav-links" id="muteUser">
                <span class="material-symbols-outlined">edit</span>
                <span class="icon-text">Mute User</span>
            </a>
            <a href="BanUser.php?id=<?= $post->user_id; ?>" class="nav-links">
                <span class="material-symbols-outlined">edit</span>
                <span class="icon-text">Ban User</span>
            </a>

            <?php $result = $link->query('SELECT * FROM users WHERE id = :id', ['id' => $post->user_id])->fetch(); ?>


            <?php if (str_contains($result->status, 'admin')): ?>
                <a href="RemoveAdmin.php?id=<?= $post->user_id; ?>" class="nav-links">
                    <span class="material-symbols-outlined">edit</span>
                    <span class="icon-text">Remove Admin</span>
                </a>
            <?php else: ?>
                <a href="#" class="nav-links" id="appointAdmin">
                    <span class="material-symbols-outlined">edit</span>
                    <span class="icon-text">Appoint Admin</span>
                </a>
            <?php endif; ?>

        <?php endif; ?>
        <a href="CopyLink.php?id=<?php echo $id ?>" class="nav-links">
            <span class="material-symbols-outlined">search</span>
            <span class="icon-text">Copy Link</span>
        </a>
    <?php else: ?>

        <?php App::redirect('Posts.php'); ?>

    <?php endif; ?>
</div>
<script>
    let deletePostLink = document.getElementById('deletePost'),
        appointAdminLink = document.getElementById('appointAdmin'),
        warnUserLink = document.getElementById('warnUser'),
        muteUserLink = document.getElementById('muteUser');

    deletePostLink.addEventListener('click', function (evt) {
        evt.preventDefault()
        if (window.confirm('Your are deleting your Post')){
            window.location = "DeletePost.php?id=<?php echo $id ?>";
        }
    });

    appointAdminLink.addEventListener('click', function (evt) {
        evt.preventDefault()
        if (window.confirm('Do you want to add this User as an Administrator of this Website?')){
            window.location = "AddAdmin.php?id=<?= $post->user_id ?>";
        }
    });

    warnUserLink.addEventListener('click', function (evt) {
        evt.preventDefault();
        if (window.confirm('Do you want to send a Warning to this User?')){
            window.location = "WarnUser.php?id=<?= $post->user_id ?>";
        }
    });

    muteUserLink.addEventListener('click', function (evt) {
        evt.preventDefault();
        if (window.confirm('Do you want to mute this User?')){
            window.location = "MuteUser.php?id=<?= $post->user_id ?>";
        }
    });
</script>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
