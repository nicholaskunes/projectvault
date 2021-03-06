<?php

require_once(dirname(__FILE__) .  '/../../vendor/autoload.php');
require_once(dirname(__FILE__) . "/../config/db.php");


session_start();


class WalletResponse
{
    public $guid; // string
    public $address; // string
    public $label; // string
}

if (isset($_POST["address"])) {
	$db_connection1 = null;
    $db_connection1 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $db_connection1->set_charset("utf8");
    if (!$db_connection1->connect_errno) {
        
        $email = $_SESSION["email"];
        
        $sql          = "SELECT guid, wpasswdhash
                        FROM users
                        WHERE email = '" . $email . "';";
        $wallet_check = $db_connection1->query($sql);
        
        if ($wallet_check->num_rows == 1 && !isset($_SESSION["vaultaddress"])) {
            $result_row = $wallet_check->fetch_object();
            $Blockchain = new \Blockchain\Blockchain("fedcfc00-371d-4b84-b055-7052a4fb5cea");
            $Blockchain->setServiceUrl("http://localhost:3030");
			$Blockchain->Wallet->credentials($result_row->guid, $result_row->wpasswdhash);
            $address = $Blockchain->Wallet->getNewAddress($label=null);
			
			$sql = "UPDATE users SET wallet='" . $address->address . "' WHERE email='" . $email . "'";
            $query_new_user_insert = $db_connection1->query($sql);
                
            $_SESSION['vaultaddress'] = $address->address;
        } elseif ($wallet_check->num_rows == 1) {
			echo $_SESSION['vaultaddress'];
		}
    }
}

if (isset($_POST["dashboarddata"])) {
	$db_connection1 = null;
    $db_connection1 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $db_connection1->set_charset("utf8");
    if (!$db_connection1->connect_errno) {
        
        $email = $_SESSION["email"];
        
        $sql          = "SELECT wallet, guid, wpasswdhash, level, experience, boughttoday
                        FROM users
                        WHERE email = '" . $email . "';";
        $wallet_check = $db_connection1->query($sql);
        
        if ($wallet_check->num_rows == 1) {
            $result_row = $wallet_check->fetch_object();
            if ($result_row->wallet != '') {
                $Blockchain = new \Blockchain\Blockchain("fedcfc00-371d-4b84-b055-7052a4fb5cea");
                $Blockchain->setServiceUrl("http://localhost:3030");
				$Blockchain->Wallet->credentials($result_row->guid, $result_row->wpasswdhash);
                $balance = $Blockchain->Wallet->getBalance();
				
				$sql = "SELECT `1`, `2`, `3`, `4`, `5`, `6`, `7`, `8`, `9`, `10`
                        FROM expinfo";
				$wallet_check = $db_connection1->query($sql); 
				$expdata = $wallet_check->fetch_object();
				
				$sql = "SELECT `1`, `2`, `3`, `4`, `5`, `6`, `7`, `8`, `9`, `10`
                        FROM levelinfo";
				$wallet_check = $db_connection1->query($sql); 
				$feeleveldata = $wallet_check->fetch_object();
				
				$btcprice = $Blockchain->Rates->get()['USD']->last;
				$vaultaddr =  $_SESSION['vaultaddress'];
        
				if ($wallet_check->num_rows == 1) {
					echo json_encode(array($result_row->level, $result_row->experience, $balance, bcmul($balance, $Blockchain->Rates->get()['USD']->last, 10), $expdata, $result_row->boughttoday, $btcprice, $vaultaddr, $feeleveldata));
				}               
            }
        }
    }
}

if (isset($_POST["level"])) {
	$db_connection1 = null;
    $db_connection1 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $db_connection1->set_charset("utf8");
    if (!$db_connection1->connect_errno) {
        
        $email = $_SESSION["email"];
        
        $sql          = "SELECT level, experience
                        FROM users
                        WHERE email = '" . $email . "';";
        $wallet_check = $db_connection1->query($sql);
        
        if ($wallet_check->num_rows == 1) {
			$result_row = $wallet_check->fetch_object();
            echo json_encode(array($result_row->level, $result_row->experience));
        }
    }
}

if (isset($_POST["btcprice"])) {
	$Blockchain = new \Blockchain\Blockchain("fedcfc00-371d-4b84-b055-7052a4fb5cea");
    $Blockchain->setServiceUrl("http://localhost:3030");
	echo $Blockchain->Rates->get()['USD']->last;
}

class Vault
{
    public $db_connection = null;
    
    public function createWallet($email, $passwd)
    {
        $db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        $db_connection->set_charset("utf8");
        if (!$db_connection->connect_errno) {
            
            $email = $db_connection->real_escape_string($email);
            
            $sql          = "SELECT wallet
                        FROM users
                        WHERE email = '" . $email . "';";
            $wallet_check = $db_connection->query($sql);
            
            if ($wallet_check->num_rows == 1) {
                $result_row = $wallet_check->fetch_object();
                if ($result_row->wallet == '') {
                    $Blockchain = new \Blockchain\Blockchain("fedcfc00-371d-4b84-b055-7052a4fb5cea");
                    $Blockchain->setServiceUrl("http://localhost:3030");
                    $wallet = $Blockchain->Create->create($passwd);
                    
                    $sql                   = "UPDATE users SET wallet='" . $wallet->address . "', guid='" . $wallet->guid . "' WHERE email='" . $email . "'";
                    $query_new_user_insert = $db_connection->query($sql);
                }
            }
        }
    }
}