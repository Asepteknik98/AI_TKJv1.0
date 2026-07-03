<?php
namespace App\Config;

class Config {
    public static $db_host = '127.0.0.1';
    public static $db_name = 'ai_tkj';
    public static $db_user = 'root';
    public static $db_pass = '';

    // Set your OpenAI API key here if you don't use env variable.
    public static $openai_api_key = 'YOUR_OPENAI_API_KEY';

    public static function getOpenAIKey()
    {
        $key = getenv('OPENAI_API_KEY');
        if ($key !== false && $key !== '') {
            return $key;
        }
        if (self::$openai_api_key && self::$openai_api_key !== 'YOUR_OPENAI_API_KEY') {
            return self::$openai_api_key;
        }
        return null;
    }
}
