<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if ($user->has('id')){
    $id = App::get('id');
} else {
    App::redirect('Posts.php');
}

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
        <a href="CopyLink.php?id=<?php echo $id ?>" class="nav-links">
            <span class="material-symbols-outlined">search</span>
            <span class="icon-text">Copy Link</span>
        </a>
    <?php else: ?>

        <?php App::redirect('Posts.php'); ?>

    <?php endif; ?>
</div>
<script>
    let deletePostLink = document.getElementById('deletePost');

    deletePostLink.addEventListener('click', function (evt) {
        evt.preventDefault()
        if (window.confirm('Your are deleting your Post')){
            window.location = "DeletePost.php?id=<?php echo $id ?>";
        }
    })
</script>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
