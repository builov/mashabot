<?php

namespace Builov\MashaBot;

class Response
{
    private $request;
    private $date_values;
    private $moods;
    private $bot_messages;



    private $chat_states = [
        1 => 'pending_date',
        2 => 'pending_mood',
        3 => 'pending_comment'
    ];

    function __construct($request)
    {
        $this->request = $request;

        $this->date_values = ['Сегодня', 'Вчера', 'Позавчера', 'Другая дата'];

        $this->moods = [
            10 => 'Полная потеря самоконтроля и самокритики. Бред и галлюцинации.',
            9 => 'Потеря контакта с реальностью, неразборчивая речь, рискованное и неадекватное поведение, приступы паранойи, невозможность уснуть.',
            8 => 'Крайняя самоуверенность, быстрая речь, ускоренное мышление, стремление браться за множество дел при невозможности их завершить.',
            7 => 'Повышенная активность, высокая продуктивность. Все с избытком: разговоры, учеба, творчество.',
            6 => 'Хорошее настроение. В меру оптимистичные и общительный настрой, адекватные решения, хорошая работоспособность.',
            5 => 'Нормальное настроение. Спокойный, сбалансированный настрой.',
            4 => 'Пониженное настроение. Легкая отстраненность, некоторое снижение концентрации внимания, легкая тревога.',
            3 => 'Сильная тревога или паника. Память ослаблена, трудно сконцентрироваться на делах. Сохраняется способность выполнять рутинные задачи.',
            2 => 'Заторможенность, потеря аппетита, желание остаться одному. Все дела даются с огромным трудом, невозможность уснуть ночью или проснуться утром.',
            1 => 'Ощущение безнадежности и вины, суицидальные мысли, нежелание двигаться, невозможность выполнять повседневные дела.',
            0 => 'Постоянные размышления о суициде, ощущение, что все поблекло и потеряло смысл и это навсегда, неподвижность.'
        ];

        $this->bot_messages = [
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
                'reply_markup' => 'keyboard',
                'parse_mode' => 'HTML',
                'keyboard' => [
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
                ]
            ],
            [
                'request' => $this->date_values,
                'response_type' => 'text',
                'response_text' => 'Как настроение?',
                'reply_markup' => 'keyboard',
                'parse_mode' => 'HTML',
                'keyboard' => [
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
                'acton' => 'save_date'
            ],
            [
                'request' => $this->moods,
                'response_type' => 'text',
                'response_text' => 'Спасибо, записано. Заходи еще!',
                'reply_markup' => [
                    "remove_keyboard" => true
                ],
                'parse_mode' => 'HTML',
                'acton' => 'save_mood'
            ]
        ];
    }

    public function generate()
    {

        foreach ($this->bot_messages as $message) {
            if ($this->request->text == $message['request']) {
                $response = [
                    'chat_id' => $this->request->chat_id
                ];
                if (isset($message['reply_markup'])) {
                    $response['reply_markup'] = $message['reply_markup'];
                }
                if (isset($message['parse_mode'])) {
                    $response['parse_mode'] = $message['parse_mode'];
                }
                if (isset($message['response_text'])) {
                    $response['text'] = $message['response_text'];
                }
                if (isset($message['response_image'])) {
                    $response['photo'] = curl_file_create(__DIR__ . '/../' . $message['response_image']);
                }

                print_r($response);

                $this->send($message['response_type'], $response);
            }
        }



        /**
         * обработка команд
         */
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

        /**
         * обработка ввода даты
         */
        if (in_array($this->request->text, $this->date_values)) {
            switch ($this->request->text) {
                case $this->date_values[0]:
                    $date = date("Y-m-d");
                    break;
                case $this->date_values[1]:
                    $date = date("Y-m-d", 'yesterday');
                    break;
                case $this->date_values[2]:
                    $date = date("Y-m-d", "2 days ago");
                    break;
                case $this->date_values[3]:
                    $date = date("Y-m-d", "3 days ago");
                    break;
            }

            if (!$this->save_date($this->request->chat_id, $date)) {
                throw new \Exception();
            }

//        $remove_keyboard = [
//            "remove_keyboard" => true
//        ];
            $keyboard = [
                'keyboard' => [
                    [
                        ['text' => $this->moods[10]]
                    ],
                    [
                        ['text' => $this->moods[9]]
                    ],
                    [
                        ['text' => $this->moods[8]]
                    ],
                    [
                        ['text' => $this->moods[7]]
                    ],
                    [
                        ['text' => $this->moods[6]]
                    ],
                    [
                        ['text' => $this->moods[5]]
                    ],
                    [
                        ['text' => $this->moods[4]]
                    ],
                    [
                        ['text' => $this->moods[3]]
                    ],
                    [
                        ['text' => $this->moods[2]]
                    ],
                    [
                        ['text' => $this->moods[1]]
                    ],
                    [
                        ['text' => $this->moods[0]]
                    ]
                ],
//            'resize_keyboard' => false,
//            'one_time_keyboard' => true
            ];
            $response = [
                'chat_id' => $this->request->chat_id,
//            'reply_markup' => json_encode($remove_keyboard),
                'reply_markup' => json_encode($keyboard),
                'parse_mode' => 'HTML',
                'text' => 'Как настроение?'
            ];
            $this->send('text', $response);
        }

        /**
         * обработка ввода настроения
         */
        if (in_array($this->request->text, $this->moods)) {

            $state = get_state($this->request->chat_id);

            $indicator_value = array_search($this->request->text, $this->moods);

            save_indicator($this->request->chat_id, 1, $indicator_value);

            $remove_keyboard = [
                "remove_keyboard" => true
            ];

            $response = [
                'chat_id' => $this->request->chat_id,
                'reply_markup' => json_encode($remove_keyboard),
                'parse_mode' => 'HTML',
                'text' => 'Спасибо, записано. Заходи еще!'
            ];
            $this->send('text', $response);

            clear_state($this->request->chat_id);
        }

    }

    public function send($media_type, $response)
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

    private function save_date($chat_id, $date)
    {
        $db = new Db();
        $sql = "INSERT INTO `chat_state` (`user_id`, `status_id`, `diary_date`) VALUES ('{$chat_id}', 1, '$date') ON DUPLICATE KEY UPDATE `status_id` = 1, `diary_date` = '$date'";
        return $db->execute($sql);
    }
}