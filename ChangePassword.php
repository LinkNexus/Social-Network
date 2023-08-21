<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();
$user->restrict();
$validator = App::getValidator();

if ($validator->isPosted()){

    $result = $user->changePassword($_POST['og_password']);

    if ($result){
        if (is_array($result)){
            $session->setFlash('alert', implode(', ', $result));
        } else {
            $session->setFlash('success', 'Your Password has been updated');
        }
    } else{
        $session->setFlash('alert', "Your Account's Password is incorrect");
    }
}

?>

<?php require_once 'Include/Header.php'; ?>
<div class="login-box">
    <h2>Change Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <div class="user-box">
            <input type="password" name="og_password" required="">
            <label>Old Password</label>
        </div>
        <div class="user-box">
            <input type="password" name="new_password" required="">
            <label>New Password</label>
        </div>
        <div class="user-box">
            <input type="password" name="confirm_password" required="">
            <label>Confirm Password</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
