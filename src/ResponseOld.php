<?php

namespace Builov\MashaBot;

use Exception;

class ResponseOld
{
    private Request $request;

//    private array $date_values = ['Сегодня', 'Вчера', 'Позавчера', 'Другая дата'];

//    private array $moods = [
//        10 => 'Полная потеря самоконтроля и самокритики. Бред и галлюцинации.',
//        9 => 'Потеря контакта с реальностью, неразборчивая речь, рискованное и неадекватное поведение, приступы паранойи, невозможность уснуть.',
//        8 => 'Крайняя самоуверенность, быстрая речь, ускоренное мышление, стремление браться за множество дел при невозможности их завершить.',
//        7 => 'Повышенная активность, высокая продуктивность. Все с избытком: разговоры, учеба, творчество.',
//        6 => 'Хорошее настроение. В меру оптимистичные и общительный настрой, адекватные решения, хорошая работоспособность.',
//        5 => 'Нормальное настроение. Спокойный, сбалансированный настрой.',
//        4 => 'Пониженное настроение. Легкая отстраненность, некоторое снижение концентрации внимания, легкая тревога.',
//        3 => 'Сильная тревога или паника. Память ослаблена, трудно сконцентрироваться на делах. Сохраняется способность выполнять рутинные задачи.',
//        2 => 'Заторможенность, потеря аппетита, желание остаться одному. Все дела даются с огромным трудом, невозможность уснуть ночью или проснуться утром.',
//        1 => 'Ощущение безнадежности и вины, суицидальные мысли, нежелание двигаться, невозможность выполнять повседневные дела.',
//        0 => 'Постоянные размышления о суициде, ощущение, что все поблекло и потеряло смысл и это навсегда, неподвижность.'
//    ];
//    private array $chat_states = [
//        1 => 'pending_date',
//        2 => 'pending_mood',
//        3 => 'pending_comment'
//    ];
    private array $bot_messages;
//    private string $diary_date;
    private ChatState $chat_state;

