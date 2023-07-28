<?php

namespace Builov\MashaBot;


class ChatState
{
    public static array $chat_states = [
        1 => 'pending_date',
        2 => 'pending_mood',
        3 => 'pending_comment'
    ];
    public string $date;
    public int $current_state;
    private Message $message;


    function __construct($message)
    {
        $this->message = $message;
    }

    public function save(): \mysqli_result|bool
    {
//        var_dump($this->message); exit;

        $state = array_search($this->message->properties['set_state'], self::$chat_states);

        $db = new Db();
        $sql = (isset($this->date))
            ? "INSERT INTO `chat_state` (`user_id`, `status_id`, `diary_date`)
                VALUES ('{$this->message->request->chat_id}', $state, '{$this->date}')
                ON DUPLICATE KEY UPDATE `status_id` = $state, `diary_date` = '{$this->date}'"
            : "INSERT INTO `chat_state` (`user_id`, `status_id`) 
                VALUES ('{$this->message->request->chat_id}', $state)";
        return $db->execute($sql);
    }

    public function check(): bool
    {
        $pending_state = array_search($this->message->properties['pending_state'], self::$chat_states);

        echo $pending_state; exit;

        return isset($state_data['status_id']) && $this->chat_states[$state_data['status_id']] == $pending_state;
    }

    public function clear()
    {
        $db = new Db();
        $sql = "DELETE FROM `chat_state` WHERE `user_id` = {$this->request->chat_id}";
        return $db->execute($sql);
    }

    public function get(): array|false|null
    {
        $db = new Db();
        $sql = "SELECT `status_id`, `diary_date` FROM `chat_state` WHERE `user_id` = {$this->message->request->chat_id}";

        if ($result = $db->execute($sql)) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }
}