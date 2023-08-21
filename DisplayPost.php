<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if ($user->has('id')){
    $post = $link->query('SELECT * FROM posts WHERE id = :id', ['id' => App::get('id')])->fetch();
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
    .login-box{
        color: white;
        width: 95%;
        display: flex;
        flex-direction: column;
        border-radius: 0;
        margin-bottom: 40px;
    }

    .login-box .postHeader{
        margin-bottom: 50px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
    }

    .login-box .postHeader div{
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .login-box .postHeader div img{
        width: 100px;
        aspect-ratio: 1;
        border-radius: 50%;
        align-self: center;
    }

    .login-box .postHeader div div{
        margin-left: 10px;
        height: 50px;
        display: flex;
        align-self: center;
        flex-direction: column;
        font-size: 20px;
    }

    .login-box .postHeader .status{
        font-size: 12px;
        font-weight: normal;
        color: grey;
        font-style: italic;
    }

    .login-box .postHeader a{
        font-size: 30px;
        text-align: center;
        display: inline-block;
        width: 45px;
        height: 45px;
        padding: 8px;
        box-sizing: border-box;
    }

    .login-box .description{
        text-align: center;
        color: grey;
        margin-bottom: 20px;
    }

    .login-box>img{
        width: calc(100% - 20px);
        align-self: center;
        margin-top: 50px;
        cursor: pointer;
    }

    .login-box .postIcons{
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .login-box .postFooter{
        color: grey;
        font-size: 12px;
        font-style: italic;
        margin-top: 30px;
    }

    form{
        width: 100%;
    }

    .login-box form .commentInput{
        width: 100%;
        height: 25px;
        border-radius: 10px;
        padding: 10px;
        overflow: auto;
        color: white;
        background: none;
        margin-bottom: 30px;
    }

    .login-box form button {
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
<div class="login-box">
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
    <form action="" method="post" enctype="multipart/form-data">
        <textarea class="commentInput" placeholder="Write your comment here" name="comment"></textarea>
        <input type="file" name="commentPic">
        <button type="submit">Submit</button>
    </form>
    <span class="postFooter">Posted
            <?php $result = $link->query('SELECT TIMESTAMPDIFF(MINUTE, posted_at, NOW()) as date FROM posts WHERE user_id = :id', [
                'id' => $post->user_id
            ])->fetch();

            if ($result->date < 60){
                echo $result->date. ' Minutes ago';
            } else {
                if ($result->date < 1440){
                    echo intval($result->date / 60). ' Hours ago';
                } else {
                    if ($result->date < 10080){
                        echo intval($result->date / 1440). ' Days ago';
                    } else {
                        if ($result->date < 40320){
                            echo intval($result->date / 10080). ' Weeks ago';
                        } else {
                            if ($result->date < 483840){
                                echo intval($result->date / 40320). ' Months ago';
                            } else {
                                echo intval($result->date / 483840). ' Years ago';
                            }
                        }
                    }
                }
            }

            ?>
    </span>
    <div class="comments">

    </div>
    <script>

        let <?= 'postImage'. $post->id ?> = document.querySelector('.login-box>img'),
            <?= 'profileSection'. $post->id ?> = document.querySelector('.postHeader>div'),
            <?= 'commentInput'. $post->id ?> = document.querySelector('form>textarea');

        if (<?= 'postImage'. $post->id ?>){
            <?= 'postImage'. $post->id ?>.addEventListener('click', function () {
                document.location = '<?php echo 'Uploads/Posts/' . $post->image; ?>';
            })
        }

        <?= 'profileSection'. $post->id ?>.addEventListener('click', function () {
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
</div>
<?php include_once 'Include/Mode.php'; ?>
</body>
</html>
