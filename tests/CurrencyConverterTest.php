<?php

class CurrencyConverterTest extends PHPUnit_Framework_TestCase
{
    private $currencyConverter;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $CI =& get_instance();
        $CI->load->library('currencyConverter');
        $CI->load->database();
    }

    public function testConvertWithoutSaveIntoDatabase()
    {
        $saveIntoDatabase = false;
        $hourDifference = 1;

        $this->currencyConverter = new CurrencyConverter();
        $result = $this->currencyConverter->convert('GBP', 'EUR', '2000.00', $saveIntoDatabase, $hourDifference);

        $this->assertNotEmpty($result);

        $dbTable = $this->currencyConverter->getCurrencyTable();
        
        $CI =& get_instance();

        $this->assertFalse($CI->db->table_exists($dbTable));
    }

    public function testConvertPds()
    {
        $saveIntoDatabase = false;
        $hourDifference = 1;

        $this->currencyConverter = new CurrencyConverter();
        $result = $this->currencyConverter->convert('PDS', 'EUR', '2000.00', $saveIntoDatabase, $hourDifference);

        $this->assertNotEmpty($result);
    }

    public function testConvertSameCurrency()
    {
        $saveIntoDatabase = false;
        $hourDifference = 1;

        $this->currencyConverter = new CurrencyConverter();
        $result = $this->currencyConverter->convert('EUR', 'EUR', '2000.00', $saveIntoDatabase, $hourDifference);

        $this->assertEquals($result, '2000.00');
    }

    public function testConvertSaveIntoDatabase()
    {
        $saveIntoDatabase = true;
        $hourDifference = 1;

        $this->currencyConverter = new CurrencyConverter();
        $result = $this->currencyConverter->convert('GBP', 'EUR', '2000.00', $saveIntoDatabase, $hourDifference);

        $this->assertNotEmpty($result);

        $dbTable = $this->currencyConverter->getCurrencyTable();

        $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from($dbTable);
        $query = $CI->db->get();

        $this->assertCount(1, $query->result());
    }

    public function testNeedToUpdateDatabase()
    {
        $saveIntoDatabase = true;
        $hourDifference = -1;

        $this->currencyConverter = new CurrencyConverter();
        $result = $this->currencyConverter->convert('GBP', 'EUR', '2000.00', $saveIntoDatabase, $hourDifference);

        $this->assertNotEmpty($result);

        $dbTable = $this->currencyConverter->getCurrencyTable();
        
        $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from($dbTable);
        $query = $CI->db->get();

        $this->assertCount(1, $query->result());
    }

    public function testNotNeedToUpdateDatabase()
    {
        $saveIntoDatabase = true;
        $hourDifference = 100;

        $this->currencyConverter = new CurrencyConverter();
        $result = $this->currencyConverter->convert('GBP', 'EUR', '2000.00', $saveIntoDatabase, $hourDifference);

        $this->assertNotEmpty($result);

        $dbTable = $this->currencyConverter->getCurrencyTable();
        
        $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from($dbTable);
        $query = $CI->db->get();

        $this->assertCount(1, $query->result());
    }

    public function testNotExistingCurrency()
    {
        $saveIntoDatabase = true;
        $hourDifference = 100;

        $this->currencyConverter = new CurrencyConverter();
        $result = $this->currencyConverter->convert('NOTEXISTS', 'EUR', '2000.00', $saveIntoDatabase, $hourDifference);

        $this->assertEquals(0, $result);
    }

    /**
     * @expectedException Exception
     */
    public function testThrowExceptionIfApiKeyIsNotSet()
    {
        $saveIntoDatabase = false;
        $hourDifference = 1;

        $this->currencyConverter = new CurrencyConverter();
        $this->currencyConverter->setApiKey(null);
        $this->currencyConverter->convert('EUR', 'EUR', '2000.00', $saveIntoDatabase, $hourDifference);
    }
}
