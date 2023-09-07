<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if ($user->has('id')){
    $post = $link->query('SELECT * FROM posts WHERE id = :id', ['id' => App::get('id')])->fetch();
    $comments = $link->query('SELECT * FROM comments WHERE post_id = :id', ['id' => App::get('id')])->fetchAll();
} else {
    App::redirect('Posts.php');
}

if (App::getValidator()->isPosted()){
    $post_id = App::get('id');
    $user_id = $session->getKey('user_infos')->id;

    if (!empty($_FILES['commentPic'])) {
        if ($_FILES['commentPic']['error'] == 0) {
            if ($user->upLoadFile($_FILES['commentPic'], 5242880, ['jpg', 'jpeg', 'gif', 'png'])) {
                $link->query('INSERT INTO comments(content, image, post_id, user_id, posted_at) VALUES (:content, :image, :post_id, :user_id, NOW())', [
                    'content' => $_POST['comment'],
                    'image' => basename($_FILES['commentPic']['name']),
                    'post_id' => $post_id,
                    'user_id' => $user_id,
                ]);

                move_uploaded_file($_FILES['commentPic']['tmp_name'], 'Uploads/Comments/' . basename($_FILES['commentPic']['name']));
            } else {
                if (is_bool($user->upLoadFile($_FILES['commentPic'], 5242880, ['jpg', 'jpeg', 'gif', 'png']))) {
                    $session->setFlash('alert', 'Allowed File Extensions are: jpg, jpeg, gif, png');
                } else {
                    $session->setFlash('alert', 'The File must not be more than 5Mo');
                }

                $link->query('INSERT INTO comments(content, post_id, user_id, posted_at) VALUES (:content, :post_id, :user_id, NOW())', [
                    'content' => $_POST['comment'],
                    'post_id' => $post_id,
                    'user_id' => $user_id,
                ]);
            }
        } else {
            $link->query('INSERT INTO comments(content, post_id, user_id, posted_at) VALUES (:content, :post_id, :user_id, NOW())', [
                'content' => $_POST['comment'],
                'post_id' => $post_id,
                'user_id' => $user_id,
            ]);
        }
    } else {
        $link->query('INSERT INTO comments(content, post_id, user_id, posted_at) VALUES (:content, :post_id, :user_id, NOW())', [
            'content' => $_POST['comment'],
            'post_id' => $post_id,
            'user_id' => $user_id,
        ]);
    }

    $session->setFlash('success', 'The Comment has successfully published');
}

