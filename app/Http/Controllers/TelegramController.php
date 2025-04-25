<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramUserService;
use OpenApi\Annotations as OA;

class TelegramController extends Controller
{
    protected TelegramUserService $telegramUserService;

    public function __construct(TelegramUserService $telegramUserService)
    {
        $this->telegramUserService = $telegramUserService;
    }

    /**
     * @OA\Post(
     *     path="/api/telegram/webhook",
     *     summary="Handle Telegram webhook",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message"},
     *             @OA\Property(property="message", type="object",
     *                 @OA\Property(property="text", type="string", example="/start"),
     *                 @OA\Property(property="chat", type="object",
     *                     @OA\Property(property="id", type="integer", example=123456)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
     */
    public function webhook(Request $request)
    {
        $data = $request->all();

        if (!isset($data['message'])) {
            return response('OK');
        }

        $message = $data['message'];
        $chatId = $message['chat']['id'];
        $text = trim($message['text'] ?? '');

        if ($text === '/start') {
            $this->telegramUserService->subscribe($chatId, $message);
        } elseif ($text === '/stop') {
            $this->telegramUserService->unsubscribe($chatId);
        }

        return response('OK');
    }
}
