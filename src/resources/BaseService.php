<?php

/**
 * @file
 * Copyright (C) 2016 Questionmark Computing Limited.
 *
 * License GNU GPL version 2 or later (see LICENSE.TXT file)
 * There is NO WARRANTY, to the extent permitted by law.
 */

/**
 * Class BaseService for making generic service calls.
 */
class BaseService {

  public $ServiceBaseUrl = '';

  public $AreaName = '';

  public $ServiceUsername = '';

  public $ServicePassword = '';

  public $ServiceBasicAuthString = '';

  public $LogTransactions = TRUE;

  public $LastHttpStatus = '';

  public $LastErrorCode = '';

  public $LastErrorMessage = '';

  /**
   * BaseService constructor.
   */
  public function __construct($customer_id, $url, $qmwise_username, $qmwise_password) {
    $this->ServiceBaseUrl = $url;
    $this->AreaName = $customer_id;
    $this->ServiceUsername = $qmwise_username;
    $this->ServicePassword = $qmwise_password;
  }

  /**
   * Signs Parameters using RSA Keys.
   *
   *   Contains any parameters needed for service call.
   *
   * @return array|null
   *   Returns either null or an array containing the signed parameters
   *   including timestamp and signature.
   */
  private function signParams($targetUrl, $params) {
    $signedRequestDataGenerator = CoreFactory::getSignedRequestDataGenerator();

    $params = $signedRequestDataGenerator->generateDeliveryoDataServiceParameters($targetUrl, $params);

    return $params;
  }

  /**
   * Generates cURL compatible header with timestamp and signature.
   *
   * @param array|null $params
   *   Contains any parameters needed for service call including timestamp and
   *    signature.
   *
   * @return object
   *   Returns either null or an array containing the signed httpHeader for cURL
   *   and the list of parameters in a json encoded string.
   */
  private function setSignedHttpHeader($params = NULL) {
    $paramString = json_encode($params);
    $obj = new \stdClass();
    $obj->paramString = $paramString;
    $obj->httpHeader = [
      'Content-Type: application/json',
      'x-qm-timestamp: ' . $params['timestamp'],
      'x-qm-signature: ' . $params['signature'],
    ];
    return $obj;
  }

  /**
   * Generates cURL compatible header without timestamp and signature.
   *
   * @param array|null $params
   *   Contains any parameters needed for service call.
   *
   * @return object|null
   *   Returns either null or an array containing the httpHeader for cURL
   *   and the list of parameters in a json encoded string.
   */
  private function setHttpHeader($params = NULL) {
    $obj = new \stdClass();
    if (isset($params)) {
      $paramString = json_encode($params);
      $obj->paramString = $paramString;
      $obj->httpHeader = [
        'Authorization: Basic ' . $this->ServiceBasicAuthString,
        'Content-Type: application/json',
      ];
    }
    else {
      $obj->paramString = NULL;
      $obj->httpHeader = [
        'Authorization: Basic ' . $this->ServiceBasicAuthString,
        'Content-Type: application/json',
      ];
    }
    return $obj;
  }