?>
<?php require_once 'Include/Header.php'; ?>
<style>
    .post{
        color: white;
        width: 95%;
        display: flex;
        flex-direction: column;
        border-radius: 0;
        margin-bottom: 40px;
    }

    .post .postHeader{
        margin-bottom: 50px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
    }

    .post .postHeader div{
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .post .postHeader div img{
        width: 100px;
        aspect-ratio: 1;
        border-radius: 50%;
        align-self: center;
    }

    .post .postHeader div div{
        margin-left: 10px;
        height: 50px;
        display: flex;
        align-self: center;
        flex-direction: column;
        font-size: 20px;
    }

    .post .postHeader .status{
        font-size: 12px;
        font-weight: normal;
        color: grey;
        font-style: italic;
    }

    .post .postHeader a{
        font-size: 30px;
        text-align: center;
        display: inline-block;
        width: 45px;
        height: 45px;
        padding: 8px;
        box-sizing: border-box;
    }

    .post .description{
        text-align: center;
        color: grey;
        margin-bottom: 20px;
    }

    .post>img{
        width: calc(100% - 20px);
        align-self: center;
        margin-top: 50px;
        cursor: pointer;
    }

    .post .postIcons{
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .post .postFooter{
        color: grey;
        font-size: 12px;
        font-style: italic;
        margin-top: 30px;
    }

    form{
        width: 100%;
    }

    .post form .commentInput{
        width: 100%;
        height: 25px;
        border-radius: 10px;
        padding: 10px;
        overflow: auto;
        color: white;
        background: none;
        margin-bottom: 30px;
    }

    .post form button {
        position: relative;
        display: block;
        padding: 10px 20px;
        color: red;
        font-size: 16px;
        text-decoration: none;
        text-transform: uppercase;
        overflow: hidden;
        transition: .5s;
        margin-top: 40px;
        letter-spacing: 4px;
        background: inherit;
        border: none;
    }
</style>
<div class="login-box post">
    <div class="postHeader">
        <div>
            <img alt="profile_pic" src="<?php

            $result = $link->query('SELECT p.user_id, u.avatar FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.id = :id', [
                'id' => $post->id
            ])->fetch();

            if ($result->avatar === null){
                echo 'Assets/avatar.jpeg';
            } else {
                echo 'Uploads/Avatars/'. $result->avatar;
            }

            ?>">

            <div>
                   <span>
                    <?php

                    $result = $link->query('SELECT u.username, u.status, p.content FROM users u INNER JOIN posts p ON u.id = p.user_id WHERE p.id = :id', [
                        'id' => $post->id
                    ])->fetch();

                    echo $result->username;

                    ?>
                   </span>
                <?php if ($result->status == 'admin'): ?>
                    <span class="status">
                        Admin
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <a href="PostMenu.php?id=<?= $post->id ?>" class="material-symbols-outlined">more_vert</a>
    </div>
    <h2 class="title"><?php echo $post->title ?></h2>
    <div class="description"><?php echo $post->description ?></div>
    <div class="content"><?php echo nl2br(htmlspecialchars($result->content)) ?></div>
    <?php if ($post->image !== NULL): ?>
        <img src="<?= "Uploads/Posts/$post->image" ?>" alt="Post_Image">
    <?php endif; ?>
    <div class="postIcons">
        <a href="#" class="material-symbols-outlined">thumb_up</a>
        <?php if (!$post->comments_blocked): ?>
            <a href="<?= 'DisplayPost.php?id='. $post->id; ?>" class="material-symbols-outlined comment">comment</a>
        <?php endif; ?>
        <a href="#" class="material-symbols-outlined">share</a>
    </div>
    <?php if ($post->comments_blocked == 0): ?>
        <form action="" method="post" enctype="multipart/form-data">
            <textarea class="commentInput" placeholder="Write your comment here" name="comment"></textarea>
            <input type="file" name="commentPic">
            <button type="submit">Submit</button>
        </form>
    <?php endif; ?>
    <span class="postFooter">Posted
        <?php echo App::displayTimeAgo('posts', $post); ?>
    </span>
</div>
<script>
    let <?= 'postImage'. $post->id ?> = document.querySelector('.login-box>img'),
        <?= 'postProfileSection'. $post->id ?> = document.querySelector('.postHeader>div'),
        <?= 'commentInput'. $post->id ?> = document.querySelector('form>textarea');

    if (<?= 'postImage'. $post->id ?>){
        <?= 'postImage'. $post->id ?>.addEventListener('click', function () {
            document.location = '<?php echo 'Uploads/Posts/' . $post->image; ?>';
        })
    }

    <?= 'postProfileSection'. $post->id ?>.addEventListener('click', function () {
        document.location = '<?php echo 'Profile.php?id=' . $post->user_id; ?>';
    })

    <?= 'commentInput'. $post->id ?>.addEventListener('focus', function () {
        <?= 'commentInput'. $post->id ?>.style.height = '100px';
        <?= 'commentInput'. $post->id ?>.style.transition = '0.5s';
    })

    <?= 'commentInput'. $post->id ?>.addEventListener('focusout', function () {
        <?= 'commentInput'. $post->id ?>.style.height = '25px';
    })
</script>
<style>
    .comments{
        margin-top: -40px;
        width: 95%;
        display: flex;
        flex-direction: column;
        color: white;
        padding: 30px;
        border-radius: 0;
    }

    .comment{
        display: flex;
        flex-direction: column;
        border-radius: 10px;
    }

    .comment .commentHeader{
        margin-bottom: 50px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
    }

    .comment .commentHeader div{
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .comment .commentHeader div img{
        width: 35px;
        aspect-ratio: 1;
        border-radius: 50%;
        align-self: center;
    }

    .comment .commentHeader div div{
        margin-left: 10px;
        height: 50px;
        display: flex;
        align-self: center;
        flex-direction: column;
        margin-top: 20px;
        font-size: 12px;
    }

    .comment .commentHeader .status{
        font-size: 10px;
        font-weight: normal;
        color: grey;
        font-style: italic;
    }

    .comment .commentHeader a{
        font-size: 20px;
        text-align: center;
        display: inline-block;
        width: 30px;
        height: 30px;
        padding: 5px;
        box-sizing: border-box;
    }

    .comment .commentContent{
        margin-top: -40px;
        text-align: center;
    }

    .comment .commentFooter{
        color: grey;
        font-size: 12px;
        font-style: italic;
        margin-top: 30px;
    }

    .comments>img{
        max-width: calc(38%);
        max-height: 500px;
        cursor: pointer;
        border-radius: 10px;
        margin-bottom: 40px;
    }
</style>
<?php if ($comments): ?>
    <div class="login-box comments">
        <?php foreach ($comments as $comment): ?>
            <div class="login-box comment" id="<?= 'CommentN'. $comment->id ?>">
                <div class="commentHeader">
                    <div>
                        <img alt="profile_pic" src="<?php

                        $result = $link->query('SELECT c.user_id, u.avatar FROM comments c INNER JOIN users u ON c.user_id = u.id WHERE c.id = :id', [
                                'id' => $comment->id
                        ])->fetch();

                        if ($result->avatar === null){
                            echo 'Assets/avatar.jpeg';
                        } else {
                            echo 'Uploads/Avatars/'. $result->avatar;
                        }

                        ?>">

                        <div>
                            <span>
                                <?php

                                $result = $link->query('SELECT u.username, u.status, c.content FROM users u INNER JOIN comments c ON u.id = c.user_id WHERE c.id = :id', [
                                        'id' => $comment->id
                                ])->fetch();

                                echo $result->username;

                                ?>
                            </span>
                            <?php if ($result->status == 'admin'): ?>
                                <span class="status">
                                    Admin
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="CommentMenu.php?id=<?= $comment->id ?>" class="material-symbols-outlined">more_vert</a>
                </div>
                <div class="commentContent"><?php echo nl2br(htmlspecialchars($result->content)) ?></div>
                <span class="commentFooter">Posted
                    <?php echo App::displayTimeAgo('comments', $comment); ?>
                </span>
            </div>
            <?php if ($comment->image != NULL): ?>
                <img id="<?= 'ImageN'. $comment->id ?>" src="<?= "Uploads/Comments/$comment->image" ?>" alt="Post_Image">
            <?php else: ?>
                <style>
                    #<?= 'CommentN'. $comment->id ?>{
                        margin-bottom: 40px;
                    }
                </style>
            <?php endif; ?>

            <script>
                let <?= 'commentImage'. $comment->id ?> = document.querySelector('<?= '#ImageN'. $comment->id; ?>'),
                    <?= 'commentProfileSection'. $comment->id ?> = document.querySelector('<?= '#CommentN'. $comment->id .' .commentHeader>div' ?>'),
                    <?= 'commentHeader'. $comment->id ?> = document.querySelector('<?= '#CommentN'. $comment->id .' .commentHeader' ?>');

                if (<?= 'commentImage'. $comment->id ?>){
                    <?= 'commentImage'. $comment->id ?>.addEventListener('click', function () {
                        document.location = '<?php echo 'Uploads/Comments/' . $comment->image; ?>';
                    })
                }

                <?= 'commentProfileSection'. $comment->id ?>.addEventListener('click', function () {
                    document.location = '<?php echo 'Profile.php?id=' . $comment->user_id; ?>';
                })
            </script>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php include_once 'Include/Mode.php'; ?>
</body>
</html>
