 <?php
 
class ChatRooms
{
    private $chat_id;
    private $user_id;
    private $message;
    private $created_on;
    protected $connect;

    public function setChatId($chat_id)
    {
        $this->chat_id = $chat_id;
    }

    function getChatId()
    {
        return $this->chat_id;
    }

    function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    function getUserId()
    {
        return $this->user_id;
    }

    function setMessage($message)
    {
        $this->message = $message;
    }

    function getMessage()
    {
        return $this->message;
    }

    function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }

    function getCreatedOn()
    {
        return $this->created_on;
    }

    public function __construct()
    {
        require_once('Database_connection.php');
        $database_object = new Database_connection;
        $this->connect = $database_object->connect();
    }

    function save_chat()
    {
        $query = "INSERT INTO chatrooms(userid, msg, created_on) VALUE (:userid, :msg, :created_on)";

        $statement = $this->connect->prepare($query);
        $statement->bindParam(':userid', $this->user_id);
        $statement->bindParam(':msg', $this->message);
        $statement->bindParam(':created_on', $this->created_on);

        $statement->execute();
    }
}
 
 ?>