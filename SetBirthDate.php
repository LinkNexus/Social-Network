<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();
$validator = App::getValidator();

$user->restrict();

if ($validator->isPosted()) {
    try {
        $dateDiff = date_diff(new DateTime(), new DateTime($_POST['date']));
    } catch (Exception $e) {
    }

    $result = $dateDiff->format('%Y');
    $user_id = $session->getKey('user_infos')->id;

    if ($user->setBirthDate($result, $_POST['date'], $user_id)){
        $session->setFlash('success', 'Your Birth Date has been set successfully');
    } else {
        if (is_bool($user->setBirthDate($result, $_POST['date'], $user_id))){
            $session->setFlash('alert', 'You must be minimum 13 to access this Website');
        } else{
            $session->setFlash('alert', 'Oops! Something went wrong');
        }
    }
}
?>
<?php require_once 'Include/Header.php'; ?>
<div class="login-box">
    <h2>Set Birth Date</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="user-box">
            <input type="date" name="date" required="">
            <label></label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
