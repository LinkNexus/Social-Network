<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();
$validator = App::getValidator();

$user->restrict();

if ($validator->isPosted()){
    $result = $link->query('SELECT * FROM users WHERE id = :id', [
        'id' => $session->getKey('user_infos')->id
    ])->fetch();

    if ($user->deleteAccount($result, $_POST['password'])) {
        $session->setFlash('success', 'Your Account has been successfully deleted');
        App::redirect('Login.php');
    } else {
        $session->setFlash('alert', "Your Account's Password is incorrect");
    }


}

?>
<?php require_once 'Include/Header.php'; ?>
<div class="login-box">
    <h2>Delete Account</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="user-box">
            <input type="password" name="password" required="">
            <label>Password</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
