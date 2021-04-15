<?php

class ChatUser
{
    private $user_id;
    private $user_name;
    private $user_email;
    private $user_password;
    private $user_status;
    private $user_created_on;
    private $user_verification_code;
    private $user_login_status;
    private $user_profile;
    private $connect;

    public function __construct()
    {
        require_once('Database_connection.php');

        $database_object = new Database_connection;
        $this->connect = $database_object->connect();
    }

    function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    function getUserId()
    {
        return $this->user_id;
    }

    function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    function getUserName()
    {
        return $this->user_name;
    }

    function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }

    function getUserEmail()
    {
        return $this->user_email;
    }

    function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
    }

    function getUserPassword()
    {
        return $this->user_password;
    }

    function setUserStatus($user_status)
    {
        $this->user_status = $user_status;
    }

    function getUserStatus()
    {
        return $this->user_status;
    }

    function setUserCreateOn($user_created_on)
    {
        $this->user_created_on = $user_created_on;
    }

    function getUSerCreateOn()
    {
        return $this->user_created_on;
    }

    function setUserVerificationCode($user_verification_code)
    {
        $this->user_verification_code = $user_verification_code;
    }

    function getUserVerificationCode()
    {
        return $this->user_verification_code;
    }

    function setUserProfile($user_profile)
    {
        $this->user_profile = $user_profile;
    }

    function getUserProfile()
    {
        return $this->user_profile;
    }

    function setUserLoginStatus($user_login_status)
    {
        $this->user_login_status = $user_login_status;
    }

    function getUSerLoginStatus()
    {
        return $this->user_login_status;
    }

    function make_avatar($character)
    {
        $path = "images/".time().".png";
        $image = imagecreate(200,200);
        $red = rand(0, 255);
        $green = rand(0, 255);
        $blue  = rand(0, 255);
        imagecolorallocate($image, $red, $green, $blue);
        $textcolor = imagecolorallocate($image, 255, 255, 255);

        $font = dirname(__FILE__).'/font/arial.ttf';

        imagettftext($image, 100, 0, 55, 150, $textcolor, $font, $character);
        imagepng($image, $path);
        imagedestroy($image);
        return $path;
    }

    function get_user_data_by_email()
    {
        $query = "SELECT *FROM chat_user_table WHERE user_email = :user_email";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':user_email', $this->user_email);

        if($statement->execute())
        {
            $user_data = $statement->fetch(PDO::FETCH_ASSOC);
        }

        return $user_data;
    }

    function save_data()
    {
        $query = "INSERT INTO chat_user_table(user_name, user_email, user_password, user_profile, user_status, user_created_on, user_verification_code) VALUES (:user_name, :user_email, :user_password, :user_profile, :user_status, :user_created_on, :user_verification_code)";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':user_name', $this->user_name);
        $statement->bindParam(':user_email', $this->user_email);
        $statement->bindParam(':user_password', $this->user_password);
        $statement->bindParam(':user_profile', $this->user_profile);
        $statement->bindParam('user_status', $this->user_status);
        $statement->bindParam(':user_created_on', $this->user_created_on);
        $statement->bindParam(':user_verification_code', $this->user_verification_code);

        if($statement->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function is_valid_email_verification_code()
    {
        $query ="SELECT * FROM chat_user_table WHERE user_verification_code = :user_verification_code";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':user_verification_code', $this->user_verification_code);
        $statement->execute();
        if($statement->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function enable_user_account()
    {
        $query = "UPDATE chat_user_table SET user_status = :user_status WHERE user_verification_code = :user_verification_code";

        $statement = $this->connect->prepare($query);

        $statement->bindParam(':user_status', $this->user_status);
        $statement->bindParam(':user_verification_code', $this->user_verification_code);

        if($statement->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function update_user_login_data()
    {
        $query = "UPDATE chat_user_table SET user_login_status = :user_login_status WHERE user_id = :user_id";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':user_login_status', $this->user_login_status);
        $statement->bindParam(':user_id', $this->user_id);

        if($statement->execute())
        {
            return true;
        }
        else{
            return false;
        }
    }

    function get_user_data_by_id()
    {
        $query = "SELECT *FROM chat_user_table WHERE user_id = :user_id";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':user_id', $this->user_id);

        if($statement->execute())
        {
            $user_data = $statement->fetch(PDO::FETCH_ASSOC);
        }

        return $user_data;
    }

    function upload_image($user_profile)
    {
        $extension = explode('.', $user_profile['name']);
        $new_name = rand(). '.'.$extension[1];
        $destination = 'images/' . $new_name;
        move_uploaded_file($user_profile['tmp_name'], $destination);
        return $destination;
    }

    function update_data()
    {
        $query = "UPDATE chat_user_table SET user_name = :user_name, user_email = :user_email, user_password = :user_password, user_profile = :user_profile WHERE user_id = :user_id";

        $statement = $this->connect->prepare($query);

        $statement->bindParam(':user_name', $this->user_name);
        $statement->bindParam(':user_email', $this->user_email);
        $statement->bindParam(':user_password', $this->user_password);
        $statement->bindParam(':user_profile', $this->user_profile);
        $statement->bindParam(':user_id', $this->user_id);

        if($statement->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function get_user_all_data()
	{
		$query = "SELECT * FROM chat_user_table";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		$data = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}
}

?>