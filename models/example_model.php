<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
        $this->CurrencyConverter = new CurrencyConverter();
    }

    public function convert(){
        $amount = '2100,00';
        $result = $this->CurrencyConverter->convert('GBP', 'EUR', $amount, 0, 1);

        return($result);
    }
}
?>
