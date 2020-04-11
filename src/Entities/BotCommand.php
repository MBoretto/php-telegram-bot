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

class BotCommand extends Entity
{
    protected $command; //Text of the command, 1-32 characters. Can contain only lowercase English letters, digits and underscores
    protected $description; // description Description of the command, 3-256 characters

    /**
     * BotCommand constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->command = isset($data['command']) ? $data['command'] : null;
        if (empty($this->command)) {
            throw new TelegramException('command is empty!');
        }
        $this->description = isset($data['description']) ? $data['description'] : null;
        if (empty($this->description)) {
            throw new TelegramException('description is empty!');
        }
    }
}
