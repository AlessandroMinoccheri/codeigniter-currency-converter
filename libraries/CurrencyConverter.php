<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
Alessandro Minoccheri
V 1.0.0
09-04-2014

https://github.com/AlessandroMinoccheri

*/

class CurrencyConverter{
    public function __construct()
    {
       
    }   

    public function convert($from_Currency,$to_Currency,$amount, $save_into_db = 1, $hour_difference = 1) {
        if($from_Currency != $to_Currency){
            $CI =& get_instance();
            $rate = 0;

            if ($from_Currency=="PDS")
                $from_Currency = "GBP";
            
            if($save_into_db == 1){
                $this->checkIfExistTable();

                $CI->db->select('*');
                $CI->db->from('currency_converter');
                $CI->db->where('from', $from_Currency);
                $CI->db->where('to', $to_Currency);
                $query = $CI->db->get();
                $find = 0;

                foreach ($query->result() as $row){
                    $find = 1;
                    $last_updated = $row->modified;
                    $now = date('Y-m-d H:i:s');
                    $d_start = new DateTime($now);
                    $d_end = new DateTime($last_updated);
                    $diff = $d_start->diff($d_end);

                    if(((int)$diff->y >= 1) || ((int)$diff->m >= 1) || ((int)$diff->d >= 1) || ((int)$diff->h >= $hour_difference) || ((double)$row->rates == 0)){
                        $rate = $this->getRates($from_Currency, $to_Currency);

                        $data = array(
                            'from'  => $from_Currency,
                            'to' => $to_Currency,
                            'rates' => $rate,
                            'modified' => date('Y-m-d H:i:s'),
                         );
                         $CI->db->where('id', $row->id);
                         $CI->db->update('currency_converter',$data);     
                    }
                    else{
                        $rate = $row->rates;
                    }
                }

                if($find == 0){
                    $rate = $this->getRates($from_Currency, $to_Currency);

                    $data = array(
                        'from'  => $from_Currency,
                        'to' => $to_Currency,
                        'rates' => $rate,
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    );
                    $CI->db->insert('currency_converter',$data); 
                }
                $value = (double)$rate*(double)$amount;
                return number_format((double)$value, 2, '.', '');
            }
            else{
                $rate = $this->getRates($from_Currency, $to_Currency);
                $value = (double)$rate*(double)$amount;
                return number_format((double)$value, 2, '.', '');
            }
        }
        else{
            return number_format((double)$amount, 2, '.', '');
        }
    }

    private function getRates($from_Currency, $to_Currency){
        $url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from_Currency . $to_Currency .'=X';
        $handle = @fopen($url, 'r');
         
        if ($handle) {
            $result = fgets($handle, 4096);
            fclose($handle);
        }

        if(isset($result)){
            $allData = explode(',',$result); /* Get all the contents to an array */
            $rate = $allData[1];
        }
        else{
            $rate = 0;
        }
        return($rate);
    }

    private function checkIfExistTable(){
        $CI =& get_instance();

        if ($CI->db->table_exists('currency_converter') ){
            return(true);
        }
        else{
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
            $CI->dbforge->create_table('currency_converter',TRUE);
        } 
    }
}

?>