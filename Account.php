<?php

require_once 'Include/Load.php';

$link = App::getDatabase();
$session = App::getSession();
$user = App::getUser();
$user->restrict();

?>

<?php require_once 'Include/Header.php'; ?>
<div class="info-box">
    <h2>Account</h2>
    <?php if (!$user->has('id')): ?>
        <div class="info">
            Username: <?php echo $session->getKey('user_infos')->username; ?>
        </div>
        <div class="info">
            Email: <?php echo $session->getKey('user_infos')->email; ?>
        </div>
        <div class="info">
            Account's Date of Creation: <?php echo $session->getKey('user_infos')->confirmed_at; ?>
        </div>
        <div class="info">
            Time Remaining before being able to change Username again:
            <?php

            $result = $link->query('SELECT DATEDIFF(DATE_SUB(modified_at, INTERVAL -1 WEEK), NOW()) as date FROM users WHERE id = :id AND modified_at IS NOT NULL', [
                    'id' => $session->getKey('user_infos')->id
            ])->fetch();

            if ($result){
                if ($result->date < 0){
                    echo '0 days';
                } else {
                    echo $result->date;
                }
            } else {
                echo '0 days';
            }

            ?>
        </div>
        <?php if (!empty($session->getKey('user_infos')->born_at)): ?>
            <div class="info">
                Age:
                <?php

                $result = $link->query('SELECT DATEDIFF(NOW(), born_at) as date FROM users WHERE id = :id', [
                        'id' => $session->getKey('user_infos')->id
                ])->fetch();

                echo intval($result->date / 365.25) . ' Years';

                ?>
            </div>
        <?php endif; ?>
        <div class="info">
            Number of Posts:
            <?php

            $posts = $link->query('SELECT * FROM posts WHERE user_id = :id', ['id' => $session->getKey('user_infos')->id])->fetch();

            if ($posts){
                if (is_array($posts)){
                    echo count($posts);
                } else {
                    echo 1;
                }
            } else {
                echo 0;
            }

            ?>
        </div>
    <?php else: ?>
    <?php $result = $link->query('SELECT * FROM users WHERE id = :id', ['id' => App::get('id')])->fetch(); ?>
        <div class="info">
            Username: <?php echo $result->username; ?>
        </div>
        <div class="info">
            Email: <?php echo $result->email; ?>
        </div>
        <div class="info">
            Account's Date of Creation: <?php echo $result->confirmed_at; ?>
        </div>
        <div class="info">
            Time Remaining before being able to change Username again:
            <?php

            $Result = $link->query('SELECT DATEDIFF(DATE_SUB(modified_at, INTERVAL -1 WEEK), NOW()) as date FROM users WHERE id = :id AND modified_at IS NOT NULL', [
                'id' => $result->id
            ])->fetch();

            if ($Result){
                if ($Result->date < 0){
                    echo '0 days';
                } else {
                    echo $Result->date;
                }
            } else {
                echo '0 days';
            }

            ?>
        </div>
        <?php if (!empty($sresult->born_at)): ?>
            <div class="info">
                Age:
                <?php

                $Result = $link->query('SELECT DATEDIFF(NOW(), born_at) as date FROM users WHERE id = :id', [
                    'id' => $Result->id
                ])->fetch();

                echo intval($Result->date / 365.25) . ' Years';

                ?>
            </div>
        <?php endif; ?>
        <div class="info">
            Number of Posts:
            <?php

            $posts = $link->query('SELECT * FROM posts WHERE user_id = :id', ['id' => $result->id])->fetch();

            if ($posts){
                if (is_array($posts)){
                    echo count($posts);
                } else {
                    echo 1;
                }
            } else {
                echo 0;
            }

            ?>
        </div>
    <?php endif; ?>
</div>
<?php require_once 'Include/Mode.php'; ?>
</body>
</html>
