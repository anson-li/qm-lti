<body class="bg-pattern" style="background-color: rgb(250, 250, 250);">
  <div id="Wrapper">
    <div id="MainContentWrapper" class="col-md-10 col-md-offset-1" style="padding-top: 5em">
      <div id="ContentWrapper">
        <div id="HeaderWrapper" class="header-top padding-top-md">
          <a href="https://www.questionmark.com/"><img id="logoImage" src="/web/images/logo.png" alt="Questionmark" class="center-block" /></a>
        </div>
        <hr class="qm-divider" />
        <div id="PageContent" class="block-color">
        <div id="body" class="container-fluid">
        <p>
          <a class="btn btn-default btn-grey" href="<?php echo $return_url; ?>">Back to Course</button></a>
          <a class="btn btn-default btn-grey" href="<?php echo $em_url; ?>" target="_blank" />Log into Questionmark Portal</a>
          <a class="btn btn-default btn-grey" href="staff_results.php" />View Assessment Results</a>
        </p>
<?php
  if (!$_SESSION['allow_outcome']) {
?>
        <p><strong>No score will be saved by this connection.</strong></p>
<?php
  }
?>
        <h1>Assessments</h1>
        <hr class="qm-divider-sm"><br>
<?php
  if ((count($assessments) !== 0) && !is_null($assessments[0])) {
?>
        <form action="staff.php" method="POST">
        <table class="DataTable-staff table table-sm" cellpadding="0" cellspacing="0">
          <thead>
            <tr class="GridHeader">
              <td>&nbsp;</td>
              <td class="AssessmentName">Assessment Name</td>
              <td class="AssessmentAuthor">Assessment Author</td>
              <td class="LastModified">Last Modified</td>
            </tr>
          </thead>
          <tbody>
<?php
    $i = 0;
    foreach ($assessments as $assessment) {
      $i++;
      if ($assessment->Assessment_ID == $assessment_id) {
        $selected = ' checked="checked" onclick="doReset();"';
      } else {
        $selected = ' onclick="doChange(\'\');"';
      }
?>
            <tr class="GridRow">
              <td>
                <input type="radio" name="assessment" value="<?php echo $assessment->Assessment_ID; ?>" <?php echo $selected; ?> />
              </td>
              <td><?php echo $assessment->Session_Name; ?></td>
              <td><?php echo $assessment->Author; ?></td>
              <td><?php echo $assessment->Modified_Date; ?></td>
            </tr>
<?php
    }
?>
          </tbody>
        </table>
        <hr class="qm-divider-sm"><br>
        <p>
        <input type="hidden" id="id_coachingreport" name="id_coachingreport" value="0">
        <div class="row">
          <div class="col1">
          Allow participants to view coaching reports
          </div>
          <div class="col2">
          <input type="checkbox" id="id_coachingreport" name="id_coachingreport" onclick="doChange('');" value="1" <?php echo $coaching_check ?> >
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col1">
          Select result to display:
          </div>
          <div class="col2">
          <select id="id_multipleresult" class="form-control dropdown-select" name="id_multipleresult" onchange="doChange('');">

<?php
      foreach ($arr_results as $results) {
        if ($results == $multiple_results) {
          $selectresults = 'selected';
        } else {
          $selectresults = '';
        }
?>
          <option value="<?php echo $results; ?>" <?php echo $selectresults; ?>><?php echo $results; ?></option>
<?php
      }
?>
        </select>
        </div>
        </div>
        <br>
        <div class="row">
          <div class="col1">
          Number of Attempts
          </div>
          <div class="col2">

          <select id="id_numberattempts" class="form-control dropdown-select" name="id_numberattempts" onchange="doChange('');">
            <option value="none" <?php echo $no_attempts; ?>>No limit</option>
            <?php
              for ($i = 1; $i <= 10; $i++) {
                if ($i == $number_attempts) {
                  $selected_attempts = 'selected';
                } else {
                  $selected_attempts = '';
                }
            ?>
              <option value="<?php echo $i; ?>" <?php echo $selected_attempts; ?>><?php echo $i; ?></option>
            <?php
              }
            ?>
          </select>
          </div>
        </div>
        <br><br><br>
        <input class="button btn btn-grey no-margin" type="submit" id="id_save" value="Save change" disabled="disabled" />
        </p>
        <br><br><br>
        <br><br><br>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-10 col-md-offset-1">
      <p class="footer"><span id="Copyright"> © 2018 Questionmark Computing Ltd.</span></p>
  </div>
</body>
<?php
  } else {
?>
        <p>No assessments available.</p>
      </div>
    </div>
  </div>
  <div class="col-md-10 col-md-offset-1">
      <p class="footer"><span id="Copyright"> © 2018 Questionmark Computing Ltd.</span></p>
  </div>
</body>
<?php
  }
?>
