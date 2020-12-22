<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Exception;

/**
 * Main exception class used for exception handling
 */
class TelegramException extends \Exception
{
    /**
     * parameters
     *
     * @var array
     */
    protected $parameters = [];

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        $data = json_decode($message, true);
        $this->parameters = $data['parameters'];
        //$data['description']
        // make sure everything is assigned properly
        parent::__construct($data['description'], $code, $previous);
    }

    /**
     * Get the parameters
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getParameters($key)
    {
        return $this->parameters[$key];
    }
}
