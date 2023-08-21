<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

?>
<?php require_once 'Include/Header.php'; ?>
<div id="navBar">
    <h2>NAVIGATE</h2>
    <a href="SetAvatar.php" class="nav-links">
        <span class="material-symbols-outlined">upload</span>
        <span class="icon-text">Set Avatar</span>
    </a>
    <a href="DisplayAvatar.php" class="nav-links">
        <span class="material-symbols-outlined">pan_zoom</span>
        <span class="icon-text">Display Avatar</span>
    </a>
    <a href="DeleteAvatar.php" class="nav-links">
        <span class="material-symbols-outlined">delete</span>
        <span class="icon-text">Delete Avatar</span>
    </a>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>

