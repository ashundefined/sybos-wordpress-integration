<?php

/**
 * Business logic for Sybos API
 *
 * Implements a class for interfacing with the Sybos API.
 * Documentation for the API can be found in Sybos' help menu.
 *
 * @package    Syin
 * @subpackage Syin/public
 * @author     Sebastian <Stampfel>
 * @since      1.0.0
 */
class Sybos_API {
	protected $apiKey;
	protected $baseURL;
	protected $tokenString;

	/**
	 * Sybos_API constructor.
	 *
	 * Sets up API Key for internal use, as well as base URL and a string with the API key to
	 * append to requests.
	 *
	 * @param $apiKey Sybos API key. Should be fetched from setting using get_option(),
	 *                can also be provided statically.
	 *
	 * @since 1.0.0
	 */
	function __construct( $apiKey, $apiBaseUrl ) {
		$this->apiKey      = $apiKey;
		$this->baseURL     = $apiBaseUrl;
		$this->tokenString = "token=" . $this->apiKey;
	}

	/**
	 * Fetch all published operations from sybos API.
	 *
	 * A note on $maxOperations: As of February 2021, Sybos does not
	 * offer the ability to only fetch operations for a specific fire department.
	 * Instead, the /Einsatz.php endpoint returns *all* operations available in the
	 * sysbos instance queried.
	 * In order to get operations for a specific department, the following (very ugly, hacky and sh***y) workaround
	 * is proposed by the author of this plugin:
	 * - Get a *huge* amount of operations from API using the "a=" attribute
	 *   (NOTE: A good wordpress caching plugin reduces load on the sybos-api, you
	 *    should only perform this *huge* query once a while. Refer to the manual of
	 *    your caching plugin on how to set something like that up!)
	 * - Return those operations
	 * - On render, disregard operations where the key "Abteilung" does not match the
	 *   name of your prefered department.
	 *
	 * @return mixed @see retrieveOperationsFromAPI
	 * @since 1.0.0
	 */
	function fetchOperations() {
		$maxOperations = "99999";
        $requestURL = $this->baseURL . "Einsatz.php?" . $this->tokenString . "&json=true&a=" . $maxOperations;
        return $this->retrieveOperationsFromAPI( $requestURL );
    }

	/**
	 * Fetch all published operations for a specified year from sybos API.
	 *
	 * A note on $maxOperations: As of February 2021, Sybos does not
	 * offer the ability to only fetch operations for a specific fire department.
	 * Instead, the /Einsatz.php endpoint returns *all* operations available in the
	 * sysbos instance queried.
	 * In order to get operations for a specific department, the following (very ugly, hacky and sh***y) workaround
	 * is proposed by the author of this plugin:
	 * - Get a *huge* amount of operations from API using the "a=" attribute
	 *   (NOTE: A good wordpress caching plugin reduces load on the sybos-api, you
	 *    should only perform this *huge* query once a while. Refer to the manual of
	 *    your caching plugin on how to set something like that up!)
	 * - Return those operations
	 * - On render, disregard operations where the key "Abteilung" does not match the
	 *   name of your prefered department.
	 *
	 * @param $year Year for which to fetch operations from API
	 *
	 * @return mixed @see retrieveOperationsFromAPI
	 * @since 1.0.0
	 */
	function fetchOperationsForYear( $year ) {
		$maxOperations = "9999";
		$startDate  = $year . "0101";
		$endDate    = $year . "1231";
		$requestURL = $this->baseURL . "Einsatz.php?" . $this->tokenString . "&json=true&a=". $maxOperations ."&von=" . $startDate . "&bis=" . $endDate;

		return $this->retrieveOperationsFromAPI( $requestURL );
	}

	/**
	 * Performs the actual request against Sybos API according to request url param.
	 * On success, returns an array of operations. This functin is supposed to be called
	 * exclusively from a helper function providing the url and returning the return value
	 * of this function.
	 *
	 * @param $url Request URL to perform a get request against
	 *
	 * @return mixed the operations encoded in appropriate PHP type. Values true, false and null (case-insensitive) are
	 *               returned as TRUE, FALSE and NULL respectively. NULL is returned if the json (from api) cannot be
	 *               decoded or if the encoded data is deeper than the recursion limit.
	 * @since 1.0.0
	 */
	function retrieveOperationsFromAPI( $url ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


		$response = curl_exec( $ch );
		$retcode  = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		$info     = curl_getinfo( $ch );
		curl_close( $ch );

		return json_decode( $response )->item;
	}

}