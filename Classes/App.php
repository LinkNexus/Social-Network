<?php

use JetBrains\PhpStorm\NoReturn;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once dirname(__DIR__) . '/vendor/phpmailer/src/Exception.php';
require_once dirname(__DIR__) . '/vendor/phpmailer/src/PHPMailer.php';
require_once dirname(__DIR__) . '/vendor/phpmailer/src/SMTP.php';

class App
{

    protected static ?Database $link = null;

    protected static ?Session $session = null;

    public function __construct()
    {}

    /* Static Method used to instantiate the class Session with the same parameters all over the code and
    to prevent the presence of 2 Instances of the Class */

    public static function getSession(): Session
    {
        if (!self::$session){
            self::$session = new Session();
        }

        return self::$session;
    }

    /* Static Method used to instantiate the class Database with the same parameters all over the code and
    to prevent the presence of 2 Instances of the Class */

    public static function getDatabase(): Database
    {
        if (!self::$link) {
            self::$link = new Database('mini-blog', 'root', '');
        }

        return self::$link;
    }

    /* Static Method used to get an Instance of the class Auth avoiding inserting the required
    parameters all along */

    public static function getUser(): User
    {
        return new User(self::getDatabase(), self::getSession());
    }

    public static function getAdmin(): Admin
    {
        return new Admin(self::getDatabase(), self::getSession());
    }

    public static function getSuperAdmin(): SuperAdmin
    {
        return new SuperAdmin(self::getDatabase(), self::getSession());
    }

    public static function getValidator(): Validator
    {
        return new Validator($_POST);
    }

    /* Static Method used to get the passed values in the URL */

    public static function get($key)
    {
        return $_GET[$key];
    }

    /* Since the password_hash will be used at different areas of the project,
    create a Method for it. Modifications can easily be set-up through this */

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);  /* The hash method could be modified at once */
    }

    /* Static Method used to redirect the user to a given page and exiting the code to prevent further
    execution of the code */

    #[NoReturn] public static function redirect($page): void
    {
        header('Location: '. $page);
        exit();
    }

    public static function sendWithGmail($to, $subject, $content): void
    {
        // passing true in constructor enables exceptions in PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $username = "nkenengnunlafrancklevy@gmail.com"; // gmail email
            $password = "vqitwblvefidajdw"; // app password

            $mail->Username = $username;
            $mail->Password = $password;

            // Sender and recipient settings
            $mail->setFrom($username, '');
            $mail->addAddress($to, '');

            // Setting the email content
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $content;

            $mail->send();
            echo "Email message sent.";
        } catch (Exception $e) {
            echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    #[NoReturn] public static function setMode(): void
    {
        if (self::getUser()->has('mode')){
            self::getSession()->addKey('mode', '1');
        } else {
            self::getSession()->delete('mode');
        }

        self::redirect('Settings.php');
    }

    public static function unlinkFile ($filename): bool
    {
        // try to force symlinks
        if ( is_link ($filename) ) {
            $sym = @readlink ($filename);
            if ( $sym ) {
                return is_writable ($filename) && @unlink ($filename);
            }
        }

        // try to use real path
        if ( realpath ($filename) && realpath ($filename) !== $filename ) {
            return is_writable ($filename) && @unlink (realpath ($filename));
        }

        // default unlink
        return is_writable ($filename) && @unlink ($filename);
    }

    public static function deleteFile($current_file, $directory): void
    {
        foreach (new DirectoryIterator(dirname(__DIR__) . $directory) as $file){
            if ($file->isFile()){
                if ($current_file === $file->getFilename()){
                    self::unlinkFile(dirname(__DIR__) . $directory . $file->getFilename());
                }
            }
        }
    }

    public static function displayTimeAgo(string $table, $entity): string
    {

        $result = self::getDatabase()->query("SELECT TIMESTAMPDIFF(MINUTE, posted_at, NOW()) as date FROM $table WHERE user_id = :id", [
            'id' => $entity->user_id
        ])->fetch();

        if ($result->date < 60){
            return $result->date. ' Minutes ago';
        } else {
            if ($result->date < 1440){
                return intval($result->date / 60). ' Hours ago';
            } else {
                if ($result->date < 10080){
                    return intval($result->date / 1440). ' Days ago';
                } else {
                    if ($result->date < 40320){
                        return intval($result->date / 10080). ' Weeks ago';
                    } else {
                        if ($result->date < 483840){
                            return intval($result->date / 40320). ' Months ago';
                        } else {
                            return intval($result->date / 483840). ' Years ago';
                        }
                    }
                }
            }
        }
    }

}