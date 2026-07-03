<?php
namespace App\Lib;

use App\Config\Config;

class AI {
    public static function chat($prompt, $mode = 'auto')
    {
        $apiKey = Config::getOpenAIKey();
        $useLocal = $mode === 'local' || ($mode !== 'openai' && !$apiKey);

        if ($useLocal) {
            return ['answer' => self::localFallback($prompt), 'raw' => ['source' => 'local_fallback'], 'mode' => 'local'];
        }

        if ($mode === 'openai' && !$apiKey) {
            throw new \Exception('OpenAI API key tidak ditemukan. Setel kunci di config/config.php atau export environment variable OPENAI_API_KEY.');
        }

        $data = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant for SMK TKJ students. Use simple bahasa Indonesia explanations.'],
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
        return ['answer' => $answer, 'raw' => $resData, 'mode' => 'openai'];
    }

    private static function localFallback(string $prompt): string
    {
        $key = strtolower(trim($prompt));

        $answers = [
            'apa itu ip address' => "IP Address adalah alamat unik untuk setiap perangkat dalam jaringan komputer. Dengan IP Address, perangkat dapat saling mengenali dan bertukar data. Contoh format IPv4 adalah 192.168.1.1.",
            'jelaskan vlan' => "VLAN (Virtual Local Area Network) adalah cara memisahkan jaringan di dalam switch secara logis. Dengan VLAN, beberapa grup perangkat dapat berkomunikasi sendiri tanpa harus berada di jaringan fisik terpisah.",
            'apa fungsi router' => "Router berfungsi meneruskan paket data antar jaringan. Router menghubungkan jaringan lokal ke internet atau ke jaringan lain, serta menentukan jalur terbaik untuk data.",
            'bagaimana cara konfigurasi dhcp' => "Konfigurasi DHCP biasanya dilakukan pada server atau router dengan menentukan rentang alamat IP yang boleh dibagikan, gateway, DNS, dan waktu sewa. Klien akan menerima alamat otomatis dari server DHCP.",
            'apa itu topologi star' => "Topologi star adalah susunan jaringan di mana semua perangkat terhubung ke satu pusat (hub atau switch). Jika satu kabel bermasalah, hanya perangkat tersebut yang terpengaruh.",
            'jelaskan subnetting' => "Subnetting adalah membagi jaringan IP besar menjadi beberapa subnet lebih kecil. Tujuannya adalah mengatur alamat IP secara efisien dan meningkatkan keamanan serta performa jaringan.",
        ];

        foreach ($answers as $pattern => $value) {
            if (strpos($key, $pattern) !== false) {
                return $value;
            }
        }

        if (stripos($key, 'soal') !== false || stripos($key, 'pilihan ganda') !== false) {
            return self::localQuestions($prompt);
        }

        if (stripos($key, 'materi') !== false || stripos($key, 'pengertian') !== false || stripos($key, 'kesimpulan') !== false) {
            return self::localMaterial($prompt);
        }

        return "Maaf, AI gratis tidak dapat menjawab secara spesifik untuk topik tersebut. Silakan isi OpenAI API key di config/config.php atau export OPENAI_API_KEY untuk mendapatkan jawaban otomatis yang lebih lengkap.";
    }

    private static function localQuestions(string $prompt): string
    {
        $topic = self::extractTopic($prompt);
        $topicLabel = $topic ?: 'topik yang kamu pilih';
        return "1. Apa pengertian {$topicLabel}?\nA. ...\nB. ...\nC. ...\nD. ...\nE. ...\n\nKunci Jawaban: A\n\nPenjelasan: {$topicLabel} adalah ...";
    }

    private static function localMaterial(string $prompt): string
    {
        $topic = self::extractTopic($prompt);
        $topicTitle = $topic ?: 'Materi ini';
        return "Pengertian:\n{$topicTitle} adalah ...\n\nTujuan Pembelajaran:\n- Memahami konsep dasar {$topicTitle}.\n- Mengimplementasikan pada jaringan TKJ.\n\nMateri:\n- Pengenalan {$topicTitle}.\n- Langkah dasar.\n- Contoh penggunaan.\n\nKesimpulan:\n{$topicTitle} membantu siswa memahami konsep dan aplikasi dalam dunia jaringan.";
    }

    private static function extractTopic(string $prompt): string
    {
        $prompt = trim(str_ireplace(['buat', 'materi', 'soal', 'tentang', 'untuk', 'siswa', 'smk', 'tkj'], '', $prompt));
        $prompt = preg_replace('/[^a-z0-9\s]/i', '', $prompt);
        $prompt = trim($prompt);
        return $prompt;
    }
}
