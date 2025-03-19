<?php

namespace App\Buttons;

use App\Server\Server;

final class ButtonService {

    private function __construct()
    {}

    public static function getInlineKeyboardForStart(): string
    {
        $buttons = [];
        foreach(['1'] as $item) {
            $buttons[][] = Button::create('тест', '/start')->toArray();
        }

        $buttons[][] = Button::create('Просто кнопка', Server::GET_COMMAND)->toArray();

        return json_encode([
                'inline_keyboard' => $buttons
            ]);
    }
}