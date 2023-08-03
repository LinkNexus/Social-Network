<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();
$user->restrict();
$validator = App::getValidator();

if ($validator->isPosted()){

    $user_id = $session->getKey('user_infos')->id;

    $result = $link->query('SELECT * FROM users WHERE id = :id AND (modified_at IS NULL OR modified_at <= DATE_SUB(NOW(), INTERVAL 1 WEEK))', ['id' => $user_id])->fetch();

    if ($user->changeUsername($result, $user_id, $_POST['username'])) {
        $result = $user->changeUsername($result, $user_id, $_POST['username']);

         if (is_array($result)){
             $session->setFlash('alert', 'Username must not be empty and can only contain letters, numbers and underscores');
         } else {
             $session->setFlash('success', 'Your Username has been modified');
         }
    } else {
        $session->setFlash('alert', 'You need to wait 1 Week before changing back your Username');
    }

}

?>
<?php require_once 'Include/Header.php'; ?>
<div class="login-box">
    <h2>Change Username</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="user-box">
            <input type="text" name="username" required="">
            <label>New Username</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
