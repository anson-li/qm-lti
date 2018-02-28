<?php
/*
 *  LTI-Connector - Connect to Perception via IMS LTI
 *  Copyright (C) 2017  Questionmark
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along
 *  with this program; if not, write to the Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 *  Contact: info@questionmark.com
 *
 *  Version history:
 *    1.0.00   1-May-12  Initial prototype
 *    1.1.00   3-May-12  Added test harness
 *    1.2.00  23-Jul-12
 *    2.0.00  18-Feb-13
*/

require_once('../resources/lib.php');
require_once('model/student.php');
require_once('../resources/LTI_Data_Connector_qmp.php');

  session_name(SESSION_NAME);
  session_start();

  error_log("Testing 0");

  $student = new Student($_SESSION);
  $student->checkValid();

  error_log("Testing 1");

  if (isset($_SESSION['error'])) {
    error_log("Error reached at 1: " . $_SESSION['error']);
  }

  // Activate SOAP Connection.
  if (!isset($_SESSION['error'])) {
    perception_soapconnect();
  }

  error_log("Testing 2");

  if (isset($_SESSION['error'])) {
    error_log("Error reached at 2: " . $_SESSION['error']);
  }

  if (isset($_POST['action'])) {
    $student->identifyAction($_POST['action']);
  }

  error_log("Testing 3");

  if (isset($_SESSION['error'])) {
    error_log("Error reached at 3: " . $_SESSION['error']);
  }

  error_log("Testing Create Participant");

  $student = $student->createParticipant();

  if (isset($_SESSION['error'])) {
    error_log("Error reached at 4: " . $_SESSION['error']);
  }
  error_log("Test error message after createParticipant");


  $student = $student->joinGroup();
 error_log("Test error message after joinGroup");

  $assessment = $student->getAssessment();
  $student = $student->getPastAttempts();
  $past_attempts = $student->getAttemptDetails();
  $bool_coaching_report = $student->isCoachingReportAvailable();
  $number_attempts = $student->getNumberAttempts();
  $launch = $student->checkLaunchDisabled();
  $parsed_attempts = $student->getParsedAttempts();
  $attempt_in_progress = $student->getAttemptProgress();

  if (isset($_SESSION['error'])) {
   header("Location: error.php");
  }

  page_header();
  include_once("view/student_nav.php");
?>
