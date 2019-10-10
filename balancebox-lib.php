<?php

require 'config.php';
require 'vendor/autoload.php';
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money;


if ( isset( $_POST["action"] ) )
	echo ajax($_POST);

function ajax($post) {
	
	switch ($post["action"]) {
		case 'fbb':
			$faucet = get_faucet( $post["faucet"], $post["currency"] );
			if ( !is_null( $faucet ) )
				return format_balance( faucetbox_get_balance( $faucet["api"], $post["currency"] ), $post["currency"] );
			break;
		default:
			return '-';
			break;
	}
}


function get_faucet($name, $currency) {
	$faucets = get_config()["faucets"];

	foreach ( $faucets as $k => $array )
		if ( $k == $name )
			if ( $array["currency"] == $currency )
				return $array;
}

function get_faucet_by_address( $address ) {
	$faucets = get_config()["faucets"];
	foreach ( $faucets as $k => $v )
		foreach ( $v as $array )
			if ( $array["address"] == $address )
				return $array;
}


function get_config() {
	global $faucets, $coinbase;
	$c["faucets"] = $faucets;
	$c["coinbase"] = $coinbase;
	return $c;
}

function coinbase_get_transactions() {
	try {
	    $cfg = get_config();
		$configuration = Configuration::apiKey( $cfg["coinbase"]["api_key"], $cfg["coinbase"]["api_secret"] );
		$client = Client::create($configuration);
		$account = $client->getAccounts()[0];
		return $client->getAccountTransactions($account);
	} catch (Exception $e) {
	    return 'Invalid API Key.';
	}
}

function coinbase_get_balance() {
	
	try {
	    $cfg = get_config();
		$configuration = Configuration::apiKey( $cfg["coinbase"]["api_key"], $cfg["coinbase"]["api_secret"] );
		$client = Client::create($configuration);
		$accounts = $client->getAccounts();
		return $accounts[0]->getBalance()->getAmount() . ' BTC';
	} catch (Exception $e) {
	    return 'Invalid API Key.';
	}
}

function coinbase_send($address, $amount, $description) {
	
	$cfg = get_config();

	if (!array_key_exists($address, $cfg["faucets"]))
		return false;

	$configuration = Configuration::apiKey( $cfg["coinbase"]["api_key"], $cfg["coinbase"]["api_secret"] );
	$client = Client::create($configuration);
	$account = $client->getAccounts()[0];

	$transaction = Transaction::send([
	    'toBitcoinAddress' => $address,
	    'amount'           => new Money($amount, CurrencyCode::BTC),
	    'description'      => $description/*,
	    'fee'              => '0.0001' // only required for transactions under BTC0.0001*/
	]);

	try {
		$client->createAccountTransaction($account, $transaction);
	} catch(Exception $e) {
		return false;
	}
	return true;
}

function faucetbox_get_balance($apikey, $currency = "BTC") {
	
	$url = "https://faucetbox.com/api/v1/balance";

	$postdata = http_build_query(
	    array(
	        "api_key" => $apikey,
	        "currency" => $currency
	    )
	);

	$opts = array(
		"http" => array(
	        "method"  => "POST",
	        "header"  => "Content-type: application/x-www-form-urlencoded",
	        "content" => $postdata
	    )
	);

	$context = stream_context_create( $opts );
   	$fp = @fopen( $url, 'rb', null, $context );

   	if(!$fp) {
   		return 'Invalid URL?';	
   	}

   	$response = stream_get_contents( $fp );
   	fclose( $fp );
   	$json = json_decode( $response, true );
	return $json["balance_bitcoin"];	
}

function format_balance($balance, $currency = "BTC") {
	if ( ($balance < 30000000 && $currency != "DOGE") || ($balance < 2500 && $currency == "DOGE") ) {
		return '<p style="color:red">'.$balance.'</p>';
	}
	return $balance;
}