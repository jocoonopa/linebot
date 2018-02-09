<?php

namespace jocoonopa\LINEBot;

use LINE\LINEBot as OrgLINEBot;
use LINE\LINEBot\MessageBuilder;
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

    /**
     * @var LINE\LINEBot\MessageBuilder
     */
    protected $msgBuilder = null;

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

        if (!config('linebot.is_debug') || !($response instanceof Response)) {
            return $response;
        }

        foreach ($arguments as $argument) {
            if ($argument instanceof MessageBuilder) {
                $this->msgBuilder = $argument;

                break;
            }
        }

        Log::debug($this->getDebugContent(), [
            'content' => $response->getJSONDecodedBody(),
            'status' => $response->getHTTPStatus(),
            'headers' => $response->getHeaders(),
        ]);

        return $response;
    }

    protected function getDebugContent()
    {
        return is_object($this->msgBuilder) ? get_class($this->msgBuilder) : 'null';
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