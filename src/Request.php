<?php

namespace Builov\MashaBot;

class Request
{
    public mixed $chat_id;
    public mixed $user_name;
    public mixed $first_name;
    public mixed $last_name;
    public string $text;
    public array $text_array;

    function __construct()
    {
        //$data = file_get_contents('php://input');
        //$data = json_decode($data, true);

        $data = [
            'update_id' => 864016577,
            'message' => [
                'message_id' => 312,
                'from' => [
                    'id' => 497026734,
                    'is_bot' => false,
                    'first_name' => 'Дмитрий',
                    'last_name' => 'Дмитрий',
                    'username' => 'Buyloff',
                    'language_code' => 'ru'
                ],
                'chat' => [
                    'id' => 497026734,
                    'first_name' => 'Дмитрий',
                    'last_name' => 'Дмитрий',
                    'username' => 'Buyloff',
                    'type' => 'private'
                ],
                'date' => '1688905553',
//                'text' => '/mood_chart'
//                'text' => '/help'
//                'text' => '/scale'
//                'text' => 'Сегодня'
//                'text' => 'Нормальное настроение. Спокойный, сбалансированный настрой.'
            ]
        ];

        $this->chat_id = $data['message']['from']['id'];
        $this->user_name = $data['message']['from']['username'];
        $this->first_name = $data['message']['from']['first_name'];
        $this->last_name = $data['message']['from']['last_name'];
        $this->text = trim($data['message']['text']);
        $this->text_array = explode(" ", $this->text);

        $this->log($data);
    }

    public function is_empty(): bool
    {
        //todo предусмотреть картинки и т.д.
        return empty($this->text);
    }

    private function log($data): void
    {
        file_put_contents(__DIR__ . '/../log.php', print_r($data, true), FILE_APPEND);
    }

    public function getDateTextValues(): array
    {
        return DiaryEntryDate::getTextValues();
    }

    public function getMoodTextValues(): array
    {
        return Mood::$values;
    }
}