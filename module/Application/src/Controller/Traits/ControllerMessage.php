<?php
namespace Application\Controller\Traits;

trait ControllerMessage
{
    /**
     * @var array - system messages
     */
    private $_messages = [];

    /**
     * Add message to system message queue.
     *
     * @param string $message Message to note
     * @param string $level   Message level eg.g error or info
     */
    private function addMessage($message, $level = self::MESSAGE_INFO)
    {
        $this->_messages[$level][] = $message;
    }
}
