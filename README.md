ZendDbTestCase
==============
This library is Database TestCase using ZendFramework2 for PHPUnit


Installation via composer
------------

<pre>
$ php composer.phar require "reioto/zend-db-testcase:*"
</pre>


Usage
-----

<pre>

class fooTest extends \Zend\Db\TestCase 
{
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet('sample.xml');
    }

    /**
     * @return array Zend\Db\Adapter\Adapter parameters
     */
    protected function getDbConfig()
    {
        return array('username'=>'dbuser',
                     'password'=> 'dbpassword',
                     'dbname'=>'dbname',
                     'driver'=>'Pdo_Mysql'
                     );
    }

    public function testSample()
    {
        $this->assertEquals(true, true);
    }
}

</pre>