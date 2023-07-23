<?php

namespace Builov\MashaBot;

class RequestProcessor
{
//    private Request $request;

    private Message $message;

    /**
     * @param Request $request
     */
    function __construct($request)
    {
//        $this->request = $request;

        $this->message = new Message($request);

//        var_dump($this->message);
    }


    public function process(): Response
    {
        /**
         * Проверка ожидаемого состояния и установка даты дневника
         */
//        $this->chat_state = new ChatState($this->message);

//        if (isset($bot_message['pending_state'])) {
//            $state_data = $this->get_state();
//
//            if ($this->check_state($bot_message['pending_state'], $state_data)) {
//                $this->diary_date = $state_data['diary_date'] ?? '';
//            } else {
//                //todo вернуться к началу. Например: "Попробуем еще раз?"
//                echo "Попробуем еще раз?";
//                exit;
//            }
//        }

        /**
         * Генерация ответа
         */
//        $response['data'] = [
//            'chat_id' => $this->message->request->chat_id
//        ];
//        if (isset($this->message->properties['reply_markup'])) {
//            $response['data']['reply_markup'] = (is_array($this->message->properties['reply_markup']))
//                ? json_encode($this->message->properties['reply_markup'])
//                : $this->message->properties['reply_markup'];
//        }
//        $response['data']['parse_mode'] = (isset($this->message->properties['parse_mode'])) ? $this->message->properties['parse_mode'] : null;
//        $response['data']['text'] = (isset($this->message->properties['response_text'])) ? $this->message->properties['response_text'] : null;
//        $response['data']['photo'] = (isset($this->message->properties['response_image'])) ? curl_file_create(__DIR__ . '/../' . $this->message->properties['response_image']) : null;
//        $response['type'] = $this->message->properties['response_type'];

        /**
         * Выполнение действия, связанного с обработкой сообщения
         */
//        if (isset($bot_message['action'])) {
//            $name = $bot_message['action'];
//
//            $this->$name();
//        }

        /**
         * Установка состояния
         */
//        if (isset($bot_message['set_state'])) {
//            $state = array_search($bot_message['set_state'], ChatState::$chat_states);
//
//            $this->chat_state->save();
//        }

        /**
         * Очистка состояния
         */
//        if (isset($bot_message['clear_state'])) {
//            $state = array_search($bot_message['set_state'], $this->chat_states);
//
//            $this->set_state($state);
//        }

        /**
         * Отправка ответа
         */
//        $this->send($bot_message['response_type'], $response);

        return new Response($this->message);
    }
}