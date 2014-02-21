<?php
require_once __DIR__ . '/../TestCase.php';
 
class testcaseTest extends \Zend\Db\TestCase
{
    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/' . __CLASS__ .'_ds.xml');
    }

    protected function getDbConfig()
    {
        return array('driver' => 'Pdo_Sqlite',
                     'database' => __DIR__ . '/sqlite/testcase.db'
                     );
    }

    public function testSetup()
    {
        $result = $this->getConnection()->createDataSet();
        $ext = $this->getDataSet();
        $this->assertDataSetsEqual($ext, $result);
    }

    public function testQuery()
    {
        $sql = 'select * from sample';
        $row = array('id'=>'1',
                     'name'=>'testname',
                     'create_datetime'=>'2000-01-01');
        $ext = array($row);

        $stmt = $this->query($sql);
        $this->assertEquals( 'PDOStatement', get_class($stmt));

        $result = $stmt->fetchAll();
        $this->assertEquals($ext, $result);
    }

    public function testInsert()
    {
        $table = 'sample';
        $data = array('id'=>2,'name'=>'testtest','create_datetime'=>'2011-01-01');
        $this->insert($table, $data);
        $stmt = $this->query("select * from sample where id = 2");
        $result = $stmt->fetch();
        $this->assertEquals($data, $result);
    }

    public function testUpdate()
    {
        $table = 'sample';
        $data = array('name'=>'useruser','create_datetime'=>'2011-01-01');
        $where = 'id = 1';
        $this->update($table, $data, $where);

        $ext = array('id'=>1);
        $ext = array_merge($ext, $data);

        $stmt = $this->query("select * from sample where id = 1");
        $result = $stmt->fetch();
        $this->assertEquals($ext, $result);
    }

}