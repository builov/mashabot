<?php

namespace Builov\MashaBot;

use Exception;

class RequestProcessor
{
    private Request $request;
    private Message $message;
    private ChatState $chatState;


    /**
     * @param Request $request
     */
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->chatState = new ChatState($request);
        $this->message = new Message($this->chatState);
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
         * Проверка ожидаемого состояния
         */


        if (isset($this->message->properties['pending_state'])) {
            if (!$this->chatState->check($this->message)) {
                throw new \Exception('Отсутствует ожидаемое состояние');
                //todo вернуться к началу. Например: "Попробуем еще раз?"
            }
        }

        /**
         * Генерация ответа
         */
        $response = new Response($this->message);

        /**
         * Выполнение действия, связанного с обработкой сообщения
         */
        if (isset($this->message->properties['action'])) {
            $name = $this->message->properties['action'];

            $this->$name();
        }

        /**
         * Установка состояния
         */
        if (isset($this->message->properties['set_state'])) {
            $this->chatState->save($this->message);
        }

        /**
         * Очистка состояния
         */
        if (isset($this->message->properties['clear_state'])) {
            $this->chatState->clear();
        }

        return $response;
    }

    /**
     * @throws \Exception
     */
    private function setDate(): void
    {
        if (in_array($this->message->request->text, DiaryEntryDate::getTextValues())) {
            $this->message->diaryDate = $this->chatState->date = DiaryEntryDate::getDateFormatted($this->message->request->text);
        }
    }

    private function saveMood(): void
    {
//        var_dump($this->chatState); exit;

        $mood = new Mood($this->message->request->chat_id, $this->chatState->date, $this->message->request->text);
        if (!$mood->save()) {
            throw new Exception();
        }
    }
}