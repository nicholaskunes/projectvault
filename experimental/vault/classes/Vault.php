<?php

echo require_once('../../../src/Blockchain.php');

$api_code = "fedcfc00-371d-4b84-b055-7052a4fb5cea";


echo 'start';

$vault = new Vault()->createWallet();

echo 'one';

class Vault
{

    private $db_connection = null;
    
    public function createWallet()
    {
		echo 'two';
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $this->db_connection->set_charset("utf8");
        if (!$this->db_connection->connect_errno) {
            
            $email = $this->db_connection->real_escape_string($_POST['email']);
            
            $sql     = "SELECT wallet
                        FROM users
                        WHERE email = '" . $email . "';";
            $wallet_check = $this->db_connection->query($sql);
            
            if ($wallet_check->num_rows == 1) {
                $result_row = $wallet_check->fetch_object();
				if($result_row->wallet == '') {
					$Blockchain = new \Blockchain\Blockchain($api_code);
					$Blockchain->setServiceUrl("https://surrealarcher.com");
					$wallet = $Blockchain->Create->create("thgf01");
					echo $wallet;
				}
            }
        }
    }
}

