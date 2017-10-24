<?php
/**
 * Copyright (C) 2014 Questionmark Computing Limited.
 * License GNU GPL version 2 or later (see LICENSE.TXT file)
 * There is NO WARRANTY, to the extent permitted by law.
 */

/**
 * @file
 * Signature computing by using RSA encryption.
 *
 * @author Questionmark
 */

class SigningTask {

  /**
   * Constructs a Signing Task object
   *
   * @param \Questionmark\qm_core\Wrappers\DrupalWrapperInterface $drupalWrapper
   */
  public function __construct() {
  }

  /**
   * Computes the signature for a given list of parameters
   *
   * @param array $parameters List of parameters
   * @param string $rsaKeyContainerName Key container name
   * @return string Signature
   * @throws \InvalidArgumentException
   */
  public function computeSignature(array $parameters, $rsaKeyContainerName) {
    if (empty($parameters)) {
      throw new \InvalidArgumentException('"parameters" needs to be initialized with a valid string array.');
    }

    ksort($parameters);

    $dataToSign = '';

    foreach ($parameters as $name => $parameter) {
      $dataToSign .= urlencode($name) . '=' . urlencode($parameter) . '&';
    }

    return $this->sign($rsaKeyContainerName, $dataToSign);
  }

  /**
   * Computes the signature for a given list of parameters for Delivery odata.
   *
   * @param array $parameters List of parameters
   * @param string $rsaKeyContainerName Key container name
   * @return string Signature
   * @throws \InvalidArgumentException
   */
  public function computeDeliveryoDataSignature(array $parameters, $rsaKeyContainerName) {
    if (empty($parameters)) {
      throw new \InvalidArgumentException('"parameters" needs to be initialized with a valid string array.');
    }

    ksort($parameters);

    $dataToSign = '';

    foreach ($parameters as $name => $parameter) {
      $dataToSign .= urlencode($name) . '=' . urlencode($parameter) . '&';
    }

    $dataToSign = $this->drupalWrapper->drupal_strtolower(rtrim($dataToSign, '&'));
    $dataToSign = str_replace(array('%28', '%29'), array('(', ')'), $dataToSign);

    return $this->sign($rsaKeyContainerName, $dataToSign);
  }

  /**
   * Validate the request
   *
   * @param string $requestUrl Request URL
   * @param string $requestBody Request body
   * @param string $timestamp Timestamp
   * @param string $signature Signature
   * @param string $publicKey Public key
   */
  public function validateRequest($requestUrl, $requestBody, $timestamp, $signature, $publicKey) {
    $portalServiceAuthentication = new \COM('QM.Portal.COM.PortalServiceAuthentication', NULL, CP_UTF8);
    return $portalServiceAuthentication->ValidateRequest($requestUrl, $requestBody, $timestamp, $signature, $publicKey);
  }

  /**
   * Computes the signature for a given string by using a .NET component
   *
   * @param string $keyContainerName RSA key container name
   * @param string $dataToSign Data to sign
   * @return string Signature
   */
  protected function sign($keyContainerName, $dataToSign) {
    $crypto = new \COM('QM.Portal.COM.Rsa');
    return $crypto->Sign($keyContainerName, $dataToSign);
  }
}
