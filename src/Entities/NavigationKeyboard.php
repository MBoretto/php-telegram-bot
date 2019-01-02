<?php

namespace Longman\TelegramBot\Entities;

class NavigationKeyboard extends InlineKeyboardMarkup
{
    /**
     * NavigationKeyboard constructor.
     *
     * @param string $payload_left
     * @param string $payload_right
     * @param string $payload_menu
     * @param string $symbol_menu
     */
    public function __construct($payload_left, $payload_right, $payload_menu = null, $symbol_menu = '☰')
    {
        $buttons = [];
        if (!is_null($payload_left)) {
            $buttons[] = new InlineKeyboardButton(['text' => '◁', 'callback_data' => $payload_left]);
        }

        if (!is_null($payload_menu)) {
            $buttons[] = new InlineKeyboardButton(['text' => $symbol_menu, 'callback_data' => $payload_menu]);
        }

        if (!is_null($payload_right)) {
            $buttons[] = new InlineKeyboardButton(['text' => '▷', 'callback_data' => $payload_right]);
        }
        // call Grandpa's constructor
        parent::__construct(['inline_keyboard' => [$buttons]]);
    }
}
