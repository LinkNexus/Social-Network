<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();
$validator = App::getValidator();

$user->restrict();

if ($user->has('id')) {
    $comment_id = App::get('id');
    $comment = $link->query('SELECT * FROM comments WHERE id = :id', ['id' => $comment_id])->fetch();

    if ($session->getKey('user_infos')->id !== $comment->user_id){
        App::redirect('Posts.php');
    }

    if ($validator->isPosted()){
        if (!empty($_FILES['commentPic'])) {
            if ($_FILES['commentPic']['error'] == 0) {
                if ($user->upLoadFile($_FILES['commentPic'], 5242880, ['jpg', 'jpeg', 'gif', 'png'])) {
                    $current_image = $comment->image;
                    App::deleteFile($current_image, '/Uploads/Comments/');

                    $link->query('UPDATE comments SET content = :content, image = :image, modified_at = NOW() WHERE id = :id', [
                        'content' => $_POST['comment'],
                        'image' => basename($_FILES['commentPic']['name']),
                        'id' => $comment_id
                    ]);

                    move_uploaded_file($_FILES['commentPic']['tmp_name'], 'Uploads/Comments/' . basename($_FILES['commentPic']['name']));
                } else {
                    if (is_bool($user->upLoadFile($_FILES['commentPic'], 5242880, ['jpg', 'jpeg', 'gif', 'png']))) {
                        $session->setFlash('alert', 'Allowed File Extensions are: jpg, jpeg, gif, png');
                    } else {
                        $session->setFlash('alert', 'The File must not be more than 5Mo');
                    }

                    $link->query('UPDATE comments SET content = :content, modified_at = NOW() WHERE id = :id', [
                        'content' => $_POST['comment'],
                        'id' => $comment_id
                    ]);
                }
            } else {
                $current_image = $comment->image;
                App::deleteFile($current_image, '/Uploads/Comments/');

                $link->query('UPDATE comments SET content = :content, modified_at = NOW() WHERE id = :id', [
                    'content' => $_POST['comment'],
                    'id' => $comment_id
                ]);
            }
        } else {
            $current_image = $comment->image;
            App::deleteFile($current_image, '/Uploads/Comments/');

            $link->query('UPDATE comments SET content = :content, modified_at = NOW() WHERE id = :id', [
                'content' => $_POST['comment'],
                'id' => $comment_id
            ]);
        }

        $session->setFlash('success', 'The Comment has successfully modified');
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<style>
    .login-box .user-box textarea{
        background: none;
        color: white;
        width: 100%;
        padding: 5px;
        height: 300px;
    }
</style>
<div class="login-box">
    <h2>Edit Comment</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="user-box">
            <textarea name="comment" id="content" required><?= isset($comment) ? $comment->content : '' ?></textarea>
        </div>
        <div class="user-box">
            <input type="file" name="commentPic">
            <label></label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php' ?>
</body>
</html>
