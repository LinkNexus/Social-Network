<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

?>
<?php require_once 'Include/Header.php'?>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
