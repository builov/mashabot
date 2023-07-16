<?php
use Builov\MashaBot\Db;
use Builov\MashaBot\Request;
use Builov\MashaBot\Response;

require 'vendor/autoload.php';
require 'config.php';

header('Content-Type: text/html; charset=utf-8');

/**
 * получение данных из чата
 */
$request = new Request();
if ($request->is_empty()) {
    exit;
}

$response = new Response($request);
$response->generate();
//$response->send();



//$db = new Db();
//$chat_id = 497026734;
//$sql = "SELECT `status_id`, `diary_date` FROM `chat_state` WHERE `user_id` = {$chat_id}";
//$result = $db->execute($sql);
//
//print_r($result->fetch_assoc());




//file_put_contents(__DIR__ . '/log.php', print_r($data, true), FILE_APPEND);
//file_put_contents(__DIR__ . '/log.php', print_r($data, true));

/**
 * разбор данных, полученных из чата
 */
//if (!empty($data['message']['text'])) {
//    $chat_id = $data['message']['from']['id'];
//    $user_name = $data['message']['from']['username'];
//    $first_name = $data['message']['from']['first_name'];
//    $last_name = $data['message']['from']['last_name'];
//    $text = trim($data['message']['text']);
//    $text_array = explode(" ", $text);
//
//    /**
//     * обработка команд
//     */
//    switch ($text) {
//        case '/help':
//            $response = [
//                'chat_id' => $chat_id,
//                'reply_markup' => '',
//                'parse_mode' => 'HTML',
//                'text' => 'Привет! я еще в разработке, пока умею только напоминать шкалу настроения.'
//            ];
//            message_to_telegram($bot_token, 'text', $response);
//            break;
//        case '/scale':
//            $response = [
//                'chat_id' => $chat_id,
//                'photo' => curl_file_create(__DIR__ . '/scale.png')
//            ];
//            message_to_telegram($bot_token, 'image', $response);
//            break;
//        case '/mood_chart':
//            $keyboard = [
//                'keyboard' => [
//                    [
//                        ['text' => $date_values[0]],
//                        ['text' => $date_values[1]]
//                    ],
//                    [
//                        $date_values[2],
//                        ['text' => $date_values[3]]
//                    ]
//                ],
//                'resize_keyboard' => true,
//                'one_time_keyboard' => true
//            ];
//
//            $response = [
//                'chat_id' => $chat_id,
//                'reply_markup' => json_encode($keyboard),
//                'parse_mode' => 'HTML',
//                'text' => 'За какой день?'
//            ];
//            message_to_telegram($bot_token, 'text', $response);
//            break;
//    }

//    /**
//     * обработка ввода даты
//     */
//    if (in_array($text, $date_values)) {
//        switch ($text) {
//            case $date_values[0]:
//                $date = date("Y-m-d");
//                break;
//            case $date_values[1]:
//                $date = date("Y-m-d", 'yesterday');
//                break;
//            case $date_values[2]:
//                $date = date("Y-m-d", "2 days ago");
//                break;
//            case $date_values[3]:
//                $date = date("Y-m-d", "3 days ago");
//                break;
//        }
////        save_date($chat_id, $date);
//
////        $remove_keyboard = [
////            "remove_keyboard" => true
////        ];
//        $keyboard = [
//            'keyboard' => [
//                [
//                    ['text' => $moods[10]]
//                ],
//                [
//                    ['text' => $moods[9]]
//                ],
//                [
//                    ['text' => $moods[8]]
//                ],
//                [
//                    ['text' => $moods[7]]
//                ],
//                [
//                    ['text' => $moods[6]]
//                ],
//                [
//                    ['text' => $moods[5]]
//                ],
//                [
//                    ['text' => $moods[4]]
//                ],
//                [
//                    ['text' => $moods[3]]
//                ],
//                [
//                    ['text' => $moods[2]]
//                ],
//                [
//                    ['text' => $moods[1]]
//                ],
//                [
//                    ['text' => $moods[0]]
//                ]
//            ],
////            'resize_keyboard' => false,
////            'one_time_keyboard' => true
//        ];
//        $response = [
//            'chat_id' => $chat_id,
////            'reply_markup' => json_encode($remove_keyboard),
//            'reply_markup' => json_encode($keyboard),
//            'parse_mode' => 'HTML',
//            'text' => 'Как настроение?'
//        ];
//        message_to_telegram($bot_token, 'text', $response);
//    }
//
//    /**
//     * обработка ввода настроения
//     */
//    if (in_array($text, $moods)) {
//
//        $state = get_state($chat_id);
//
//        $indicator_value = array_search($text, $moods);
//
//        save_indicator($chat_id, 1, $indicator_value);
//
//        $remove_keyboard = [
//            "remove_keyboard" => true
//        ];
//
//        $response = [
//            'chat_id' => $chat_id,
//            'reply_markup' => json_encode($remove_keyboard),
//            'parse_mode' => 'HTML',
//            'text' => 'Спасибо, записано. Заходи еще!'
//        ];
//        message_to_telegram($bot_token, 'text', $response);
//
//        clear_state($chat_id);
//    }
//}

/**
 * Отправка сообщения в чат
 * @param $bot_token
 * @param $chat_id
 * @param $content_type
 * @param $content
 * @param $reply_markup
 * @return void
 */
//function message_to_telegram($bot_token, $content_type, $response)
//{
////    print_r($response); exit;
//
//    $content_types = [
//        'text' => 'sendMessage',
//        'image' => 'sendPhoto'
//    ];
//
//    $ch = curl_init();
//    $ch_post = [
//        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/' . $content_types[$content_type],
//        CURLOPT_POST => TRUE,
//        CURLOPT_RETURNTRANSFER => TRUE,
//        CURLOPT_TIMEOUT => 10,
////        CURLOPT_HEADER => false,
//        CURLOPT_POSTFIELDS => $response
//    ];
//
//    curl_setopt_array($ch, $ch_post);
//    curl_exec($ch);
////    curl_close($ch);
//}

//function save_date($chat_id, $date)
//{
//    $db = new DbConn();
//    $sql = "INSERT INTO `chat_state` (`user_id`, `status_id`, `diary_date`) VALUES ('{$chat_id}', 1, '$date')";
//    $result = $db->execute($sql);
//}
//
//function save_indicator($chat_id, $indicator_id, $indicator_value)
//{
//    $db = new DbConn();
//
////todo сделать получение даты из БД
//    $date = '2023-07-08';
//
//    $sql = "INSERT INTO `diary` (`record_date`, `user_id`, `indicator_id`, `indicator_value`, `ts`) VALUES ($date, {$chat_id}, {$indicator_id}, {$indicator_value}, NOW())";
////    file_put_contents(__DIR__ . '/log.php', $sql);
//    $result = $db->execute($sql);
//}
//
//function get_state($chat_id) ////get_state(497026734);
//{
////    echo $chat_id;
//
//    $db = new DbConn();
//    $sql = "SELECT `status_id`, `diary_date` FROM `chat_state` WHERE `user_id` = {$chat_id}";
//    $result = $db->execute($sql);
//
//   print_r($result);
//
////    foreach ($result as $row) {
////        echo " diary_date = " . $row['diary_date'] . "\n";
////    }
//}