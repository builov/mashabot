<?php

namespace Builov\MashaBot;

class RequestProcessor
{
    private Message $message;
    private ChatState $chatState;


    /**
     * @param Request $request
     */
    function __construct(Request $request)
    {
        $this->message = new Message($request);
    }

    /**
     * @throws \Exception
     */
    public function process(): Response
    {
        /**
         * Получение кнопок для ответа
         */
        if (isset($this->message->properties['reply_markup']['keyboard'])) {
            $method_name = $this->message->properties['reply_markup']['keyboard'];

            $keyboard = new Keyboard();
            $this->message->properties['reply_markup']['keyboard'] = $keyboard->$method_name();
        }

        /**
         * Проверка ожидаемого состояния и установка даты дневника
         */
        $this->chatState = new ChatState($this->message);

        if (isset($this->message->properties['pending_state'])) {
            if (!$current_state = $this->chatState->get()) {
                throw new \Exception('Отсутствует ожидаемое состояние');
            }

//            var_dump($current_state); exit;

            if ($this->chatState->check()) {
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
        $response = new Response($this->message);

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
        if (isset($this->message->properties['set_state'])) {
            $this->chatState->save();
        }

        /**
         * Очистка состояния
         */
//        if (isset($bot_message['clear_state'])) {
//            $state = array_search($bot_message['set_state'], $this->chat_states);
//
//            $this->set_state($state);
//        }


        return $response;
    }
}