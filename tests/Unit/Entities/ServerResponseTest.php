<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto
 */

namespace Tests\Unit;

use \Longman\TelegramBot\Entities\ServerResponse;
use \Longman\TelegramBot\Entities\Message;
use \Longman\TelegramBot\Request;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class ServerResponseTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Entities\ServerResponse
    */
    private $server;

    public function sendMessageOk()
    {
        return '{
            "ok":true,
            "result":{
                "message_id":1234,
                "from":{"id":123456789,"first_name":"botname","username":"namebot"},
                "chat":{"id":123456789,"first_name":"john","username":"Mjohn"},
                "date":1441378360,
                "text":"hello"
            }
        }';
    }

    public function testSendMessageOk() {
        $result = $this->sendMessageOk();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');
        $server_result = $this->server->getResult();

        $this->assertTrue($this->server->isOk());
        $this->assertNull($this->server->getErrorCode());
        $this->assertNull($this->server->getDescription());
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\Message', $server_result);

        //Message
        $this->assertEquals('1234', $server_result->getMessageId());

        $this->assertInstanceOf('\Longman\TelegramBot\Entities\User', $server_result->getFrom());
        $this->assertEquals('123456789', $server_result->getFrom()->getId());
        $this->assertEquals('botname', $server_result->getFrom()->getFirstName());
        $this->assertEquals('namebot', $server_result->getFrom()->getUserName());

        $this->assertInstanceOf('\Longman\TelegramBot\Entities\Chat', $server_result->getChat());
        $this->assertEquals('123456789', $server_result->getChat()->getId());
        $this->assertEquals('john', $server_result->getChat()->getFirstName());
        $this->assertEquals('Mjohn', $server_result->getChat()->getUserName());

        $this->assertEquals('1441378360', $server_result->getDate());
        $this->assertEquals('hello', $server_result->getText());
    }

    public function sendMessageFail()
    {
        return '{
            "ok":false,
            "error_code":400,
            "description":"Error: Bad Request: wrong chat id"
        }';
    }

    public function testSendMessageFail() {
        $result = $this->sendMessageFail();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertFalse($this->server->isOk());
        $this->assertNull($this->server->getResult());
        $this->assertEquals('400', $this->server->getErrorCode());
        $this->assertEquals('Error: Bad Request: wrong chat id', $this->server->getDescription());
    }

   public function setWebhookOk()
    {
        return '{"ok":true,"result":true,"description":"Webhook was set"}';
    }

    public function testSetWebhookOk() {
        $result = $this->setWebhookOk();
        $this->server =  new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertTrue($this->server->getResult());
        $this->assertNull($this->server->getErrorCode());
        $this->assertEquals('Webhook was set', $this->server->getDescription());
    }

    public function setWebhookFail()
    {
        return '{
            "ok":false,
            "error_code":400,
            "description":"Error: Bad request: htttps:\/\/domain.host.org\/dir\/hook.php"
        }';
    }

    public function testSetWebhookFail() {
        $result = $this->setWebhookFail();
        $this->server =  new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertFalse($this->server->isOk());
        $this->assertNull($this->server->getResult());
        $this->assertEquals(400, $this->server->getErrorCode());
        $this->assertEquals("Error: Bad request: htttps://domain.host.org/dir/hook.php", $this->server->getDescription());
    }

    public function getUpdatesArray()
    {
        return '{
            "ok":true,
            "result":[
                {
                    "update_id":123,
                    "message":{
                        "message_id":90,
                        "from":{"id":123456789,"first_name":"John","username":"Mjohn"},
                        "chat":{"id":123456789,"first_name":"John","username":"Mjohn"},
                        "date":1441569067,
                        "text":"\/start"
                    }
                },
                {
                    "update_id":124,
                    "message":{
                        "message_id":91,
                        "from":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "chat":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "date":1441569073,
                        "text":"Hello!"
                    }
                },
                {
                    "update_id":125,
                    "message":{
                        "message_id":92,
                        "from":{"id":123456789,"first_name":"John","username":"MJohn"},
                        "chat":{"id":123456789,"first_name":"John","username":"MJohn"},
                        "date":1441569094,
                        "text":"\/echo hello!"
                    }
                },
                {
                    "update_id":126,
                    "message":{
                        "message_id":93,
                        "from":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "chat":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "date":1441569112,
                        "text":"\/echo the best"
                    }
                }
            ]
        }';
    }

    public function testGetUpdatesArray() {
        $result = $this->getUpdatesArray();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertCount(4, $this->server->getResult());
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\Update', $this->server->getResult()[0]);
    }

    public function getUpdatesEmpty()
    {
        return '{"ok":true,"result":[]}';
    }

    public function testGetUpdatesEmpty() {
        $result = $this->getUpdatesEmpty();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertNull($this->server->getResult());
    }

    public function getUserProfilePhotos()
    {
        return '{
            "ok":true,
            "result":{
                "total_count":3,
                "photos":[
                    [
                        {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHqgg5uEju-8IBAAEC","file_size":7402,"width":160,"height":160},
                        {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHWMuphij6_MIBAAEC","file_size":15882,"width":320,"height":320},
                        {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHNWdpQ9jz_cIBAAEC","file_size":46680,"width":640,"height":640}
                    ],
                    [
                        {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABO8UCGz_JLAAgI","file_size":7324,"width":160,"height":160},
                        {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABAlhB5Q_K0AAgI","file_size":15816,"width":320,"height":320},
                        {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABIIxOSHyayAAgI","file_size":46620,"width":640,"height":640}
                    ],
                    [
                        {"file_id":"AgABxG6_vmQaL2X0CUTAABMhd1n2RLaRSj6cAAgI","file_size":2710,"width":160,"height":160},
                        {"file_id":"AgADcxG6_vmQaL2X0EUTAABPXm1og0O7qwjKcAAgI","file_size":11660,"width":320,"height":320},
                        {"file_id":"AgADxG6_vmQaL2X0CUTAABMOtcfUmoPrcjacAAgI","file_size":37150,"width":640,"height":640}
                    ]
                ]
            }
        }';
    }

    public function testGetUserProfilePhotos()
    {
        $result = $this->getUserProfilePhotos();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');
        $server_result = $this->server->getResult();
        $photos = $server_result->getPhotos();

        //Photo count
        $this->assertCount(3, $photos);
        //Photo size count
        $this->assertCount(3, $photos[0]);

        $this->assertInstanceOf('\Longman\TelegramBot\Entities\UserProfilePhotos', $server_result);
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\PhotoSize', $photos[0][0]);
    }

    public function getFile()
    {
        return '{
            "ok":true,
            "result":{
                "file_id":"AgADBxG6_vmQaVf3qRzHYTAABD1hNWdpQ9qz_cIBAAEC",
                "file_size":46680,
                "file_path":"photo\/file_1.jpg"
            }
        }';
    }

    public function testGetFile()
    {
        $result = $this->getFile();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertInstanceOf('\Longman\TelegramBot\Entities\File', $this->server->getResult());
    }

    public function getChat()
    {
        //Request::getChat(['chat_id' => $chat_id])
        return '{
            "ok":true,
            "result":{
                "id":-1001036309999,
                "title":"TestChat",
                "username":"testchat",
                "type":"supergroup",
                "description":"Bot testing",
                "permissions":{
                    "can_send_messages":true,
                    "can_send_media_messages":false,
                    "can_send_polls":false,
                    "can_send_other_messages":false,
                    "can_add_web_page_previews":false,
                    "can_change_info":false,
                    "can_invite_users":false,
                    "can_pin_messages":false
                },
                "slow_mode_delay":60,
                "photo":{
                    "small_file_id":"aqadbaatYGUlgGaeaGadScQw7rB___-Y4AWeEQkXXR_raaiEBA",
                    "small_file_unique_id":"aqaDYGUlgGaev9EAAg",
                    "big_file_id":"aqadBaatYGuLggaeaWaDscQw7Rb___-y4AWeeQkXxShraaIEbA",
                    "big_file_unique_id":"AqadYGUlgGaewDeaaG"
                }
            }
        }';
    }

    public function testGetChat()
    {
        $result = $this->getChat();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());
        $server_result = $this->server->getResult();
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\Chat', $server_result);
        $this->assertEquals(60, $server_result->getSlowModeDelay());
    }

    public function getMe()
    {
        //Request::getMe();
        return '{
            "ok":true,
            "result":{
                "id":107647101,
                "is_bot":true,
                "first_name":"Bottone",
                "username":"Tonebot",
                "can_join_groups":true,
                "can_read_all_group_messages":false,
                "supports_inline_queries":true
            }
        }';
    }

    public function testGetMe()
    {
        $result = $this->getMe();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());
        $server_result = $this->server->getResult();
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\User', $server_result);
    }

    public function sendChatAction()
    {
        //Request::sendChatAction(['chat_id' => $this->getMessage()->getChat()->getId(), 'action' => 'typing']);
        return '{"ok":true,"result":true}';
    }

    public function testsendChatAction()
    {
        $result = $this->sendChatAction();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertTrue($this->server->getResult());
    }

    public function getChatAdministrator()
    {
        //Request::getChatAdministrators(['chat_id' => $chat_id]);
        return '{
            "ok":true,
            "result":[
                {"user":{
                    "id":123456,
                    "is_bot":true,
                    "first_name": "Mybot",
                    "username":"Mybotbot"
                    },
                "status":"administrator",
                "can_be_edited":false,
                "can_change_info":true,
                "can_delete_messages":true,
                "can_invite_users":true,
                "can_restrict_members":true,
                "can_pin_messages":true,
                "can_promote_members":false,
                "is_anonymous":false},
                {"user":{
                    "id":12345663,
                    "is_bot":false,
                    "first_name":"Tom",
                    "last_name":"John",
                    "username":"tjohn",
                    "language_code":"ien"},
                    "status":"creator",
                    "is_anonymous":false}
                ]
            }';
    }

    public function testGetChatAdministrator()
    {
        $result = $this->getChatAdministrator();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());

        $this->assertCount(2, $this->server->getResult());
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\ChatMember', $this->server->getResult()[0]);
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\ChatMember', $this->server->getResult()[1]);
    }


    public function getChatMember()
    {
        //Request::getChatMember(['chat_id' => $chat_id, 'user_id' => $user_id]);
        return '{
            "ok":true,
            "result": {
                "user":{
                    "id":12345,
                    "is_bot":false,
                    "first_name":"Tom",
                    "last_name":"John",
                    "username":"tJohn",
                    "language_code":"en"},
                "status":"creator",
                "is_anonymous":false
            }
        }';
    }

    public function testGetChatMember()
    {
        $result = $this->getChatMember();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\ChatMember', $this->server->getResult());
    }

    public function getMyCommands()
    {
        // Request::getMyCommands();
        return '{
            "ok":true,
            "result":[
                {"command":"settings","description":"Set your settings"},
                {"command":"help","description":"Need help?"},
                {"command":"donate","description":"Make a donation!"}
            ]
        }';
    }

    public function testGetMyCommands()
    {
        $result = $this->getMyCommands();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());

        $this->assertCount(3, $this->server->getResult());
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\BotCommand', $this->server->getResult()[0]);
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\BotCommand', $this->server->getResult()[1]);
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\BotCommand', $this->server->getResult()[2]);
    }

    //TODO
    //Request::editMessageText($data);
    //Request::editMessageReplyMarkup($data);
    //Request::answerCallbackQuery($data);
    //Request::setMyCommands($data);

    public function testSetGeneralTestFakeResponse() {
        //setWebhook ok
        $fake_response = Request::generateGeneralFakeServerResponse();

        $this->server =  new ServerResponse($fake_response, 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertTrue($this->server->getResult());
        $this->assertNull($this->server->getErrorCode());
        $this->assertEquals('', $this->server->getDescription());

        //sendMessage ok
        $fake_response = Request::generateGeneralFakeServerResponse(['chat_id' => 123456789, 'text' => 'hello']);

        $this->server =  new ServerResponse($fake_response, 'testbot');
        $server_result = $this->server->getResult();

        $this->assertTrue($this->server->isOk());
        $this->assertNull($this->server->getErrorCode());
        $this->assertNull($this->server->getDescription());
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\Message', $server_result);

        //Message
        $this->assertEquals('1234', $server_result->getMessageId());
        $this->assertEquals('1441378360', $server_result->getDate());
        $this->assertEquals('hello', $server_result->getText());
        //Message //User
        $this->assertEquals('123456789', $server_result->getFrom()->getId());
        $this->assertEquals('botname', $server_result->getFrom()->getFirstName());
        $this->assertEquals('namebot', $server_result->getFrom()->getUserName());
        //Message //Chat
        $this->assertEquals('123456789', $server_result->getChat()->getId());
        $this->assertEquals('', $server_result->getChat()->getFirstName());
        $this->assertEquals('', $server_result->getChat()->getUserName());

        //... they are not finished...
    }
}
