<?php

class TestContainer extends \Illuminate\Container\Container
{
    /**
     * Needed because this is called within Console\Command but it exists on Foundation\Application
     */
    public function getNamespace()
    {
        return 'App\\';
    }
}
