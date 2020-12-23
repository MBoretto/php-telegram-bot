<?php

namespace Longman\TelegramBot\Entities;

class ServerResponse extends Entity
{
    protected $ok;
    protected $result;
    protected $error_code;
    protected $description;

    /**
     * ServerResponse constructor.
     *
     * @param array $data
     * @param $bot_name
     */
    public function __construct(array $data, $bot_name)
    {
        $this->error_code = null;
        $this->description = null;
        $this->result = null;
        if (isset($data['ok']) and isset($data['result'])) {
            $this->ok = $data['ok'];
            if (is_array($data['result'])) {
                if ($this->isAssoc($data['result'])) {
                    if (isset($data['result']['total_count'])) {
                        //Response from getUserProfilePhotos
                        $this->result = new UserProfilePhotos($data['result']);
                        return;
                    }
                    if (isset($data['result']['file_id'])) {
                        //Response from getFile
                        $this->result = new File($data['result']);
                        return;
                    }
                    if (isset($data['result']['type'])) {
                        //Response from getChat
                        $this->result = new Chat($data['result']);
                        return;
                    }
                    if (isset($data['result']['username'])) {
                        //Response from getMe
                        $this->result = new User($data['result']);
                        return;
                    }
                    if (isset($data['result']['user'])) {
                        //Response from getChatMember
                        $this->result = new ChatMember($data['result']);
                        return;
                    }
                    //Response from sendMessage
                    $this->result = new Message($data['result'], $bot_name);
                    return;
                }

                //Response from getChatAdministrators
                if (isset($data['result'][0]['user'])) {
                    $this->result = [];
                    foreach ($data['result'] as $user) {
                        array_push($this->result, new ChatMember($user));
                    }
                    return;
                }

                // Get My Command response
                if (isset($data['result'][0]['command'])) {
                    foreach ($data['result'] as $bot_command) {
                        $this->result[] = new BotCommand($bot_command, $bot_name);
                    }
                    return;
                }

                //Get Update
                foreach ($data['result'] as $update) {
                    $this->result[] = new Update($update, $bot_name);
                }
                return;
            }
            if ($data['ok'] and $data['result'] === true) {
                //Response from setWebhook set
                $this->result = true;

                if (isset($data['description'])) {
                    $this->description = $data['description'];
                }
                return;
            }
            if (is_numeric($data['result'])) {
                //Response from getChatMembersCount
                $this->result = $data['result'];
                return;
            }

            $this->ok = false;
            $this->error_code = $data['error_code'];
            $this->description = $data['description'];
            return;
        }

        //webHook not set
        $this->ok = false;

        if (isset($data['result'])) {
            $this->result = $data['result'];
        }

        if (isset($data['error_code'])) {
            $this->error_code = $data['error_code'];
        }

        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        return;
    }

    //must be an array
    protected function isAssoc(array $array)
    {
        return (bool) count(array_filter(array_keys($array), 'is_string'));
    }
    public function isOk()
    {
        return $this->ok;
    }
    public function getResult()
    {
        return $this->result;
    }
    public function getErrorCode()
    {
        return $this->error_code;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function printError()
    {
        return 'Error N: '.$this->getErrorCode().' Description: '.$this->getDescription();
    }
}
