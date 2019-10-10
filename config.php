<?php
$faucets["zonebitcoin.com"] = array(
	"api" => "1ap58T2IENJpK1kec1nkAmmCWtAXq", // FaucetBox ApiKey
	"currency" => "BTC", 
	"to" => "13XAC14L3DuWQTusg3ZAQ1y7d4jJUAMimK", // FaucetBox deposit address 
	"auto" => true, // Set true to enable auto recharge
	"min_balance" => "0.001", // When balance is lower than "min_balance", the script will send "reload_amount"
	"reload_amount" => 0.01 // Amount to recharge
);

$coinbase = array(
	"api_key" => "123456", 
	"api_secret" => "123456"
);