<?php

namespace jocoonopa\LINEBot;

use LINE\LINEBot;

class Linebot 
{
    protected $bot;

    public function __construct(LINEBot $bot)
    {
        $this->setBot($bot);
    }

    /**
     * @return mixed
     */
    public function getBot()
    {
        return $this->bot;
    }

    /**
     * @param mixed $bot
     *
     * @return self
     */
    public function setBot(LINEBot $bot)
    {
        $this->bot = $bot;

        return $this;
    }
}