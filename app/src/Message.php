<?php

namespace App;

class Message {
    public function __construct() {
    }

    public static function messageTo() {
        $output = `cat ../resource/views/getEmailAddress.html`;
        return $output;
    }

    public static function getReply($email, $link) {
        $message = `cat ../resource/views/emailMessage.html`;
        $message = preg_replace(['/%%email%%/', '/%%link%%/'], [$email, $link], $message);
        return $message;
    }
}
