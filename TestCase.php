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

    private $_defaultFetchMode = \PDO::FETCH_ASSOC;

    /**
     * @param string $fetchmode \PDO::FETCH_*
     */
    protected function setFetchMode($fetchmode)
    { $this->_defaultFetchMode = $fetchmode; }

    /**
     * @return string $fetchmode \PDO::FETCH_*
     */
    protected function getFetchMode()
    { return $this->_defaultFetchMode; }

    /**
     * @param string $sql
     * @return \PDOStatement
     */
    protected function query($sql)
    {
        $adapter = $this->getAdapter();
        $stmt = $adapter->query($sql)->execute();
        $result = $stmt->getResource();
        $mode = $this->getFetchMode();
      
        $result->setFetchMode($mode);
        return $result;
    }


    /**
     * @param string $table
     * @param array $data
     * @return Adapter\Driver\ResultInterface
     */
    protected function insert($table, array $data)
    {
        $insert = new Sql\Insert($table);
        $insert->values($data, $insert::VALUES_MERGE);

        $adapter = $this->getAdapter();
        $sqlobj = new Sql\Sql($adapter);
        $stmt = $sqlobj->prepareStatementForSqlObject($insert);
        return $stmt->execute();
    }

    /**
     * @param string $table
     * @param array $data
     * @param string $where SQL string
     * @return Adapter\Driver\ResultInterface
     */
    protected function update($table, array $data, $where = '')
    {
        $update = new Sql\Update($table);
        $update->set($data);
        if ($where !== '') {
            $update->where($where);
        }

        $adapter = $this->getAdapter();
        $sqlobj = new Sql\Sql($adapter);
        $stmt = $sqlobj->prepareStatementForSqlObject($update);
        return $stmt->execute();
    }

}