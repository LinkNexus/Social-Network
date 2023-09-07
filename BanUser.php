<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$admin = App::getAdmin();
$validator = App::getValidator();

$admin->restrict();

if (!str_contains($session->getKey('user_infos')->status, 'admin') || !$admin->has('id')){
    App::redirect('Posts.php');
}

if ($validator->isPosted()){
    $result = $link->query('SELECT * FROM users WHERE id = :id', [
        'id' => App::get('id')
    ])->fetch();

    $admin->banUser($result, $_POST['ban_period']);
    $session->setFlash('success', 'The User has been successfully banned for '. $_POST['ban_period'] .' days');
    App::redirect('Posts.php');
}

?>
<?php require_once 'Include/Header.php'; ?>
<div class="login-box">
    <h2>Ban User</h2>
    <form action="" method="post">
        <div class="user-box">
            <input type="number" name="ban_period">
            <label>Period of Ban (in Days)</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
