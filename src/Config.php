<?php
namespace Onfido;

class Config
{
    public static $instance;
    public $token = '';
    public $version = '1';
    public $page = 1;
    public $per_page = 20;
    public $debug;

    protected function __construct()
    {

    }

    public static function init()
    {
        if (static::$instance === null)
            static::$instance = new static();

        return static::$instance;
    }

    public function set_token($token)
    {
        $this->token = $token;

        return $this;
    }

    public function set_version($version)
    {
        $this->version = $version;

        return $this;
    }

    public function paginate($page = null, $per_page = null)
    {
        if ($page !== null)
            $this->page = $page;

        if ($per_page !== null)
            $this->per_page = $per_page;

        return $this;
    }

    public function debug()
    {
        $this->debug = true;

        return $this;
    }
}
