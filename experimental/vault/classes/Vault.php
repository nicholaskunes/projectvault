<?php

require_once("../../vault/config/db.php");

require '../../vendor/autoload.php';

Vault::createWallet();


class Vault
{
	$api_code = "fedcfc00-371d-4b84-b055-7052a4fb5cea";

    public $db_connection = null;
    
    public function createWallet()
    {
        $db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $db_connection->set_charset("utf8");
        if (!$db_connection->connect_errno) {
            
            $email = $db_connection->real_escape_string('kunes.nick0@gmail.com');
            
            $sql     = "SELECT wallet
                        FROM users
                        WHERE email = '" . $email . "';";
            $wallet_check = $db_connection->query($sql);
            
            if ($wallet_check->num_rows == 1) {
                $result_row = $wallet_check->fetch_object();
				if($result_row->wallet == '') {
					$Blockchain = new \Blockchain\Blockchain($api_code);
					$Blockchain->setServiceUrl("http://54.39.21.63");
					$wallet = $Blockchain->Create->create("thgf01");
					echo $wallet;
				}
            }
        }
    }
}

