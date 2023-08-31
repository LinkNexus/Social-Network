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

$comment = $link->query('SELECT * FROM comments WHERE id = :id', ['id' => $id])->fetch();

?>
<?php require_once 'Include/Header.php'; ?>
<div id="navBar">
    <h2>Comment Menu</h2>
    <?php if ($comment): ?>

        <?php if ($session->getKey('user_infos')->id === $comment->user_id): ?>
            <a href="EditComment.php?id=<?php echo $id; ?>" class="nav-links">
                <span class="material-symbols-outlined">edit</span>
                <span class="icon-text">Modify Comment</span>
            </a>
            <a href="#" class="nav-links" id="deleteComment">
                <span class="material-symbols-outlined">delete</span>
                <span class="icon-text">Delete Comment</span>
            </a>
        <?php endif; ?>
    <?php else: ?>

        <?php App::redirect('Posts.php'); ?>

    <?php endif; ?>
</div>
<script>
    let deleteCommentLink = document.getElementById('deleteComment');

    deleteCommentLink.addEventListener('click', function (evt) {
        evt.preventDefault()
        if (window.confirm('Your are deleting your Comment')){
            window.location = "DeleteComment.php?id=<?php echo $id ?>";
        }
    })
</script>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>