  /**
   * Generic call to a REST based API.
   *
   * @param string $serviceName
   *   String representation of the name service being called.
   * @param string $apiMethodName
   *   String representation of the name of the method being invoked.
   * @param string $endpoint
   *   String representation of the endpoint's address.
   * @param string $method
   *   String repsentation of the http verb GET or POST.
   * @param array|null $params
   *   Contains any parameters necessary for the service call.
   *
   * @return bool
   *   TRUE|FALSE
   */
  protected function callApi($serviceName, $apiMethodName, $endpoint, $method, $params = NULL) {
    $scriptStartTime = microtime(TRUE);
    $options = NULL;
    $this->LastHttpStatus = '';
    $this->LastErrorCode = '';
    $this->LastErrorMessage = '';

    if ($ch = curl_init($endpoint)) {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

      if ($serviceName == 'Delivery Odata Service') {
        $signedParams = $this->signParams($endpoint, $params);

        $httpHeaderObj = $this->setSignedHttpHeader($signedParams);
      }
      else {
        $httpHeaderObj = $this->setHttpHeader($params);
      }

      if (isset($params)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $requestParams = $this->getWatchdogString($httpHeaderObj->paramString);
      }
      else {
        $requestParams = "";
      }

      curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaderObj->httpHeader);

      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

      $result = curl_exec($ch);

      $scriptEndTime = microtime(TRUE);
      $scriptTime = number_format(($scriptEndTime - $scriptStartTime), 2);

      $info = curl_getinfo($ch);
      $this->LastHttpStatus = $info["http_code"];

      if (!curl_errno($ch)) {
        if ($this->LogTransactions) {
          $info = curl_getinfo($ch);
          if ($this->isJson($result)) {
            $responseParams = $this->getWatchdogString(json_decode($result));
          }
          else {
            $responseParams = $this->getWatchdogString($result);
          }

          watchdog(
            'QM Services',
            '@apiMethodName (@scriptTime seconds)<br/>Endpoint: @endpoint</br>HTTP Status: @httpStatus<br/>REQUEST:<pre>@request</pre><br/>RESPONSE:<pre>@responseParams</pre><br/>Curl Info:<pre>@curl_info</pre>',
            array(
              '@serviceName' => $serviceName,
              '@responseParams' => $responseParams,
              '@requestParams' => $requestParams,
              '@apiMethodName' => $apiMethodName,
              '@scriptTime' => $scriptTime,
              '@endpoint' => $endpoint,
              '@httpStatus' => $this->LastHttpStatus,
              '@curl_info' => print_r($info, TRUE),
            )
          );
        }

        if ($this->isJson($result)) {
          $resultIsJSON = TRUE;
          $decodedResult = json_decode($result);
        }
        else {
          $resultIsJSON = FALSE;
          $decodedResult = $result;
        }

        if ($resultIsJSON && isset($decodedResult->data)) {
          return $decodedResult->data;
        }
        else {
          if ($resultIsJSON && isset($decodedResult->value)) {
            return $decodedResult->value;
          }
          else {
            if ($resultIsJSON) {
              return json_decode($result);
            }
            else {
              return $result;
            }
          }
        }
      }
      else {
        $this->LastErrorCode = curl_errno($ch);
        $this->LastErrorMessage = curl_error($ch);

        $resultString = $this->getWatchdogString($result);

        if (isset($result)) {
          $status = $this->getWatchdogString($result);
        }
        else {
          if (isset($result->code)) {
            $status = $result->code . ': ' . $result->error;
          }
          else {
            $status = 'Unknown error';
          }
        }

        watchdog(
          'QM Services',
          '@apiMethodName (@scriptTime seconds)<br/>Endpoint: @endpoint</br>@status<br/>Curl Error: @curl_errorno - @curl_error<br/>Request Params:<pre>@request</pre>Status: <pre>@status</pre>Response:<pre>@result</pre>',
          array(
            '@serviceName' => $serviceName,
            '@apiMethodName' => $apiMethodName,
            '@scriptTime' => $scriptTime,
            '@endpoint' => $endpoint,
            '@status' => $status,
            '@curl_errorno' => curl_errno($ch),
            '@curl_error' => curl_error($ch),
            '@request' => $requestParams,
            '@result' => $resultString,
          ),
          WATCHDOG_ERROR);
        return FALSE;
      }
    }
    else {
      // Curl init failed.
      watchdog('QM Services',
        '@apiMethodName<br/>Endpoint: @endpoint</br>API call initialization failed.<br/>Request Params<pre>@request</pre>',
        array(
          '@serviceName' => $serviceName,
          '@endpoint' => $endpoint,
          '@apiMethodName' => $apiMethodName,
          '@request' => $this->getWatchdogString($params),
        ),
        WATCHDOG_ERROR);
    }
    curl_close($ch);

    return FALSE;
  }

  /**
   * Turns objects and arrays into a string ready for use in a watchdog call.
   *
   * @param array|object $obj
   *   A stdClass that will be made ready for being logged in watchdog.
   *
   * @return string
   *   String ready to be used in a call for Watchdog.
   */
  protected function getWatchdogString($obj) {
    if (is_object($obj) || is_array($obj)) {
      $messageObj = print_r($obj, TRUE);
    }
    else {
      if (!is_null($obj)) {
        $messageObj = $obj;
      }
      else {
        $messageObj = 'NULL';
      }
    }

    return $messageObj;
  }

  /**
   * Determines if a string is already in JSON format or not.
   *
   * @param string $string
   *   The string you would like to check for JSON format.
   *
   * @return bool
   *   TRUE|FALSE
   */
  protected function isJson($string) {
    return is_string($string) && is_array(json_decode($string, TRUE)) && (json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE;
  }

}
