<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if (App::getValidator()->isPosted()){

    $posts = $link->query("SELECT * FROM posts WHERE (title LIKE :term OR content LIKE :term OR description LIKE :term)", [
        'term' => '%'. $_POST['search'] .'%'
    ])->fetchAll();

    $Users = $link->query("SELECT * FROM users WHERE username LIKE :term", [
        'term' => '%'. $_POST['search'] .'%'
    ])->fetchAll();

    if ($Users) {
        foreach ($Users as $User) {
            $results = $link->query('SELECT * FROM posts WHERE user_id = :id', [
                'id' => $User->id
            ])->fetchAll();

            if ($results) {
                for ($i = 0; $i < count($results); $i++) {
                    $Posts[] = $results[$i];
                }
            }
        }
    }

    if (isset($Posts)) {
        foreach ($Posts as $Post) {
            if (!in_array($Post, $posts)) {
                $posts[] = $Post;
            }
        }
    }
} else {
    $posts = $link->query('SELECT * FROM posts')->fetchAll();
}

?>
<?php require_once 'Include/Header.php'; ?>
<style>
    form {
        width: 95%;
    }
     #postInput{
        width: 100%;
        height: 40px;
        border: 1px solid white;
        border-radius: 5px;
        background: none;
        margin-bottom: 30px;
        font-size: 20px;
        color: grey;
        box-sizing: border-box;
        padding: 5px;
    }

     .login-box{
         color: white;
         width: 580px;
         display: flex;
         flex-direction: column;
         border-radius: 0;
         margin-bottom: 40px;
     }

     .login-box .postHeader{
         margin-bottom: 20px;
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
         width: 50px;
         aspect-ratio: 1;
         border-radius: 50%;
         align-self: center;
     }

     .login-box .postHeader div div{
         margin-left: 10px;
         height: 50px;
         display: flex;
         align-self: flex-end;
         flex-direction: column;
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
         width: 500px;
         align-self: center;
         margin-top: 30px;
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
     }
</style>
<form action="" method="post">
    <input type="text" name="search" placeholder="What's New?" id="postInput" value="<?= App::getValidator()->isPosted() ? $_POST['search'] : ''; ?>"/>
</form>
<script>
    const postInput = document.getElementById('postInput');

    <?php

        if (!App::getValidator()->isPosted()){
            echo "postInput.addEventListener('click', function () {
            window.location = 'Post.php';
            })";
        }

    ?>
</script>

<?php foreach ($posts as $post):?>
    <div class="login-box" id="<?= 'PostN'. $post->id ?>">
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
        <div class="content"><?php echo nl2br($result->content) ?></div>
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
        <span class="postFooter">Posted
            <?php echo App::displayTimeAgo('posts', $post); ?>
        </span>
        <script>

            let <?= 'postImage'. $post->id ?> = document.querySelector('<?= '#PostN'. $post->id .'>img' ?>'),
                <?= 'profileSection'. $post->id ?> = document.querySelector('<?= '#PostN'. $post->id .' .postHeader>div' ?>'),
                <?= 'postHeader'. $post->id ?> = document.querySelector('<?= '#PostN'. $post->id .' .postHeader' ?>'),
                <?= 'postTitle'. $post->id ?> = document.querySelector('<?= '#PostN'. $post->id .' .title' ?>'),
                <?= 'postDescription'. $post->id ?> = document.querySelector('<?= '#PostN'. $post->id .' .description' ?>'),
                <?= 'postContent'. $post->id ?> = document.querySelector('<?= '#PostN'. $post->id .' .content' ?>');

            if (<?= 'postImage'. $post->id ?>){
                <?= 'postImage'. $post->id ?>.addEventListener('click', function () {
                    document.location = '<?php echo 'Uploads/Posts/' . $post->image; ?>';
                })
            }

            <?= 'profileSection'. $post->id ?>.addEventListener('click', function () {
                document.location = '<?php echo 'Profile.php?id=' . $post->user_id; ?>';
            })

            <?= 'postTitle'. $post->id ?>.addEventListener('click', function () {
                document.location = '<?php echo 'DisplayPost.php?id=' . $post->id; ?>';
            })

            <?= 'postDescription'. $post->id ?>.addEventListener('click', function () {
                document.location = '<?php echo 'DisplayPost.php?id=' . $post->id; ?>';
            })

            <?= 'postContent'. $post->id ?>.addEventListener('click', function () {
                document.location = '<?php echo 'DisplayPost.php?id=' . $post->id; ?>';
            })

        </script>
    </div>

<?php endforeach; ?>

<?php include_once 'Include/Mode.php' ?>
</body>
</html>
