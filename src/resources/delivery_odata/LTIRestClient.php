<?php

/**
 * @file
 * Copyright (C) 2016 Questionmark Computing Limited.
 *
 * License GNU GPL version 2 or later (see LICENSE.TXT file)
 * There is NO WARRANTY, to the extent permitted by law.
 */

require_once(  dirname(__FILE__) . '/../php-restclient/restclient.php');

class LTIRestClient {

  private $api = NULL;

  /**
   * RestClient constructor.
   */
  public function __construct($customer_id, $url, $qmwise_username, $qmwise_password) {
    $this->api = new RestClient([
      'base_url' => $url,
      'format' => 'json',
      'username' => $qmwise_username,
      'password' => $qmwise_password
    ]);
  }

  public function callApi($serviceName, $endpoint, $method) {
    $result = $this->api->get($endpoint);
  }

}
