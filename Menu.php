<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

?>
<?php require_once 'Include/Header.php'; ?>
<div id="navBar">
    <h2>NAVIGATE</h2>
    <a href="Index.php" class="nav-links">
        <span class="material-symbols-outlined">home</span>
        <span class="icon-text">HOME</span>
    </a>
    <a href="Account.php" class="nav-links">
        <span class="material-symbols-outlined">account_circle</span>
        <span class="icon-text">ACCOUNT</span>
    </a>
    <a href="Posts.php" class="nav-links">
        <span class="material-symbols-outlined">token</span>
        <span class="icon-text">POSTS</span>
    </a>
    <a href="Search.php" class="nav-links">
        <span class="material-symbols-outlined">search</span>
        <span class="icon-text">SEARCH</span>
    </a>
    <a href="Profile.php" class="nav-links">
        <span class="material-symbols-outlined">person</span>
        <span class="icon-text">PROFILE</span>
    </a>
    <a href="Settings.php" class="nav-links">
        <span class="material-symbols-outlined">settings</span>
        <span class="icon-text">SETTINGS</span>
    </a>
</div>
<div id="social-media">
    <h2>FOLLOW US ON</h2>
    <div id="links">
        <a href="#" class="fa fa-facebook"></a>
        <a href="#" class="fa fa-instagram"></a>
        <a href="#" class="fa fa-linkedin"></a>
        <a href="#" class="fa fa-twitter"></a>
        <a href="#" class="fa fa-whatsapp"></a>
    </div>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
