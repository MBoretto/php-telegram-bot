<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class User extends Entity
{

    protected $id;
    protected $first_name;
    protected $last_name;
    protected $username;

    /**
     * User constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {

        $this->id = isset($data['id']) ? $data['id'] : null;
        if (empty($this->id)) {
            throw new TelegramException('id is empty!');
        }

        $this->first_name = isset($data['first_name']) ? $data['first_name'] : null;

        $this->last_name = isset($data['last_name']) ? $data['last_name'] : null;
        $this->username = isset($data['username']) ? $data['username'] : null;
    }

    public function getId()
    {

        return $this->id;
    }

    public function getFirstName()
    {

        return $this->first_name;
    }

    public function getLastName()
    {

        return $this->last_name;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function tryMention($markdown = false)
    {
        if (!is_null($this->username)) {
            if ($markdown) {
                return '@' . $this->stripMarkDown($this->username);
            }
            return '@' . $this->username;
        }
        $name = $this->first_name;
        if (!is_null($this->last_name)) {
            $name .= ' ' . $this->last_name;
        }
        if ($markdown) {
            return $this->stripMarkDown($name);
        }
        return $name;
    }

    public function stripMarkdown($string)
    {
        $string = str_replace('[', '\[', $string);
        $string = str_replace('`', '\`', $string);
        $string = str_replace('*', '\*', $string);
        return str_replace('_', '\_', $string);
    }
}
