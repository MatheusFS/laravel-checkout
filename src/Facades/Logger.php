<?php

namespace MatheusFS\Laravel\Checkout\Facades;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Logger {

    public static function log($type, $message, $model = null) {

        $model = $model ?? debug_backtrace()[1]['function'];
        // Logger::_logInEmail("$model." . strtoupper($type) . ": $message");
        Logger::_logInFile("$model." . strtoupper($type) . ": $message");
    }

    protected static function _logInEmail($message) {

        Mail::raw($message, function ($message) {
            $message->to('matheus@refresher.com.br');
            $message->to('marketplace@refresher.com.br');
        });
    }

    protected static function _logInFile($message) {

        $file_path = '/checkout/logger.log';
        $disk = Storage::disk('storage_logs');
        $date_string = '[' . date('Y-m-d H:i:s') . ']';
        $content = "$date_string $message " . PHP_EOL;

        return !$disk->exists($file_path)
        ? $disk->put($file_path, $content)
        : $disk->append($file_path, $content);
    }
}