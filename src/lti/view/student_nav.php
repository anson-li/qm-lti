<body style="background-color: rgb(250, 250, 250);">
<div id="Wrapper">
  <div id="MainContentWrapper" class="col-md-6 col-md-offset-3" style="padding-top: 5em">
    <div id="ContentWrapper">
      <div id="HeaderWrapper" class="header-top padding-top-md">
        <img id="logoImage" src="/web/images/logo.png" alt="Questionmark" class="center-block" />
      </div>
      <hr class="qm-divider" />
      <div id="PageContent" class="block-color">
        <div class="container-fluid"><p><button type="button" class="btn btn-default" style="background-color: #eee; border-color: #fff; color: #333;" onclick="location.href=\'' . $_SESSION['lti_return_url'] . '\';">Return to course environment</button></p></div>
          <div id="body" class="container-fluid" style="padding: 0px">
          <form action="student_nav.php" method="POST">
            <div class="col-md-6">
              <h2 style="font-weight: normal; color: #999;">Assessment</h2>
              <hr class="qm-divider-sm">
              <h1 style="margin: 0px"><?php echo $assessment->Session_Name; ?></h1>
              <br><br>
              <p>You have completed <?php echo $past_attempts; ?> out of <?php echo $parsed_attempts; ?> attempts.
              <br><br>
              <?php if ($attempt_in_progress === "Yes") { ?>
                You currently have an attempt in progress.</p>
                <?php } else { ?>
                You currently do not have an attempt in progress.</p>
              <?php } ?>
            </div>
            <div class="col-md-6">
              <?php if ($launch) { ?>
                 <input class="btn btn-sm btn-link btn-wide btn-success" type="submit" name="action" value="Launch Assessment"/>
              <?php } ?>
              <br>
              <?php // if ($bool_coaching_report) { ?>
                <input class="btn btn-sm btn-link btn-wide btn-info" type="submit" name="action" value="View Coaching Report" formtarget="_blank"/>
              <?php // } ?>
            </div>
          </form>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
