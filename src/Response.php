<?php

namespace Builov\MashaBot;

use Exception;

class Response
{
    public Message $message;
    private ChatState $chat_state;
    /** @var array состав определяется API Telegram */
    private array $postfields;
    /** @var array состав определяется API Telegram */
    private array $content_types = [
        'text' => 'sendMessage',
        'image' => 'sendPhoto'
    ];

    function __construct($message)
    {
        $this->message = $message;
    }

    public function send(): void
    {
        $this->postfields = [
            'chat_id' => $this->message->request->chat_id
        ];
        if (isset($this->message->properties['reply_markup'])) {
            $this->postfields['reply_markup'] = (is_array($this->message->properties['reply_markup']))
                ? json_encode($this->message->properties['reply_markup'])
                : $this->message->properties['reply_markup'];
        }
//        $this->postfields['reply_markup'] = '';
        $this->postfields['parse_mode'] = (isset($this->message->properties['parse_mode'])) ? $this->message->properties['parse_mode'] : null;
        $this->postfields['text'] = (isset($this->message->properties['response_text'])) ? $this->message->properties['response_text'] : null;
        $this->postfields['photo'] = (isset($this->message->properties['response_image'])) ? curl_file_create(__DIR__ . '/../' . $this->message->properties['response_image']) : null;

        print_r($this->postfields);

        $ch = curl_init();
        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . BOT_TOKEN . '/' . $this->content_types[$this->message->properties['response_type']],
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
//        CURLOPT_HEADER => false,
            CURLOPT_POSTFIELDS => $this->postfields
        ];

        curl_setopt_array($ch, $ch_post);
        curl_exec($ch);
//    curl_close($ch);
    }





//    private function save_state($state): \mysqli_result|bool
//    {
//        $db = new Db();
//        $sql = (isset($this->diary_date))
//            ? "INSERT INTO `chat_state` (`user_id`, `status_id`, `diary_date`)
//                VALUES ('{$this->request->chat_id}', $state, '$this->diary_date')
//                ON DUPLICATE KEY UPDATE `status_id` = $state, `diary_date` = '$this->diary_date'"
//            : "INSERT INTO `chat_state` (`user_id`, `status_id`)
//                VALUES ('{$this->request->chat_id}', $state)";
//        return $db->execute($sql);
//    }

    /**
     * @throws Exception
     */
//    private function set_date(): void
//    {
//        if (in_array($this->request->text, $this->date_values)) {
//            $this->diary_date = $this->get_date_formatted($this->request->text);
//        }
//    }

//    private function check_state($state, $state_data): bool
//    {
//        return isset($state_data['status_id']) && $this->chat_states[$state_data['status_id']] == $state;
//    }

//    private function clear_state(): bool
//    {
//        $db = new Db();
//        $sql = "DELETE FROM `chat_state` WHERE `user_id` = {$this->request->chat_id}";
//        return $db->execute($sql);
//    }

    /**
     * @throws Exception
     */
//    private function save_mood(): void
//    {
//        $state = $this->get_state();
//
//        $indicator_id = 1; //todo см. табл. 'indicator'
//        $indicator_value = array_search($this->request->text, $this->moods);
//
//        if (!$this->save_indicator($state['diary_date'], $indicator_id, $indicator_value)) {
//            throw new Exception();
//        }
//
//        $this->clear_state();
//    }

//    private function save_indicator($date, $indicator_id, $indicator_value): bool|\mysqli_result
//    {
//        $user_id = $this->request->chat_id;
//        $ts = time();
//
//        $db = new Db();
//        $sql = "INSERT INTO `diary` (`record_date`, `user_id`, `indicator_id`, `indicator_value`, `ts`)
//                VALUES ('$date', {$this->request->chat_id}, $indicator_id, $indicator_value, $ts)
//                ON DUPLICATE KEY UPDATE `indicator_value` = $indicator_value, `ts` = $ts";
//        return $db->execute($sql);
//    }

//    private function get_state(): array|null
//    {
//        $db = new Db();
//        $sql = "SELECT `status_id`, `diary_date` FROM `chat_state` WHERE `user_id` = {$this->request->chat_id}";
//
//        if ($result = $db->execute($sql)) {
//            return mysqli_fetch_assoc($result);
//        }
//
//        return null;
//    }



    /**
     * @throws Exception
     */
