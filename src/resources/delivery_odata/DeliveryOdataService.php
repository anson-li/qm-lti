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
    $this->ServiceEndpoint = $this->ServiceBaseUrl;
  }

  // RESULTS FEED
  function GetResult($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Results";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetResults($filter) {
    $endpoint = $this->ServiceEndpoint . "/Results?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // GROUPS FEED
  function GetGroup($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Groups";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetGroups($filter) {
    $endpoint = $this->ServiceEndpoint . "/Groups?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // Rubrics FEED
  function GetRubric($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Rubrics";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetRubrics($filter) {
    $endpoint = $this->ServiceEndpoint . "/Rubrics?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // Dimensions FEED
  function GetDimension($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Dimensions";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetDimensions($filter) {
    $endpoint = $this->ServiceEndpoint . "/Dimensions?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // Questions FEED
  function GetQuestion($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Questions";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetQuestions($filter) {
    $endpoint = $this->ServiceEndpoint . "/Questions?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // QuestionTranslations FEED
  function GetQuestionTranslation($id = null) {
    $endpoint = $this->ServiceEndpoint . "/QuestionTranslations";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetQuestionTranslations($filter) {
    $endpoint = $this->ServiceEndpoint . "/QuestionTranslations?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // Participants FEED
  function GetParticipant($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Participants";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetParticipants($filter) {
    $endpoint = $this->ServiceEndpoint . "/Participants?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // Assessments FEED
  function GetAssessment($id = null) {
    $endpoint = "Assessments";
    if (isset($id)) {
      $endpoint .= "/" . $id . "";
    }
    $method = "GET";
    return $this->RestClient->callApi($this->ServiceName, $endpoint, $method);
  }

  function GetAssessments($filter) {
    $endpoint = $this->ServiceEndpoint . "/Assessments?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetAllAssessments() {
    $endpoint = $this->ServiceEndpoint . "/Assessments";
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // Answers FEED
  function GetAnswer($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Answers";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetAnswers($filter) {
    $endpoint = $this->ServiceEndpoint . "/Answers?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // ScoringTasks FEED
  function GetScoringTask($id = null) {
    $endpoint = $this->ServiceEndpoint . "/ScoringTasks";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetScoringTasks($filter) {
    $endpoint = $this->ServiceEndpoint . "/ScoringTasks?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // ScoringResults FEED
  function GetScoringResult($id = null) {
    $endpoint = $this->ServiceEndpoint . "/ScoringResults";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetScoringResults($filter) {
    $endpoint = $this->ServiceEndpoint . "/ScoringResults?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // DimensionScores FEED
  function GetDimensionScore($id = null) {
    $endpoint = $this->ServiceEndpoint . "/DimensionScores";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetDimensionScores($filter) {
    $endpoint = $this->ServiceEndpoint . "/DimensionScores?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // Administrators FEED
  function GetAdministrator($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Administrators";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetAdministrators($filter) {
    $endpoint = $this->ServiceEndpoint . "/Administrators?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // AssessmentSnapshots FEED
  function GetAssessmentSnapshot($id = null) {
    $endpoint = $this->ServiceEndpoint . "/AssessmentSnapshots";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetAssessmentSnapshots($filter) {
    $endpoint = $this->ServiceEndpoint . "/AssessmentSnapshots?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // AssessmentSnapshotsData FEED
  function GetAssessmentSnapshotsData($id = null) {
    $endpoint = $this->ServiceEndpoint . "/AssessmentSnapshots";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetAssessmentSnapshotsDatas($filter) {
    $endpoint = $this->ServiceEndpoint . "/AssessmentSnapshots?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // Attempts FEED
  function GetAttempt($id = null) {
    $endpoint = $this->ServiceEndpoint . "/Attempts";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetAttempts($filter) {
    $endpoint = $this->ServiceEndpoint . "/Attempts?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  /**
   * CreateAttemptsExamLobby
   *
   * $params = new \StdClass;
   * $params->ExternalAttemptID = $bvAppointmentID;
   * $params->AssessmentID = $assessmentID;
   * $params->ParticipantID = $user->field_qms_user_participant_id['und'][0]['value'];
   * $params->LockStatus = true;
   * $params->LockRequired = true;
   * $result = $deliveryAPI->CreateExamLobby($params);
   * @return null
   */
  function CreateExamLobby($params) {
    $endpoint = $this->ServiceEndpoint . "/Attempts";
    $method = "POST";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method, $params);
  }

  function GetExamLobby($attemptID = null) {
    $endpoint = $this->ServiceEndpoint . "/Attempts";
    if (isset($attemptID)) {
      $endpoint .= "(" . $attemptID . ")";
    }
    $method = "GET";
      return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function SetProctorSystemWidgetURL($lobbyID, $params) {
    $endpoint = $this->ServiceEndpoint . "/Attempts(" . $lobbyID . ")";
    $method = "PATCH";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method, $params);
  }

  /**
   * Get the attempt list object by ID.
   *
   * @param int $id
   *    The ID of the attempt list
   *
   * @return object
   *    The attempt list object
   */
  function GetAttemptList($id) {
    $endpoint = $this->ServiceEndpoint . "/AttemptLists(" . $id . ")";
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  /**
   * Gets a list of attempt list objects using the provided filter.
   *
   * @param string $filter
   *    Properly formatted OData filter string, excluding '?$filter='.
   * @param bool $expandAttempts
   *
   * @return array
   *    An array of attempt list objects matching the filter
   */
  function GetAttemptLists($filter, $expandAttempts = FALSE) {
    $endpoint = $this->ServiceEndpoint . "/AttemptLists?\$filter=".urlencode($filter);
    if ($expandAttempts) $endpoint .= '&$expand=Attempts';
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  /**
   * Creates an attempt list.
   *
   * @param StdClass
   *    ExternalAttemptListID = external attempt ID (i.e. exam event ID)
   *
   * @return object
   *    Newly created attempt list object
   */
  function CreateAttemptList($params) {
    $endpoint = $this->ServiceEndpoint . "/AttemptLists";
    $method = "POST";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method, $params);
  }

  // AnswerUploads FEED
  function GetAnswerUpload($id = null) {
    $endpoint = $this->ServiceEndpoint . "/AnswerUploads";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetAnswerUploads($filter) {
    $endpoint = $this->ServiceEndpoint . "/AnswerUploads?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  // PrintBatches FEED
  function GetPrintBatch($id = null) {
    $endpoint = $this->ServiceEndpoint . "/PrintBatches";
    if (isset($id)) {
      $endpoint .= "(" . $id . ")";
    }
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

  function GetPrintBatches($filter) {
    $endpoint = $this->ServiceEndpoint . "/PrintBatches?\$filter=".urlencode($filter);
    $method = "GET";
    return $this->callApi($this->ServiceName, __FUNCTION__, $endpoint, $method);
  }

}

