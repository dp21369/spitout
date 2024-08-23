<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require "../db/users.php";
require "../db/chatrooms.php";
class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = [];
        echo "Server Started.";
    }

    public function onOpen(ConnectionInterface $conn)
    {

        // Get the user ID from the query string.
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $params);
        $userId = (int)$params['uid'];

        // Store the connection using the user ID as the key.
        $this->clients[$userId] = $conn;

        echo "New connection! ({$userId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {

        $data = json_decode($msg, true);

        $objChatroom = new \chatrooms;

        if ($data['type'] == 'send_msg') {

            $objChatroom->setUserId($data['userId']);
            $objChatroom->setMsg($data['msg']);
            $objChatroom->setCreatedOn(date("Y-m-d h:i:s"));

            if ($objChatroom->saveChatRoom()) {
                $objUser = new \users;
                $objUser->setId($data['userId']);
                $user = $objUser->getUserById();
                $data['from'] = $user['name'];
                $data['msg']  = $data['msg'];
                $data['dt']  = date("d-m-Y h:i:s");
            }

            //send the new msg to who ever sent the message
            $data['from']  = "Me";
            $receiverConn = $this->clients[$data['userId']];
            $receiverConn->send(json_encode([$data, ['type' => $data['type']]]));

            //send the new msg to the receiver
            if (isset($this->clients[$data['receiverID']])) {
                $data['from']  = $user['name'];
                $receiverConn = $this->clients[$data['receiverID']];
                $receiverConn->send(json_encode([$data, ['type' => $data['type']]]));
            }
        } else if ($data['type'] == 'get_chat') {

            $objChatroom->setReceiverId($data['receiverID']);

            $chat_log = $objChatroom->getSpecificUserChatRoom();

            // return the chat log to whichever connection requested it
            $receiverConn = $this->clients[$data['senderID']];
            $receiverConn->send(json_encode([$chat_log, ['type' => $data['type']]]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {

        // Get the user ID from the query string.
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $params);
        $userId = $params['uid'];

        // Remove the connection using the user ID as the key.
        unset($this->clients[$userId]);

        echo "Connection {$userId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
