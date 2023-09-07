<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();
$user->reconnectFromCookie();
$validator = App::getValidator();

if ($user->isUserConnected()){
    App::redirect('Account.php');
}

if ($validator->isPosted() && $validator->isValuePosted('username') && $validator->isValuePosted('password')) {

    $result = $user->login($_POST['username'], $_POST['password'], isset($_POST['remember']));

    if ($result) {
        if (is_bool($result)) {
            $session->setFlash('success', 'You are now connected');
            App::redirect('Account.php');
        }
        $session->setFlash('alert', 'The Account with this Username/Email is temporary banned for '. $result .' days');
    } else {
        $session->setFlash('alert', 'Connection Information are invalid');
    }
}


?>

<?php require_once 'Include/Header.php' ?>
<div class="login-box">
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="user-box">
            <input type="text" name="username" required="">
            <label>Username or Email</label>
        </div>
        <div class="user-box">
            <input type="password" name="password" required="">
            <label>Password</label>
        </div>
        <div class="user-box">
            <label>Remember me</label>
            <input type="checkbox" name="remember" class="remember">
        </div>
        <button type="submit">Submit</button>
        <a href="ForgotPassword.php">Forgot Password?</a>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
