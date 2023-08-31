<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

if (App::getValidator()->isPosted()){
    if ($_FILES['avatar']['error'] == 0){
        $user_id = $session->getKey('user_infos')->id;
        $current_avatar = $session->getKey('user_infos')->avatar;

        if ($user->upLoadFile($_FILES['avatar'], 5242880, ['jpg', 'jpeg', 'gif', 'png'], $user_id)){
            App::deleteFile($current_avatar, '/Uploads/Avatars/');

            $link->query('UPDATE users SET avatar = :avatar WHERE id = :id', [
                'avatar' => basename($_FILES['avatar']['name']),
                'id' => $user_id
            ]);

            $result = $link->query('SELECT * FROM users WHERE id = :id', ['id' => $user_id])->fetch();
            $user->connect($result);
            move_uploaded_file($_FILES['avatar']['tmp_name'], 'Uploads/Avatars/' . basename($_FILES['avatar']['name']));
            $session->setFlash('success', 'Your Profile Picture has been successfully uploaded');
        } else {
            if ($user->upLoadFile($_FILES['avatar'], 5242880, ['jpg', 'jpeg', 'gif', 'png'], $user_id)) {
                $session->setFlash('alert', 'Allowed File Extensions are: jpg, jpeg, gif, png');
            } else {
                $session->setFlash('alert', 'The File must not be more than 5Mo');
            }
        }
    }
}

?>
<?php require_once 'Include/Header.php'; ?>
<div class="login-box">
    <h2>Avatar</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <div class="user-box">
            <input type="file" name="avatar" required="">
            <label></label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
