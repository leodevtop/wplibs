<?php

namespace vustech\wp\base;
use Monolog\Handler;

class WpMailHandler extends MailHandler
{

    function __construct($to, $subject, $from) {

        parent::__construct($level, $bubble);
        $this->to = $to;
        $this->subject = $subject;
        $this->from = $from;
        $this->maxColumnWidth = $maxColumnWidth;
    }

    protected function send($content, array $records) {
        wp_mail($this->to, $this->subject, $content, 'Content-type: text/html');
    }

}