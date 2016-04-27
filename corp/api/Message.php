<?php

class Message
{
    public static function sendToConversation($accessToken, $opt)
    {
        $response = Http::post("/message/send_to_conversation",
            array("access_token" => $accessToken),
            json_encode($opt));
        return $response;
    }

    public static function send($accessToken, $opt)
    {
        $response = Http::post("/message/send",
            array("access_token" => $accessToken),json_encode($opt));
        return $response;
    }
}