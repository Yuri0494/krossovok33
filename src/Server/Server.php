<?php

namespace App\Server;

use App\TelegramBotRequest\TelegramBotRequest;
use App\TelegramBot\TelegramBot;
use App\Buttons\ButtonService;
use App\Repository\UserRepository;
use App\Repository\ChatRepository;

class Server {
    const START_COMMAND = '/start';
    const GET_COMMAND = '/subscriptions';
    const GET_THIS_COMMAND = '/get-'; // Подписка и ее настройки
    const GET_NEXT = '/get-next';
    const GET_PREV = '/get-prev';
    const SET_SERIES = '/set-series';
    const UNSUBSCRIBE = '/unsubscribe';
  
    public function __construct(
        private ChatRepository $chatRepository,
        private UserRepository $userRepository,
        private TelegramBot $tgBot,
        private TelegramBotRequest $req,
    )
    {}

    public function handleRequest()
    {
        try {
            switch($this->req->type) {
                case 'not handled':
                    break;
                case 'message' && array_key_exists('text', $this->req->getRequestData()):
                    $this->handleMessageRequest();
                    break;
                case 'callback_query':
                    $this->handleCallbackRequest();
                    break;
                case 'my_chat_member':
                    $this->handleMyChatMember();
                    break;
            }
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    // Обработка текстового сообщения
    private function handleMessageRequest()
    {
        // Старт приложения
        if ($this->req->getCommand() === Server::START_COMMAND) {
            return $this->actionStart();
        }
        // Роут для получения конкретной серии
        if (
            str_contains($this->req->user->getCurrentCommand(), Server::GET_THIS_COMMAND) &&
            is_int((int) $this->req->getCommand())
        ) {
            return;
        }
        // Роут для установки серии, с которой будет продолжен просмотр
        if (
            str_contains($this->req->user->getCurrentCommand(), Server::SET_SERIES) &&
            is_int((int) $this->req->getCommand())
        ) {
            return;
        }
    }

    // Обработка коллбэк-сообщения (пользователь нажал на кнопку)
    private function handleCallbackRequest()
    {
        // Удаляем предыдущее сообщение пользователя
        if ($this->req->getMessageId()) {
            $this->tgBot->api->sendDeleteMessage($this->req->chat->getChatId(), $this->req->getMessageId());
        }

        $command = $this->req->getCommand();
        
        switch ($command) {
            case Server::START_COMMAND:
                $this->actionStart();
                break;
            case Server::GET_COMMAND:

                break;
            case Server::GET_NEXT:

                break;
            case Server::GET_PREV:

                break;
            case Server::SET_SERIES:

                break;
            case str_contains($command, Server::GET_THIS_COMMAND):

                break;
            case Server::UNSUBSCRIBE:

                break;
            default:

                break;
        }
    }

    private function actionStart()
    {
        $this->userRepository->setCurrentCommand($this->req->user, '');

        $this->tgBot->api->sendMessage(
            $this->req->chat->getChatId(), 
            'Приветики!', 
            ['reply_markup' => ButtonService::getInlineKeyboardForStart()]
        );
    }

    private function handleMyChatMember()
    {
        $req = $this->req->getRequestData();
        $status = $req['new_chat_member']['status'] ?? ''; 

        if (in_array($status, ['left', 'kicked'])) {
            $this->req->chat = $this->chatRepository->createOrFind($req['chat']);
        }
    }
}