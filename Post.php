<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();
$validator = App::getValidator();

$user->restrict();

if ($user->has('id')) {
    $post_id = App::get('id');
    $post = $link->query('SELECT * FROM posts WHERE id = :id', ['id' => $post_id])->fetch();
    if ($session->getKey('user_infos')->id !== $post->user_id){
        App::redirect('Posts.php');
    }
}

if ($validator->isPosted()){
    if ($user->has('id')){
        $post_id = App::get('id');
        $post = $link->query('SELECT * FROM posts WHERE id = :id', ['id' => $post_id])->fetch();

        $validator->isTooLong('title', 50, 'The Title must not be more than 50 Characters long');

        if (!empty($_POST['description'])) {
            $validator->isTooLong('description', 100, 'The Description must not be more than 100 Characters long');
        }

        if ($validator->isValid()){
            if (!empty($_FILES['postPic']['name'])){
                if ($_FILES['postPic']['error'] == 0){
                    if ($user->upLoadFile($_FILES['postPic'], 5242880, ['jpg', 'jpeg', 'gif', 'png'])){
                        $user->modifyPost($_POST['title'], $_POST['description'], $_POST['content'], $post_id, $post, basename($_FILES['postPic']['name']));

                    } else {
                        if (is_bool($user->upLoadFile($_FILES['postPic'], 5242880, ['jpg', 'jpeg', 'gif', 'png']))) {
                            $session->setFlash('alert', 'Allowed File Extensions are: jpg, jpeg, gif, png');
                        } else {
                            $session->setFlash('alert', 'The File must not be more than 5Mo');
                        }

                        $user->modifyPost($_POST['title'], $_POST['description'], $_POST['content'], $post_id, $post);

                    }
                } else {
                    $user->modifyPost($_POST['title'], $_POST['description'], $_POST['content'], $post_id, $post);
                }
            } else {
                $user->modifyPost($_POST['title'], $_POST['description'], $_POST['content'], $post_id, $post, NULL, false);
            }

            $session->setFlash('success', 'The Post has been successfully modified');
            App::redirect('Posts.php');
        } else {
            $errors = $validator->getErrors();
        }
    } else {
        $user_id = $session->getKey('user_infos')->id;

        $validator->isTooLong('title', 50, 'The Title must not be more than 50 Characters long');

        if (!empty($_POST['description'])) {
            $validator->isTooLong('description', 100, 'The Description must not be more than 100 Characters long');
        }

        if ($validator->isValid()) {

            if (!empty($_FILES['postPic'])) {
                if ($_FILES['postPic']['error'] == 0) {
                    if ($user->upLoadFile($_FILES['postPic'], 5242880, ['jpg', 'jpeg', 'gif', 'png'])) {

                        $user->makePost($_POST['title'], $_POST['description'], $_POST['content'], $user_id, basename($_FILES['postPic']['name']));
                    } else {
                        if (is_bool($user->upLoadFile($_FILES['postPic'], 5242880, ['jpg', 'jpeg', 'gif', 'png']))) {
                            $session->setFlash('alert', 'Allowed File Extensions are: jpg, jpeg, gif, png');
                        } else {
                            $session->setFlash('alert', 'The File must not be more than 5Mo');
                        }

                        $user->makePost($_POST['title'], $_POST['description'], $_POST['content'], $user_id);
                    }
                } else {
                    $user->makePost($_POST['title'], $_POST['description'], $_POST['content'], $user_id);
                }
            } else {
                $user->makePost($_POST['title'], $_POST['description'], $_POST['content'], $user_id);
            }

            $session->setFlash('success', 'The Post has successfully published');
        } else {
            $errors = $validator->getErrors();
        }
    }
}

?>
<?php require_once 'Include/Header.php'; ?>
<?php if (!empty($errors)): ?>
    <div class="error-msg">
        <p>The Information were not filled correctly. These are the possible errors:</p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<style>
    .login-box .user-box textarea{
        background: none;
        color: white;
        width: 100%;
        padding: 5px;
    }

    .login-box .user-box #description{
        height: 40px;
        margin-bottom: 30px;
        overflow: auto;
    }

    .login-box .user-box #content{
        height: 300px;
        overflow: auto;
    }
</style>
<div class="login-box">
    <h2>Post</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="user-box">
            <input type="text" name="title" required="" value="<?= isset($post) ? $post->title : ''; ?>">
            <label>Title</label>
        </div>
        <div class="user-box">
            <textarea name="description" id="description" placeholder="Description (Less than 100 characters)
(Not Required)"><?= isset($post) ? $post->description : ''; ?></textarea>
        </div>
        <div class="user-box">
            <textarea name="content" id="content" placeholder="Content" required><?= isset($post) ? $post->content : ''; ?></textarea>
        </div>
        <div class="user-box">
            <input type="file" name="postPic">
            <label></label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php include_once 'Include/Mode.php' ?>
</body>
</html>
