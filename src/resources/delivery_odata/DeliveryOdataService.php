<?php
/**
 * Copyright (C) 2016 Questionmark Computing Limited.
 * License GNU GPL version 2 or later (see LICENSE.TXT file)
 * There is NO WARRANTY, to the extent permitted by law.
 */

require_once "LTIRestClient.php";

/**
 * Class DeliveryOdataService
 *
 * @package Questionmark\qm_services\Api
 */
class DeliveryOdataService  {

  private $ServiceEndpoint = '';
  private $ServiceName = 'Delivery Odata Service';
  private $RestClient = NULL;

  public function __construct($customer_id, $url, $qmwise_username, $qmwise_password) {
    $this->RestClient = new LTIRestClient($customer_id, $url, $qmwise_username, $qmwise_password);
    $this->ServiceEndpoint = $url;
  }

  // Assessments FEED
  function GetAssessment($id = null) {
    $endpoint = 'Assessments';
    if (isset($id)) {
      $endpoint .= "?\$filter=" . urlencode("ID eq " . $id . "L");
    }
    $method = "GET";
    return $this->RestClient->callApi($endpoint, $method);
  }

  // Attempts FEED
  function GetAttempt($externalAttemptID, $assessmentID, $participantID) {
    $endpoint = "Attempts";
    $params = array(
      "EntityType" => "Attempt",
      "ExternalAttemptID" => $externalAttemptID,
      "AssessmentID" => $assessmentID,
      "ParticipantID" => $participantID,
      "LockStatus" => false,
      "LockRequired" => false
    );
    $method = "POST";
    $headers = array(
      "Content-Type" => "application/json"
    );
    return $this->RestClient->callApi($endpoint, $method, $params, $headers);
  }

}