    function __construct($request)
    {
        $this->request = $request;

        $this->bot_messages = [
            [
                'request' => '',
                'response_type' => 'text',
                'response_text' => 'Ой, даже не знаю, что на это ответить! Лучше используй команды, которые я понимаю.',
                'reply_markup' => '',
                'parse_mode' => 'HTML',
            ],
            [
                'request' => '/help',
                'response_type' => 'text',
                'response_text' => 'Привет! я еще в разработке, пока умею только напоминать шкалу настроения.',
                'reply_markup' => '',
                'parse_mode' => 'HTML',
            ],
            [
                'request' => '/scale',
                'response_type' => 'image',
                'response_image' => 'scale.png',
            ],
            [
                'request' => '/mood_chart',
                'response_type' => 'text',
                'response_text' => 'За какой день?',
                'reply_markup' => [
                    'keyboard' => [
                        [
                            ['text' => $this->date_values[0]],
                            ['text' => $this->date_values[1]]
                        ],
                        [
                            $this->date_values[2],
                            ['text' => $this->date_values[3]]
                        ]
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ],
                'parse_mode' => 'HTML',
                'set_state' => 'pending_date'
            ],
            [
                'request' => $this->date_values,
                'response_type' => 'text',
                'response_text' => 'Как настроение?',
                'reply_markup' => [
                    'keyboard' => [
                        [['text' => $this->moods[10]]],
                        [['text' => $this->moods[9]]],
                        [['text' => $this->moods[8]]],
                        [['text' => $this->moods[7]]],
                        [['text' => $this->moods[6]]],
                        [['text' => $this->moods[5]]],
                        [['text' => $this->moods[4]]],
                        [['text' => $this->moods[3]]],
                        [['text' => $this->moods[2]]],
                        [['text' => $this->moods[1]]],
                        [['text' => $this->moods[0]]]
                    ]
                ],
                'parse_mode' => 'HTML',
                'action' => 'set_date',
                'pending_state' => 'pending_date',
                'set_state' => 'pending_mood'
            ],
            [
                'request' => $this->moods,
                'response_type' => 'text',
                'response_text' => 'Спасибо, записано. Заходи еще!',
                'reply_markup' => [
                    "remove_keyboard" => true
                ],
                'parse_mode' => 'HTML',
                'action' => 'save_mood',
                'pending_state' => 'pending_mood',
                'clear_state' => true
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function generate(): void
    {
        /**
         * Определение сообщения
         */
        foreach ($this->bot_messages as $key => $message) {
            if ((!is_array($message['request']) && $this->request->text == $message['request'])
                || (is_array($message['request']) && in_array($this->request->text, $message['request']))) {
                $bot_message = $this->bot_messages[$key];
            }
        }

        /**
         * Непредусмотренное сообщение
         */
        if (!isset($bot_message)) {
            $bot_message = $this->bot_messages[0];
        }

        /**
         * Проверка ожидаемого состояния и установка даты дневника
         */
        $this->chat_state = new ChatState($bot_message);

        if (isset($bot_message['pending_state'])) {



            $state_data = $this->get_state();

            if ($this->check_state($bot_message['pending_state'], $state_data)) {
                $this->diary_date = $state_data['diary_date'] ?? '';
            } else {
                //todo вернуться к началу. Например: "Попробуем еще раз?"
                echo "Попробуем еще раз?";
                exit;
            }
        }

        /**
         * Генерация ответа
         */
        $response = [
            'chat_id' => $this->request->chat_id
        ];
        if (isset($bot_message['reply_markup'])) {
            $response['reply_markup'] = (is_array($bot_message['reply_markup']))
                ? json_encode($bot_message['reply_markup'])
                : $bot_message['reply_markup'];
        }
        $response['parse_mode'] = (isset($bot_message['parse_mode'])) ? $bot_message['parse_mode'] : null;
        $response['text'] = (isset($bot_message['response_text'])) ? $bot_message['response_text'] : null;
        $response['photo'] = (isset($bot_message['response_image'])) ? curl_file_create(__DIR__ . '/../' . $bot_message['response_image']) : null;

        /**
         * Выполнение действия, связанного с обработкой сообщения
         */
        if (isset($bot_message['action'])) {
            $name = $bot_message['action'];

            $this->$name();
        }

        /**
         * Установка состояния
         */
        if (isset($bot_message['set_state'])) {
            $state = array_search($bot_message['set_state'], ChatState::$chat_states);

            $this->chat_state->save();
        }

        /**
         * Очистка состояния
         */
        if (isset($bot_message['clear_state'])) {
            $state = array_search($bot_message['set_state'], $this->chat_states);

            $this->set_state($state);
        }

        /**
         * Отправка ответа
         */
        $this->send($bot_message['response_type'], $response);
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
    private function set_date(): void
    {
        if (in_array($this->request->text, $this->date_values)) {
            $this->diary_date = $this->get_date_formatted($this->request->text);
        }
    }

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
    private function save_mood(): void
    {
        $state = $this->get_state();

        $indicator_id = 1; //todo см. табл. 'indicator'
        $indicator_value = array_search($this->request->text, $this->moods);

        if (!$this->save_indicator($state['diary_date'], $indicator_id, $indicator_value)) {
            throw new Exception();
        }

        $this->clear_state();
    }

    private function save_indicator($date, $indicator_id, $indicator_value): bool|\mysqli_result
    {
        $user_id = $this->request->chat_id;
        $ts = time();

        $db = new Db();
        $sql = "INSERT INTO `diary` (`record_date`, `user_id`, `indicator_id`, `indicator_value`, `ts`) 
                VALUES ('$date', {$this->request->chat_id}, $indicator_id, $indicator_value, $ts) 
                ON DUPLICATE KEY UPDATE `indicator_value` = $indicator_value, `ts` = $ts";
        return $db->execute($sql);
    }

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

    public function send($media_type, $response): void
    {
        $content_types = [
            'text' => 'sendMessage',
            'image' => 'sendPhoto'
        ];

        $ch = curl_init();
        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . BOT_TOKEN . '/' . $content_types[$media_type],
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
//        CURLOPT_HEADER => false,
            CURLOPT_POSTFIELDS => $response
        ];

        curl_setopt_array($ch, $ch_post);
        curl_exec($ch);
//    curl_close($ch);
    }

    /**
     * @throws Exception
     */
    private function get_date_formatted($text): string
    {
        return match ($text) {
            $this->date_values[0] => date("Y-m-d"),
            $this->date_values[1] => date("Y-m-d", 'yesterday'),
            $this->date_values[2] => date("Y-m-d", "2 days ago"),
            $this->date_values[3] => date("Y-m-d", "3 days ago"),
            default => throw new Exception("Дата не определена"),
        };
    }
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