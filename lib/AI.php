<?php
namespace App\Lib;

use App\Config\Config;

class AI {
    public static function chat($prompt)
    {
        $apiKey = Config::getOpenAIKey();
        if (!$apiKey) {
            throw new \Exception('OpenAI API key tidak ditemukan. Setel kunci di config/config.php atau export environment variable OPENAI_API_KEY.');
        }

        $data = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant for SMK TKJ students. Use simple bahasa Indonesia explanations.'] ,
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 800,
            'temperature' => 0.2,
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($res === false) {
            throw new \Exception('Request failed: ' . $err);
        }

        $resData = json_decode($res, true);
        if (isset($resData['error'])) {
            throw new \Exception('API error: ' . json_encode($resData['error']));
        }

        $answer = $resData['choices'][0]['message']['content'] ?? '';
        return ['answer' => $answer, 'raw' => $resData];
    }
}
