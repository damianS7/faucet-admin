<?php
define('CRON_PASSWORD', 'qqsdx34d');
$pass = $argv[1];

if(empty($pass) || $pass != CRON_PASSWORD) {
	file_put_contents('log.txt', "Failed access to cronjob ... Wrong password -> {$pass} \n", FILE_APPEND);	
	exit;
}

require 'balancebox-lib.php';
$log = "\nRunning cron at " . date('Y-m-d H:i:s') . "\n";

foreach (get_config()["faucets"] as $k => $v) {
	if ( $v["auto"] ) {
		$faucet_balance = faucetbox_get_balance( $v["api"] ) ;

		if ( !empty( $faucet_balance ) && $faucet_balance != null ) {
			if ( $faucet_balance < $v["min_balance"] ) {
				if ( coinbase_send( $v["to"], $v["reload_amount"], $k ) ) {
					$log .= "Reloaded {$k} with {$v['reload_amount']} \n";	
				} else {
					$log .= "Error sending {$v['reload_amount']} to {$k}, invalid address/amount \n";
				}
			} else {
				$log .= "Faucet {$k} will not be recharged as balance did not hit the limit yet.\n";	
			}
		}
	}
}
//echo $log;
file_put_contents('log.txt', $log, FILE_APPEND);