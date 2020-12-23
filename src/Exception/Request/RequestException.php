<?php

namespace Longman\TelegramBot\Exception\Request;

/**
 * Main exception class used for request exception handling
 */
class RequestException extends \Exception
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
