<?php require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

?>
<?php require_once 'Include/Header.php'; ?>
<style>
    .avatar img{
        display: block;
        height: 400px;
        aspect-ratio: 1;
        border-radius: 50%;
        margin-bottom: 30px;
        cursor: pointer;
    }

    .avatar a{
        height: 90px;
        aspect-ratio: 1;
        background: none;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-content: center;
        position: relative;
        z-index: 10;
        left: 275px;
        bottom: 100px;
        font-size: 60px;
        color: red;
        transition: 0.5s;
        padding: 15px;
        box-sizing: border-box;
    }

    .avatar a:hover{
        color: white;
        text-decoration: none;
        background-color: red;
        box-shadow: 0 0 5px red,
        0 0 25px red,
        0 0 50px red,
        0 0 100px red;
    }

    h1 {
        padding: 0;
        color: #fff;
        text-align: center;
        font-size: 40px;
        margin-bottom: 10px;
    }

    .profileBlock{
        cursor: pointer;
    }

    .status{
        display: block;
        color: grey;
        text-align: center;
        font-size: 30px;
        font-style: italic;
    }
</style>
<?php if ($user->has('id')): ?>

    <?php if (App::get('id') != $session->getKey('user_infos')->id): ?>
        <div class="avatar">
            <img alt="profile_pic" src="<?php

            $result = $link->query('SELECT * FROM users WHERE id = :id', ['id' => App::get('id')])->fetch();

            if ($result->avatar === null){
                echo 'Assets/avatar.jpeg';
            } else {
                echo 'Uploads/Avatars/'. $result->avatar;
            }

            ?>">
            <div class="profileBlock">
                <h1>
                    <?= $result->username ?>
                </h1>
                <?php if ($result->status == 'admin'): ?>
                    <span class="status">Admin</span>
                <?php endif; ?>
            </div>
            <script>
                let avatarImage = document.querySelector('.avatar img'),
                    profileBlock = document.querySelector('.profileBlock');

                avatarImage.addEventListener('click', function () {
                    document.location = '<?php if ($result->avatar === null){
                    echo 'Assets/avatar.jpeg';
                    } else {
                        echo 'Uploads/Avatars/'. $result->avatar;
                    } ?>';
                })

                profileBlock.addEventListener('click', function () {
                    document.location = "<?= 'Account.php?id='. $result->id; ?>"
                })
            </script>
        </div>
    <?php else: ?>
        <div class="avatar">
            <img alt="profile_pic" src="<?php

            if ($session->getKey('user_infos')->avatar === null){
                echo 'Assets/avatar.jpeg';
            } else {
                echo 'Uploads/Avatars/'. $session->getKey('user_infos')->avatar;
            }

            ?>">
            <a href="AvatarMenu.php" class="material-symbols-outlined">Camera</a>
            <div class="profileBlock">
                <h1>
                    <?= $session->getKey('user_infos')->username ?>
                </h1>
                <?php if ($session->getKey('user_infos')->status == 'admin'): ?>
                    <span>Admin</span>
                <?php endif; ?>
            </div>
            <script>
                let avatarImage = document.querySelector('.avatar img'),
                    profileBlock = document.querySelector('.profileBlock');

                avatarImage.addEventListener('click', function () {
                    document.location = '<?php if ($session->getKey('user_infos')->avatar === null){
                    echo 'Assets/avatar.jpeg';
                    } else {
                        echo 'Uploads/Avatars/'. $session->getKey('user_infos')->avatar;
                    } ?>'
                })

                profileBlock.addEventListener('click', function () {
                    document.location = "Account.php";
                })
            </script>
            <style>
                .profileBlock{
                    margin-top: -90px;
                }
            </style>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="avatar">
        <img alt="profile_pic" src="<?php

        if ($session->getKey('user_infos')->avatar === null){
            echo 'Assets/avatar.jpeg';
        } else {
            echo 'Uploads/Avatars/'. $session->getKey('user_infos')->avatar;
        }

        ?>">
        <a href="AvatarMenu.php" class="material-symbols-outlined">Camera</a>
        <div class="profileBlock">
            <h1>
                <?= $session->getKey('user_infos')->username ?>
            </h1>
            <?php if ($session->getKey('user_infos')->status == 'admin'): ?>
                <span>Admin</span>
            <?php endif; ?>
        </div>
        <script>
            let avatarImage = document.querySelector('.avatar img'),
                profileBlock = document.querySelector('.profileBlock');

            avatarImage.addEventListener('click', function () {
                document.location = <?php if ($session->getKey('user_infos')->avatar === null){
                    echo 'Assets/avatar.jpeg';
                } else {
                    echo 'Uploads/Avatars/'. $session->getKey('user_infos')->avatar;
                } ?>
            })

            profileBlock.addEventListener('click', function () {
                document.location = "Account.php";
            })
        </script>
        <style>
            .profileBlock{
                margin-top: -90px;
            }
        </style>
    </div>
<?php endif; ?>
<?php include_once 'Include/Mode.php' ?>
</body>
</html>

