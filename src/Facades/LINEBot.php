<?php

namespace jocoonopa\LINEBot\Facades;

use Illuminate\Support\Facades\Facade;

class LINEBot extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'linebot';
    }
}