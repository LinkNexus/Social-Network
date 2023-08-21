<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();
$validator = App::getValidator();

if ($validator->isPosted() && $validator->isValuePosted('email')){

    if ($user->verifyEmail($_POST['email'])){
        $session->setFlash('success', 'The mail containing the Instructions for the Password Reset has been sent to you');
        App::redirect('Login.php');
    } else{
        $session->setflash('alert', 'Connection Information are invalid');
    }

}

?>
<?php require_once 'Include/Header.php' ?>
<div class="login-box">
    <h2>Forgotten Password</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="user-box">
            <input type="email" name="email" required="">
            <label>Email</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
