<?php

namespace Builov\MashaBot;

use Builov\MashaBot\Request;
use Builov\MashaBot\DiaryEntryDate;

class Message
{
    public array $properties;
    public Request $request;
    public ChatState $chatState;
    public string $diaryDate;
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
                'keyboard' => 'getDates', //метод класса Keyboard
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ],
            'parse_mode' => 'HTML',
            'set_state' => 'pending_date'   //свойства класса ChatState
        ],
            [
                'request' => ['getDateTextValues'], //метод класса Request
                'response_type' => 'text',
                'response_text' => 'Как настроение?',
                'reply_markup' => [
                    'keyboard' => 'getMoods',   //метод класса Keyboard
                ],
                'parse_mode' => 'HTML',
                'action' => 'setDate',  //метод класса RequestProcessor
                'pending_state' => 'pending_date',  //свойства класса ChatState
                'set_state' => 'pending_mood'       //свойства класса ChatState
            ],
            [
                'request' => ['getMoodTextValues'], //метод класса Request
                'response_type' => 'text',
                'response_text' => 'Спасибо, записано. Заходи еще!',
                'reply_markup' => [
                    "remove_keyboard" => true
                ],
                'parse_mode' => 'HTML',
                'action' => 'saveMood',     //метод класса RequestProcessor
                'pending_state' => 'pending_mood',  //свойства класса ChatState
                'clear_state' => true
            ],


//            [
//                'request' => [1,2,3,4,5],
//                'response_type' => 'text',
//                'response_text' => '', //метод класса Request
//                'reply_markup' => [
//                    "remove_keyboard" => true
//                ],
//                'parse_mode' => 'HTML',
//                'action' => 'saveMood',     //метод класса RequestProcessor
//                'pending_state' => 'pending_mood',  //свойства класса ChatState
//                'set_state' => 'pending_mood'       //свойства класса ChatState
////                'clear_state' => true
//            ]
    ];

    /**
     * @param ChatState $chatState
     */
    function __construct($chatState)
    {
        $this->chatState = $chatState;
        $this->request = $chatState->request;

        /**
         * Определение сообщения
         */
        foreach ($this->messages as $key => $message) {
            /** числовые ответы */
            if (in_array($this->request->text, [1,2,3,4,5])) {
                $this->properties['request'] = $this->request->text;
                $this->properties['response_type'] = 'text';
                $this->properties['response_text'];
                $this->properties['reply_markup'] = '';
                $this->properties['parse_mode'] = 'HTML';



                return;
            }

            if (is_array($message['request'])) {
                $method = $message['request'][0];

                $message['request'] = $this->request->$method();
            }

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
    }
}