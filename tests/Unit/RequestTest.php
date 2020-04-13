<?php

namespace Tests\Unit;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;

class RequestTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Telegram
    */
    private $telegram;

    /**
    * @var array
    */
    private $data = [
            'user_id' => 123,
            'chat_id' => 123,
            'text'    => 'text'
            ];

    /**
    * setUp
    */
    protected function setUp(): void
    {
        $this->telegram = new Telegram('apikey', 'testbot');
    }

    public function testSendMethod()
    {
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::send('non_existing_method');
    }

    public function testSendMessageMethods()
    {
        Request::getMe();

        Request::sendMessage($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendMessage([]);
    }

    public function testForwardMethod()
    {
        Request::forwardMessage($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::forwardMessage([]); #throw new TelegramException('Data is empty!');
    }

    public function testSendPhotoMethod()
    {
        Request::sendPhoto($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendPhoto([]);
    }

    public function testSendAudioMethod()
    {
        Request::sendAudio($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendAudio([]);
    }

    public function testSendDocumentMethod()
    {
        Request::sendDocument($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendDocument([]);
    }

    public function testSendStickerMethod()
    {
        Request::sendSticker($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendSticker([]);
    }

    public function testSendVideoMethod()
    {
        Request::sendVideo($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendVideo([]);
    }

    public function testSendVoiceMethod()
    {
        Request::sendVoice($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendVoice([]);
    }

    public function testSendLocationMethod()
    {
        Request::sendLocation($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendLocation([]);
    }

    public function testSendVenueMethod()
    {
        Request::sendVenue($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendVenue([]);
    }

    public function testSendContactMethod()
    {
        Request::sendContact($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendContact([]);
    }

    public function testSendChatActionMethod()
    {
        Request::sendChatAction($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::sendChatAction([]);
    }

    public function testGetUserProfilePhotosMethod()
    {
        Request::getUserProfilePhotos($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::getUserProfilePhotos([]);

        // Putting together
        Request::getUpdates($this->data);
        Request::setWebhook('http://www.myserver.com');
        //TODO $max_connections = null
    }

    public function testGetFileMethod()
    {
        Request::getFile($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::getFile([]);
    }

    public function testKickChatMemberMethod()
    {
        Request::kickChatMember($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::kickChatMember([]);
    }

    public function testLeaveChatMethod()
    {
        Request::leaveChat($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::leaveChat([]);
    }

    public function testUnbanChatMemberMethod()
    {
        Request::unbanChatMember($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::unbanChatMember([]);
    }

    public function testGetChatMethod()
    {
        Request::getChat($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::getChat([]);
    }

    public function testGetChatAdministratorMethod()
    {
        Request::getChatAdministrators($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::getChatAdministrators([]);
    }

    public function testGetChatMembersCountMethod()
    {
        Request::getChatMembersCount($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::getChatMembersCount([]);
    }

    public function testGetChatMembersMethod()
    {
        Request::getChatMember($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::getChatMember([]);
    }

    public function testCallbackQueryMethod()
    {
        Request::answerCallbackQuery($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::answerCallbackQuery([]);
    }

    public function testAnswerInlineQueryMethod()
    {
        Request::answerInlineQuery($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::answerInlineQuery([]);
    }

    public function testEditMessageTextMethod()
    {
        Request::editMessageText($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::editMessageText([]);
    }

    public function testEditMessageCaptionMethod()
    {
        Request::editMessageCaption($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::editMessageCaption([]);
    }

    public function testEditMessageReplyMarkupMethod()
    {
        Request::editMessageReplyMarkup($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::editMessageReplyMarkup([]);

        // Putting together
        Request::getMyCommands();
    }

    public function testSetMyCommandsMethod()
    {
        Request::setMyCommands($this->data);
        $this->expectException(\Longman\TelegramBot\Exception\TelegramException::class);
        Request::setMyCommands([]);
    }

    public function testEmptyResponseMethod()
    {
        $server_response = Request::emptyResponse();
        $this->assertEquals(true, $server_response->isOk());
        $this->assertEquals(true, $server_response->getResult());
    }
}
