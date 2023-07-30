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

//        print_r($this->postfields);

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

//        var_dump(curl_exec($ch));

//    curl_close($ch);
    }
}


