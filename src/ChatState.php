<?php

namespace Builov\MashaBot;


use mysqli_result;

class ChatState
{

    //todo сделать из этого класса DTO, методы вынести в модель


    public static array $chat_states = [
        1 => 'pending_date',
        2 => 'pending_mood',
        3 => 'pending_comment',

        4 => 'sleep_duration',
        5 => 'sleep_depth',
        6 => 'morning_feeling',
        7 => 'discomfort',
        8 => 'activity',
        9 => 'attention',
        10 => 'mood_swings',
        11 => 'tearfulness',
        12 => 'irritability'
    ];
    public static array $replicas = [

    ];
    public Request $request;
    public string $date;
    public int $current_state;
    private Message $message;
    private Db $db;


//    function __construct($message)
//    {
//        $this->message = $message;
//        $this->db = new Db();
//    }

    function __construct($request)
    {
        $this->request = $request;

//        $this->message = $message;
        $this->db = new Db();
    }

    public function save($message): \mysqli_result|bool
    {
        $this->message = $message;

//        var_dump($this->message); exit;

        $state = array_search($this->message->properties['set_state'], self::$chat_states);

        $sql = (isset($this->date))
            ? "INSERT INTO `chat_state` (`user_id`, `status_id`, `diary_date`)
                VALUES ('{$this->message->request->chat_id}', $state, '{$this->date}')
                ON DUPLICATE KEY UPDATE `status_id` = $state, `diary_date` = '{$this->date}'"
            : "INSERT INTO `chat_state` (`user_id`, `status_id`) 
                VALUES ('{$this->message->request->chat_id}', $state)
                ON DUPLICATE KEY UPDATE `status_id` = $state, `diary_date` = null";
        return $this->db->execute($sql);
    }

    /**
     * Дата не проверяется, т.к. при соответстви статуса ожидаемому просто присваивается новая дата
     * (например, начал заполняь и бросил, потом вернулся)
     * (хотя нет, тогда ранее введенные двнные могут относится к другой дате)
     * @return bool
     */
    public function check($message): bool
    {
        $this->message = $message;

        //todo если осутствует $this->message->properties['pending_state'] ?
        $pending_state = (int) array_search($this->message->properties['pending_state'], self::$chat_states);

        if (!isset($this->current_state)) {
            $this->get();
        }

        return !empty($this->current_state) && $this->current_state == $pending_state;
    }

    public function clear(): bool|mysqli_result
    {
        $sql = "DELETE FROM `chat_state` WHERE `user_id` = {$this->request->chat_id}";

        return $this->db->execute($sql);
    }

    public function get(): array|false|null
    {
        $sql = "SELECT `status_id`, `diary_date` FROM `chat_state` WHERE `user_id` = {$this->request->chat_id}";

        if ($result = $this->db->execute($sql)) {
            $result_array = mysqli_fetch_assoc($result);

            $this->set($result_array['status_id'], $result_array['diary_date']);

            return $result_array;
        }

        return null;
    }

    public function set($status_id, $diary_date): void
    {
        if ($diary_date) {
            $this->date = $diary_date;
        }
        $this->current_state = $status_id;
    }
}