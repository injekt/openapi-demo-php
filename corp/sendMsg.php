<?php
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/util/Log.php");
require_once(__DIR__ . "/util/Cache.php");
require_once(__DIR__ . "/api/Auth.php");
require_once(__DIR__ . "/api/User.php");
require_once(__DIR__ . "/api/Message.php");

$event = $_POST["event"];
switch($event){
    case '':
        echo json_encode(array("error_code"=>"4000"));
        break;
    case 'send_to_conversation':
        $sender = $_POST['sender'];
        $cid = $_POST['cid'];
        $content = $_POST['content'];
        $accessToken = Auth::getAccessToken();
        $option = array(
            "sender"=>$sender,
            "cid"=>$cid,
            "msgtype"=>"text",
            "text"=>array("content"=>$content)
        );
        $response = Message::sendToConversation($accessToken,$option);

        echo json_encode($response);
        break;

    case 'get_userinfo':
        $accessToken = Auth::getAccessToken();
        $code = $_POST["code"];
        $userInfo = User::getUserInfo($accessToken, $code);
        Log::i("[USERINFO]".json_encode($userInfo));
        echo json_encode($userInfo);
        break;
}
