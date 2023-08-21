<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

?>
<?php require_once 'Include/Header.php' ?>
<div class="login-box">
    <form action="Posts.php" method="post">
        <h2>SEARCH</h2>
        <div class="user-box">
            <input type="text" name="search" required>
            <label>What do you search?</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
