<?php

namespace App\Services;

use OpenAI;

class AIService
{
    public function chat($message)
    {
        $client = OpenAI::factory()
    ->withApiKey(env('OPENAI_API_KEY'))
    ->withHttpClient(
        new \GuzzleHttp\Client([
            'verify' => false
        ])
    )
    ->make();
        
        $response = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => '
أنت مساعد ذكي لمنصة الإدارة القانونية.

ساعد المستخدمين في:
- رفع الاستشارات.
- متابعة حالة الاستشارة.
- فهم النظام.
- توجيههم للقسم المناسب.

أجب باللغة العربية فقط.
'
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ]
        ]);

        return $response->choices[0]->message->content;
    }
}