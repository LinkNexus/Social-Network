<?php

use JetBrains\PhpStorm\NoReturn;

class User
{

    /* If one or more Message could be used all over the project,
    create an array for the said Messages */

    protected array $options = [
        'restriction_msg' => 'You are not yet authorized to access this page'
    ];

    protected Validator $validator;

    public function __construct(protected Database $link, protected Session $session, array $options = [])
    {
        /* If you have other generic Messages,
        you could replace the one by default with it */

        $this->options = array_merge($this->options, $options);
        $this->validator = new Validator($_POST);
    }

    /* Static Method used to check if values have been passed through the URL */

    public function has($key): bool
    {
        return isset($_GET[$key]);
    }

    /* In order to permit access of the user to specific pages,
    create a Method having the Instructions for the Restriction */

    public function restrict(): void
    {
        if (!$this->session->getKey('user_infos')) {
            $this->session->setFlash('alert', $this->options['restriction_msg']);
            App::redirect('Login.php');
        }
    }

    /* Static Method to check if the user is connected to the website */
    public function isUserConnected(): bool
    {
        if ($this->session->getKey('user_infos')){
            return true;
        }

        return false;
    }

    /* Static Method to connect the user to the website */

    public function connect($result): void
    {
        $this->session->addKey('user_infos', $result);
    }

    /* Method grouping all the instructions for the sign-up of a new User */

