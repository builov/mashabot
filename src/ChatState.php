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


    function __construct($bot_message)
    {
        $this->message = new Message($bot_message);
    }

    public function save()
    {
        $db = new Db();
        $sql = (isset($this->diary_date))
            ? "INSERT INTO `chat_state` (`user_id`, `status_id`, `diary_date`)
                VALUES ('{$this->request->chat_id}', $state, '$this->diary_date')
                ON DUPLICATE KEY UPDATE `status_id` = $state, `diary_date` = '$this->diary_date'"
            : "INSERT INTO `chat_state` (`user_id`, `status_id`) 
                VALUES ('{$this->request->chat_id}', $state)";
        return $db->execute($sql);
    }

    public function check()
    {
        return isset($state_data['status_id']) && $this->chat_states[$state_data['status_id']] == $state;
    }

    public function clear()
    {
        $db = new Db();
        $sql = "DELETE FROM `chat_state` WHERE `user_id` = {$this->request->chat_id}";
        return $db->execute($sql);
    }

    public function get($chat_id): array|false|null
    {
        $db = new Db();
        $sql = "SELECT `status_id`, `diary_date` FROM `chat_state` WHERE `user_id` = $chat_id";

        if ($result = $db->execute($sql)) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }
}