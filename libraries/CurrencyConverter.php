<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
Alessandro Minoccheri
V 1.1.0
09-04-2014

https://github.com/AlessandroMinoccheri

*/

class CurrencyConverter{
        
    private $dbTable;
    
    public function __construct()
    {
       $CI =& get_instance();
       $CI->config->load('currency_converter',TRUE);
       $this->dbTable=$CI->config->item('currency_converter_db_table','currency_converter');
    }   

    public function convert($fromCurrency, $toCurrency, $amount, $saveIntoDb = 1, $hourDifference = 1) {
        if($fromCurrency != $toCurrency){
            $CI =& get_instance();
            $rate = 0;

            if ($fromCurrency=="PDS") {
                $fromCurrency = "GBP";
            }
            
            if ($saveIntoDb == 1) {
                $this->checkIfExistTable();

                $CI->db->select('*');
                $CI->db->from($this->dbTable);
                $CI->db->where('from', $fromCurrency);
                $CI->db->where('to', $toCurrency);
                $query = $CI->db->get();
                $find = 0;

                foreach ($query->result() as $row){
                    $find = 1;
                    $lastUpdated = $row->modified;
                    $now = date('Y-m-d H:i:s');
                    $dStart = new DateTime($now);
                    $dEnd = new DateTime($lastUpdated);
                    $diff = $dStart->diff($dEnd);

                    if ($this->needToUpdateDatabase($diff, $hourDifference, $row)) {
                        $rate = $this->getRates($fromCurrency, $toCurrency);

                        $data = array(
                            'from'  => $fromCurrency,
                            'to' => $toCurrency,
                            'rates' => $rate,
                            'modified' => date('Y-m-d H:i:s'),
                         );

                         $CI->db->where('id', $row->id);
                         $CI->db->update($this->dbTable,$data);     
                    } else{
                        $rate = $row->rates;
                    }
                }

                if($find == 0){
                    $rate = $this->getRates($fromCurrency, $toCurrency);

                    $data = array(
                        'from'  => $fromCurrency,
                        'to' => $toCurrency,
                        'rates' => $rate,
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    );

                    $CI->db->insert($this->dbTable,$data); 
                }

                $value = (double)$rate * (double)$amount;

                return number_format((double)$value, 2, '.', '');
            }

            $rate = $this->getRates($fromCurrency, $toCurrency);
            $value = (double)$rate * (double)$amount;

            return number_format((double)$value, 2, '.', '');
        }

        return number_format((double)$amount, 2, '.', '');
    }

    private function needToUpdateDatabase($diff, $hourDifference, $row) {
        if (
            ((int)$diff->y >= 1) ||
            ((int)$diff->m >= 1) ||
            ((int)$diff->d >= 1) ||
            ((int)$diff->h >= $hourDifference) ||
            ((double)$row->rates == 0)
        ) {
            return true;
        }

        return false;
    }

    private function getRates($fromCurrency, $toCurrency){
        $url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $fromCurrency . $toCurrency .'=X';
        $handle = @fopen($url, 'r');
         
        if ($handle) {
            $result = fgets($handle, 4096);
            fclose($handle);
        }

        if (isset($result)) {
            $allData = explode(',', $result);
            $rate = $allData[1];
        } else {
            $rate = 0;
        }
        return($rate);
    }

    private function checkIfExistTable(){
        $CI =& get_instance();

        if ($CI->db->table_exists($this->dbTable)) {
            return(true);
        } else {
            $CI->load->dbforge();
            $CI->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'from' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '5',
                    'null' => FALSE
                ),
                'to' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '5',
                    'null' => FALSE
                ),
                'rates' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '10',
                    'null' => FALSE
                ),
                'created' => array(
                    'type' => 'DATETIME'
                ),
                'modified' => array(
                    'type' => 'DATETIME'
                )
            ));

            $CI->dbforge->add_key('id', TRUE);
            $CI->dbforge->create_table($this->dbTable,TRUE);
        } 
    }
}
