<?php

namespace Builov\MashaBot;

use Builov\MashaBot\Request;

class Message
{
    public array $properties;
    public Request $request;
    public array $messages = [
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
//                        [
//                            ['text' => $this->date_values[0]],
//                            ['text' => $this->date_values[1]]
//                        ],
//                        [
//                            $this->date_values[2],
//                            ['text' => $this->date_values[3]]
//                        ]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ],
            'parse_mode' => 'HTML',
            'set_state' => 'pending_date'
        ],
//            [
//                'request' => $this->date_values,
//                'response_type' => 'text',
//                'response_text' => 'Как настроение?',
//                'reply_markup' => [
//                    'keyboard' => [
//                        [['text' => $this->moods[10]]],
//                        [['text' => $this->moods[9]]],
//                        [['text' => $this->moods[8]]],
//                        [['text' => $this->moods[7]]],
//                        [['text' => $this->moods[6]]],
//                        [['text' => $this->moods[5]]],
//                        [['text' => $this->moods[4]]],
//                        [['text' => $this->moods[3]]],
//                        [['text' => $this->moods[2]]],
//                        [['text' => $this->moods[1]]],
//                        [['text' => $this->moods[0]]]
//                    ]
//                ],
//                'parse_mode' => 'HTML',
//                'action' => 'set_date',
//                'pending_state' => 'pending_date',
//                'set_state' => 'pending_mood'
//            ],
//            [
//                'request' => $this->moods,
//                'response_type' => 'text',
//                'response_text' => 'Спасибо, записано. Заходи еще!',
//                'reply_markup' => [
//                    "remove_keyboard" => true
//                ],
//                'parse_mode' => 'HTML',
//                'action' => 'save_mood',
//                'pending_state' => 'pending_mood',
//                'clear_state' => true
//            ]
    ];

    /**
     * @param Request $request
     */
    function __construct($request)
    {
//        var_dump($request); exit;

        $this->request = $request;
//        var_dump($this->request);
//        exit;

        /**
         * Определение сообщения
         */
        foreach ($this->messages as $key => $message) {
            if ((!is_array($message['request']) && $this->request->text == $message['request'])
                || (is_array($message['request']) && in_array($this->request->text, $message['request']))) {
                $this->properties = $this->messages[$key];
            }
        }

        /**
         * Непредусмотренное сообщение
         */
        if (!isset($this->properties)) {
            $this->properties = $this->messages[0];
        }

//        exit;

    }
}