<?php
namespace Zend\Db;

abstract class TestCase extends \PHPUnit_Extensions_Database_TestCase
{
    static private $_adapter;
    private $_connection;

    final protected function getConnection()
    {
        $params = $this->getDbConfig();
        if(self::$_adapter === null) {
            $connection = new Adapter\Adapter($params);
            self::$_adapter = $connection;
        }

        if ($this->_connection === null) {
            $pdo = self::$_adapter->getDriver()
                ->getConnection()->getResource();
            $this->_connection = $this->createDefaultDBConnection($pdo);
        }

        return $this->_connection;
    }

    /**
     * @see \Zend\Db\Adapter\Adapter
     * @return array Zend\Db\Adapter\Adapter parameters
     */
    abstract protected function getDbConfig();

    /**
     * @return \Zend\Db\Adapter\Adapter
     */
    protected function getAdapter()
    {
        return self::$_adapter;
    }
}