    public function register(string $username, string $password, string $email): void
    {
        $password = App::hashPassword($password);
        $token = Str::random(60);

        $this->link->query('INSERT INTO users(username, email, password, confirmation_token, status) VALUES (:username, :email, :password, :confirmation_token, :status)',
            [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'confirmation_token' => $token,
                'status' => 'user'
            ]
        );

        $directory = 'Mini_Blog'; /* The name of the directory where the entire project is */

        $full_URL = "http". (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') .'://'. $_SERVER['HTTP_HOST'] . '/'. (empty($directory) ? '' : $directory .'/') .'Confirm.php';

        try
        {
            $user_id = $this->link->lastInsertId();
            App::sendWithGmail($email, 'Account Confirmation', "In order to confirm your Account, click on this link\n\n$full_URL?id=$user_id&token=$token");
        } catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /* Method grouping all the instructions for the confirmation of the sign-up of a new User */
    public function confirm(int $user_id, string $token): bool
    {
        $result = $this->link->query('SELECT * FROM users WHERE id = :id', ['id' => $user_id])->fetch();

        if ($result && $result->confirmation_token === $token) {
            $this->link->query('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = :id', ['id' => $user_id]);
            $this->session->addKey('user_infos', $result);
            return true;
        }

        return false;
    }

    /* Method to check if there is a Cookie containing the connection information
    of the user and if yes, it connects the user */

    public function reconnectFromCookie(): void
    {
        if (isset($_COOKIE['remember']) && !$this->isUserConnected()) {
            $remember_token = $_COOKIE['remember'];
            $parts = explode('==', $remember_token);

            $user_id = $parts[0];
            $result = $this->link->query('SELECT * FROM users WHERE id = :id', ['id' => $user_id])->fetch();

            if ($result) {
                $expected = $user_id . '==' . $result->remember_token . sha1($user_id . 'TheBlog');

                if ($expected == $remember_token) {
                    $this->connect($result);
                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                } else {
                    setcookie('remember', NULL, -1);
                }
            } else {
                setcookie('remember', NULL, -1);
            }
        }
    }

    /* Method to create Cookie stocking the Information Connection of the user */

    public function remember($result): void
    {
        $remember_token = Str::random(250);

        $this->link->query('UPDATE users SET remember_token = :remember_token WHERE id = :id', [
            'remember_token' => $remember_token,
            'id' => $result->id
        ]);

        setcookie('remember', $result->id . '==' . $remember_token . sha1($result->id . 'TheBlog'), time() + 60 * 60 * 24 * 7);
    }


    /* Method grouping all the instructions for the sign-in of a user to the website */

    public function login(string $username, string $password, $remember = false): bool|object
    {
        $result = $this->link->query('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL', ['username' => $username])->fetch();

        if ($result && password_verify($password, $result->password)) {
            $this->connect($result);

            if ($remember) {
                $this->remember($result);
            }

            return $result;
        }

        return false;

    }

    /* Method used to log out the user */

    public function logout(): void
    {
        setcookie('remember', NULL, -1);
        $this->session->delete('user_infos');
    }

    /* Method used to check the Email of a user during for a Password Reset */

    public function verifyEmail($email)
    {
        $result = $this->link->query('SELECT * FROM users WHERE email = :email AND confirmed_at IS NOT NULL', ['email' => $email])->fetch();

        if ($result) {
            $reset_token = Str::random(60);

            $this->link->query('UPDATE users SET reset_token = :reset_token, reset_at = NOW() WHERE id = :id', [
                'reset_token' => $reset_token,
                'id' => $result->id
            ]);

            $directory = 'Mini_Blog'; /* The name of the directory where the entire project is */

            $full_URL = "http". (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') .'://'. $_SERVER['HTTP_HOST'] . '/'. (empty($directory) ? '' : $directory .'/') .'ResetPassword.php';

            try {
                App::sendWithGmail($email, 'Reset of your Password', "In order to reset your Password, click on this link\n\n$full_URL?id=$result->id&token=$reset_token");
            } catch (Exception $e)
            {
                echo $e->getMessage();
            }

            return $result;
        }

        return false;
    }

    public function changeUsername($result, $user_id, $username): bool|array
    {
        if ($result) {

            $this->validator->isAlphanumeric('username', $username);

            if ($this->validator->isValid()) {

                $this->link->query('UPDATE users SET username = :username, modified_at = NOW() WHERE id = :id', [
                    'username' => $_POST['username'],
                    'id' => $user_id
                ]);

                $result = $this->link->query('SELECT * FROM users WHERE id = :id', ['id' => $user_id])->fetch();

                $this->connect($result);

                return true;
            }

            return $this->validator->getErrors();
        }

        return false;
    }

    public function changePassword($password): bool|array
    {
        if (password_verify($password, $this->session->getKey('user_infos')->password)) {

            $this->validator->isConfirmed(['new_password', 'confirm_password'], ['Password must not be empty and must contain at least 5 characters', 'Passwords do not match']);

            if ($this->validator->isValid()) {

                $user_id = $this->session->getKey('user_infos')->id;
                $password = App::hashPassword($_POST['new_password']);

                $this->link->query('UPDATE users SET password = :password WHERE id = :id', [
                    'password' => $password,
                    'id' => $user_id
                ]);

                return true;

            }

            return $this->validator->getErrors();

        }

        return false;
    }

    public function resetPassword($result, $password): bool|array
    {
        $this->validator->isConfirmed(['new_password', 'confirm_password'], ['Password must not be empty and must contain at least 5 characters', 'Passwords do not match']);

        if ($this->validator->isValid()) {
            $password = App::hashPassword($password);

            $this->link->query('UPDATE users SET password = :password, reset_at = NULL, reset_token = NULL WHERE id = :id', [
                'password' => $password,
                'id' => $result->id
            ]);

            $this->connect($result);

            return true;
        }

        return $this->validator->getErrors();

    }

    public function deleteAccount($result, $password): bool
    {
        if (password_verify($password, $result->password)){
            $this->link->query('DELETE FROM users WHERE id = :id', ['id' => $result->id]);
            $this->logout();
            return true;
        }

        return false;
    }

    public function setBirthDate($result, $date, $user_id): ?bool
    {
        if ($result) {
            if ($result >= 13) {
                $this->link->query('UPDATE users SET born_at = :birth_at WHERE id = :id', [
                    'birth_at' => $date,
                    'id' => $user_id
                ]);

                $result = $this->link->query('SELECT * FROM users WHERE id = :id', [
                    'id' => $user_id
                ])->fetch();

                $this->connect($result);

                return true;
            }

            return false;
        }

        return NULL;
    }

    public function upLoadFile(array $file, int $fileWeight, array $allowedExtensions): ?bool
    {
        if ($file['size'] <= $fileWeight){
            $fileInfo = pathinfo($file['name']);
            $extension = $fileInfo['extension'];

            if (in_array($extension, $allowedExtensions)) {
                return true;
            }

            return false;
        }

        return null;
    }

    #[NoReturn] public function displayAvatar(): void
    {
        if ($this->session->getKey('user_infos')->avatar === null){
            App::redirect('Assets/avatar.jpeg');
        } else {
            App::redirect('Uploads/Avatars/'. $this->session->getKey('user_infos')->avatar);
        }
    }

    public function deleteAvatar(): void
    {
        $user_id = $this->session->getKey('user_infos')->id;
        $current_avatar = $this->session->getKey('user_infos')->avatar;
        $this->link->query('UPDATE users SET avatar = NULL WHERE id = :id', ['id' => $user_id]);

        App::deleteFile($current_avatar, '/Uploads/Avatars/');

        $result = $this->link->query('SELECT * FROM users WHERE id = :id', ['id' => $user_id])->fetch();
        $this->connect($result);
    }

}