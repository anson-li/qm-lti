<div id="MainContentWrapper" class="col-md-6 col-md-offset-3" style="padding-top: 5em">
  <div id="ContentWrapper">
    <div id="HeaderWrapper" class="header-top padding-top-md">
      <img id="logoImage" src="/web/images/logo.gif" alt="Questionmark" class="center-block" style="width: 175px; height: 32px; margin-left: 10px" />
    </div>
    <div id="PageContent">
      <div class="container-fluid"><p><button type="button" class="btn btn-default" onclick="location.href=\'' . $_SESSION['lti_return_url'] . '\';">Return to course environment</button></p></div>
        <div id="body" class="container-fluid col-md-6 offset-md-3">
        <h1>Student Portal</h1>
        <form action="student_nav.php" method="POST">
          <table class="DataTable table table-sm" cellpadding="0" cellspacing="0">
            <tr class="GridHeader">
              <th>Assessment Name</th>
              <th>Launch</th>
              <th># of Possible Attempts</th>
              <th># of Attempts Taken</th>
              <th>Attempt in Progress</th>
            <?php if ($bool_coaching_report) { ?>
              <th>Coaching Report</th>
            <?php } ?>
            </tr>
            <tr class="GridRow">
              <td><?php echo $assessment->Session_Name; ?></td>
              <td><?php echo $launch; ?></td>
              <td><?php echo $parsed_attempts; ?></td>
              <td><?php echo $past_attempts; ?></td>
              <td><?php echo $attempt_in_progress; ?></td>
            <?php if ($bool_coaching_report) { ?>
              <td><input class="btn btn-sm btn-link" type="submit" name="action" value="View Coaching Report" formtarget="_blank"/></td>
            <?php } ?>
            </tr>
          </table>
        </form>
        <br>
      </div>
    </div>
  </div>
</div>
