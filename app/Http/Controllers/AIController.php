<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;

class AIController extends Controller
{
    public function index()
    {
        return view('ai-chat');
    }

   public function send(Request $request)
{
    try {

        $ai = new \App\Services\AIService();

        $reply = $ai->chat(
            $request->message
        );

        return response()->json([
            'reply' => $reply
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'reply' => $e->getMessage()
        ]);
    }
}
}