<?php

namespace jocoonopa\LINEBot;

use LINE\LINEBot as OrgLINEBot;
use LINE\LINEBot\Response;
use Log;

class LINEBot
{
    /**
     * @var \LINE\LINEBot
     */
    protected $bot;

    /**
     * Line accesstoken, used to get user profile
     * 
     * @var string
     */
    protected $accessToken = null;

    public function __construct(OrgLINEBot $bot = null)
    {
        $this->setBot($bot);
    }

    /**
     * LINEBot proxy
     * 
     * @param  string $name 
     * @param  array $arguments
     * @return \LINE\LINEBot\Response
     */
    public function __call($name, $arguments)
    {
        $response = call_user_func_array([$this->getBot(), $name], $arguments);

        if (config('linebot.is_debug') && $resopnse instanceof Response) {
            Log::debug($response->getJSONDecodedBody());
        }

        return $response;
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
    public function setBot(OrgLINEBot $bot)
    {
        $this->bot = $bot;

        return $this;
    }
}