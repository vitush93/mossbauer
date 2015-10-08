<?php

namespace Mossbauer\Core;


use Latte\Engine;
use Nette\Database\Connection;

class Container
{
    /**
     * Holds all container instances.
     *
     * @var array
     */
    private $instances = array();

    /**
     * Loaded configuration
     *
     * @var array
     */
    private $config = array();

    /** @var Container|NULL */
    private static $self = NULL;

    /**
     * @param array $config
     */
    function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     * @return mixed
     */
    static function get($name)
    {
        if (self::$self == NULL) self::create();

        if (!isset(self::$self->instances[$name])) {
            self::$self->instances[$name] = self::$self->{"create" . ucfirst($name)}();
        }

        return self::$self->instances[$name];
    }

    private static function create()
    {
        if (self::$self == NULl) {
            self::$self = new Container(require_once ROOT_DIR . '/config.php');
        }
    }

    /**
     * @return Connection
     */
    function createDatabase()
    {
        $dbconfig = $this->config['database'];

        return new Connection("mysql:host={$dbconfig['host']};dbname={$dbconfig['dbname']}", $dbconfig['user'], $dbconfig['password']);
    }

    /**
     * @return Engine
     */
    function createLatte()
    {
        $latte = new Engine();
        $latte->setTempDirectory($this->config['latte']['tempDir']);

        return $latte;
    }

}