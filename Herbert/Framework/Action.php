<?php namespace Herbert\Framework;

use Exception;

/**
 * @see http://getherbert.com
 */
class Action
{
    /**
     * @var \Herbert\Framework\Application
     */
    protected $app;

    /**
     * @param \Herbert\Framework\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function add($name, $callable)
    {
        add_action($name, function () use ($callable)
        {

            if (strpos($callable, '::') !== false)
            {
                list($api, $method) = explode('::', $callable);

                global $$api;

                if ($$api === null)
                {
                    throw new Exception("API '{$api}' not set!");
                }

                $callable = $$api->get($method);

                if ($callable === null)
                {
                    throw new Exception("Method '{$method}' not set!");
                }
            }

            $this->app->call($callable);

            return true;
        });
    }
}