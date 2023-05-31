<?php
require_once './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
require_once 'Aes.php';
require_once './email.php';
require_once 'Permission.php';

class Pdo_
{
    private $db;
    private $purifier;

    private $perm;
    private $aes;
    private $log_2F_step1;


    private $mail;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=news', 'root', '');

            $this->aes = new Aes();

            $this->perm = new Permission();

            $this->mail = new M();

        } catch (PDOException $e) {
            // add relevant code
            die();
        }
    }

    public function add_user($login, $email, $password, $twofa)
    {
        //generate salt
        $salt = random_bytes(16);
        $pepper = 'cyberbezpieczenstwo';
        $password = hash('sha512', $password . $salt);

        //echo $password . $salt; 
        $login = $this->purifier->purify($login);
        $email = $this->purifier->purify($email);



        try {
            $sql = "INSERT INTO `user`( `login`, `email`, `hash`, `salt`, `id_status`, `password_form`, `2fa`) VALUES (:login,:email,:hash,:salt,:id_status,:password_form,:2fa)";

            $data = [
                'login' => $login,
                'email' => $email,
                'hash' => $password,
                'salt' => $salt,
                'id_status' => '1',
                'password_form' => '1',
                '2fa' => (int) $twofa,
            ];
            $this->db->prepare($sql)->execute($data);
        } catch (Exception $e) {
            //modify the code here
            print 'Exception' . $e->getMessage();
        }
    }



    public function log_user_in($login, $password)
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT id,hash,login,salt FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();



            if (empty($user_data)) {
                echo 'There is no user with login ' . $login;
                return false;
            }

            $password = hash('sha512', $password . $user_data['salt']);

            if ($password == $user_data['hash']) {
                echo 'login successfull<BR/>';
                echo 'You are logged in as: ' . $user_data['login'] . '<BR/>';

                $_SESSION['login'] = $user_data['login'];
                $this->perm->save_sing_in($user_data['login']);
            } else {
                echo 'login FAILED<BR/>';
            }
        } catch (Exception $e) {

            print 'Exception' . $e->getMessage();
        }
    }

    public function change_password($old_password, $password, $password2)
    {

        if ($password != $password2) {
            echo 'Passwords are not same';
            return false;
        }

        $login = $_SESSION['login'];

        $sql = "SELECT hash, salt FROM user WHERE login=:login";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['login' => $login]);
        $user_data = $stmt->fetch();


        $old_hash = hash('sha512', $old_password . $user_data['salt']);

        if ($old_hash != $user_data['hash']) {
            echo 'Old password is not correct';
            return false;
        }


        $salt = random_bytes(16);

        $hash = hash('sha512', $password . $salt);


        $sql = "UPDATE user SET hash=:hash, salt=:salt WHERE login=:login";

        $data = [
            'login' => $login,
            'hash' => $hash,
            'salt' => $salt,
        ];

        $this->db->prepare($sql)->execute($data);

        echo 'Password changed';
    }
    public function log_2F_step1($login, $password)
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT id,hash,login,salt,email,2fa FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            $password = hash('sha512', $password . $user_data['salt']);

            if (!$user_data['2fa']) {
                // echo 'Login successfull<BR/>';

                if ($password == $user_data['hash']) {
                    $result = [
                        'result' => 'logged_in',
                        'user_id' => $user_data['id']
                    ];

                    return $result;
                } else {
                    echo 'login FAILED<BR/>';
                    $result = [
                        'result' => 'failed'
                    ];
                    return $result;
                }
            }

            if ($password == $user_data['hash']) {


                //generate and send OTP
                $otp = random_int(100000, 999999);
                $code_lifetime = date('Y-m-d H:i:s', time() + 300);
                try {
                    $sql = "UPDATE `user` SET `sms_code`=:code, `code_timelife`=:lifetime WHERE login=:login";
                    $data = [
                        'login' => $login,
                        'code' => $otp,
                        'lifetime' => $code_lifetime
                    ];
                    $this->db->prepare($sql)->execute($data);

                    $this->mail->send_email($user_data['email'], $otp);

                    //add the code to send an e-mail with OTP
                    $result = [
                        'result' => 'success'
                    ];
                    return $result;
                } catch (Exception $e) {
                    print 'Exception' . $e->getMessage();
                    //add necessary code here
                }
            } else {
                echo 'login FAILED<BR/>';
                $result = [
                    'result' => 'failed'
                ];
                return $result;
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            //add necessary code here
        }
    }

    public function log_2F_step2($login, $code)
    {
        $login = $this->purifier->purify($login);
        $code = $this->purifier->purify($code);
        try {
            $sql = "SELECT id,login,sms_code,code_timelife FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            if (
                $code == $user_data['sms_code']
                && time() < strtotime($user_data['code_timelife'])
            ) {
                //login successfull
                echo 'Login successfull<BR/>';

                $_SESSION['login'] = $user_data['login'];

                $_SESSION['session_expire'] = time();


                $this->perm->save_sing_in($user_data['login']);
                $this->perm->add_permissions_and_roles_to_user_session($user_data['user_id']);



                return true;
            } else {
                echo 'login FAILED<BR/>';
                return false;
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }



    /*
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

} //END OF CLASS