//    private function get_date_formatted($text): string
//    {
//        return match ($text) {
//            $this->date_values[0] => date("Y-m-d"),
//            $this->date_values[1] => date("Y-m-d", 'yesterday'),
//            $this->date_values[2] => date("Y-m-d", "2 days ago"),
//            $this->date_values[3] => date("Y-m-d", "3 days ago"),
//            default => throw new Exception("Дата не определена"),
//        };
//    }
}









//        switch ($this->request->text) {
//            case '/help':
//                $response = [
//                    'chat_id' => $this->request->chat_id,
//                    'reply_markup' => '',
//                    'parse_mode' => 'HTML',
//                    'text' => 'Привет! я еще в разработке, пока умею только напоминать шкалу настроения.'
//                ];
//                $this->send('text', $response);
//                break;
//            case '/scale':
//                $response = [
//                    'chat_id' => $this->request->chat_id,
//                    'photo' => curl_file_create(__DIR__ . '/../scale.png')
//                ];
//                $this->send('image', $response);
//                break;
//            case '/mood_chart':
//                $keyboard = [
//                    'keyboard' => [
//                        [
//                            ['text' => $this->date_values[0]],
//                            ['text' => $this->date_values[1]]
//                        ],
//                        [
//                            $this->date_values[2],
//                            ['text' => $this->date_values[3]]
//                        ]
//                    ],
//                    'resize_keyboard' => true,
//                    'one_time_keyboard' => true
//                ];
//
//                $response = [
//                    'chat_id' => $this->request->chat_id,
//                    'reply_markup' => json_encode($keyboard),
//                    'parse_mode' => 'HTML',
//                    'text' => 'За какой день?'
//                ];
//                $this->send('text', $response);
//                break;
//        }

//        /**
//         * обработка ввода даты
//         */
//        if (in_array($this->request->text, $this->date_values)) {
//            switch ($this->request->text) {
//                case $this->date_values[0]:
//                    $date = date("Y-m-d");
//                    break;
//                case $this->date_values[1]:
//                    $date = date("Y-m-d", 'yesterday');
//                    break;
//                case $this->date_values[2]:
//                    $date = date("Y-m-d", "2 days ago");
//                    break;
//                case $this->date_values[3]:
//                    $date = date("Y-m-d", "3 days ago");
//                    break;
//            }
//
//            if (!$this->save_date($this->request->chat_id, $date)) {
//                throw new Exception();
//            }
//
////        $remove_keyboard = [
////            "remove_keyboard" => true
////        ];
//            $keyboard = [
//                'keyboard' => [
//                    [
//                        ['text' => $this->moods[10]]
//                    ],
//                    [
//                        ['text' => $this->moods[9]]
//                    ],
//                    [
//                        ['text' => $this->moods[8]]
//                    ],
//                    [
//                        ['text' => $this->moods[7]]
//                    ],
//                    [
//                        ['text' => $this->moods[6]]
//                    ],
//                    [
//                        ['text' => $this->moods[5]]
//                    ],
//                    [
//                        ['text' => $this->moods[4]]
//                    ],
//                    [
//                        ['text' => $this->moods[3]]
//                    ],
//                    [
//                        ['text' => $this->moods[2]]
//                    ],
//                    [
//                        ['text' => $this->moods[1]]
//                    ],
//                    [
//                        ['text' => $this->moods[0]]
//                    ]
//                ],
////            'resize_keyboard' => false,
////            'one_time_keyboard' => true
//            ];
//            $response = [
//                'chat_id' => $this->request->chat_id,
////            'reply_markup' => json_encode($remove_keyboard),
//                'reply_markup' => json_encode($keyboard),
//                'parse_mode' => 'HTML',
//                'text' => 'Как настроение?'
//            ];
//            $this->send('text', $response);
//        }
//
//        /**
//         * обработка ввода настроения
//         */
//        if (in_array($this->request->text, $this->moods)) {
//
//
//
//            $remove_keyboard = [
//                "remove_keyboard" => true
//            ];
//
//            $response = [
//                'chat_id' => $this->request->chat_id,
//                'reply_markup' => json_encode($remove_keyboard),
//                'parse_mode' => 'HTML',
//                'text' => 'Спасибо, записано. Заходи еще!'
//            ];
//            $this->send('text', $response);
//
//
//        }