<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

?>
<?php require_once 'Include/Header.php'; ?>
<div id="navBar">
    <h2>SETTINGS</h2>
    <a href="ChangeUsername.php" class="nav-links">
        <span class="material-symbols-outlined">badge</span>
        <span class="icon-text">Change Username</span>
    </a>
    <a href="ChangePassword.php" class="nav-links">
        <span class="material-symbols-outlined">password</span>
        <span class="icon-text">Change Password</span>
    </a>
    <?php if ($session->getKey('mode') == null): ?>
        <a href="SetMode.php?mode=1" class="nav-links">
            <span class="material-symbols-outlined">light_mode</span>
            <span class="icon-text">Blue Mode</span>
        </a>
    <?php else: ?>
        <a href="SetMode.php" class="nav-links">
            <span class="material-symbols-outlined">dark_mode</span>
            <span class="icon-text">Red Mode</span>
        </a>
    <?php endif; ?>
    <a href="SetBirthDate.php" class="nav-links">
        <span class="material-symbols-outlined">event</span>
        <span class="icon-text">Set Birth Date</span>
    </a>
    <a href="#" class="nav-links" id="deleteAccount">
        <span class="material-symbols-outlined">delete</span>
        <span class="icon-text">Delete Account</span>
    </a>
</div>
<script>
    let deleteAccountLink = document.getElementById('deleteAccount');

    deleteAccountLink.addEventListener('click', function (evt) {
        evt.preventDefault();
        if (window.confirm('You are deleting your Account')){
            window.location = 'DeleteAccount.php';
        }
    })
</script>
<?php require_once 'Include/Mode.php' ?>
</body>
</html>
