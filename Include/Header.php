<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TheBlog</title>
    <link rel="stylesheet" href="Styles/Style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<?php

if (isset(App::getSession()->getKey('user_infos')->id)) {
    if (App::getSession()->getKey('user_infos')->status === 'super_admin'){
        $result = App::getDatabase()->query('SELECT * FROM users WHERE admin_request = 0')->fetch();

        if ($result) {
            echo
                '<script>
                     if (window.confirm("Do approve that '. $result->username .' becomes an Administrator of this Website?")){
                         window.location = "AddAdmin.php?answer=1&id='. $result->id .'";
                     } else {
                         window.location = "AddAdmin.php?answer=0&id='. $result->id .'";
                     }
                </script>'
            ;
        } else {
            $result = App::getDatabase()->query('SELECT * FROM users WHERE remove_admin = 1')->fetch();

            if ($result){
                echo
                    '<script>
                         if (window.confirm("Do you approve to remove '. $result->username .' from the Administrators of this Website?")){
                             window.location = "RemoveAdmin.php?answer=1&id='. $result->id .'";
                         } else {
                             window.location = "RemoveAdmin.php?answer=0&id='. $result->id .'";
                         }
                    </script>'
                ;
            }
        }
    } else {
        $result = App::getDatabase()->query('SELECT * FROM users WHERE id = :id', [
            'id' => App::getSession()->getKey('user_infos')->id
        ])->fetch();

        if ($result->admin_request == 1) {
            echo
                '<script>
                     if (window.confirm("Do you want to become an Administrator of this Website?")){
                         window.location = "AddAdmin.php?value=1";
                     } else {
                         window.location = "AddAdmin.php?value=0";
                     }
                </script>';
        }
    }
}

?>

<div class="nav-bar" id="nav-bar">
    <p class="logo">TheBlog</p>
    <div>
        <?php if (isset($_SESSION['user_infos'])): ?>
            <span>
                <a href="Menu.php"><span>Menu</span> <span class="material-symbols-outlined">menu</span></a>
            </span>
            <span>
                <a href="Logout.php"><span>Logout</span> <span class="material-symbols-outlined">logout</span></a>
            </span>
        <?php else: ?>
            <span>
                <a href="Register.php"><span>Sign Up</span> <span class="material-symbols-outlined">app_registration</span></a>
            </span>
            <span>
                <a href="Login.php"><span>Login</span> <span class="material-symbols-outlined">login</span></a>
            </span>
        <?php endif; ?>
    </div>
</div>

<?php if (App::getSession()->hasFlashes()) : ?>

    <?php foreach (App::getSession()->getFlashes() as $type => $message) : ?>

        <div class="<?php echo $type . '-msg' ?>">
            <?= $message ?>
        </div>

    <?php endforeach; ?>

<?php endif; ?>
