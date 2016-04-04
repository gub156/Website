<?php

session_start(); //Démarrage de la session php

?>

<!DOCTYPE HTML>
<html>

<head>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="0_css/style_feedback.css" />
  <!-- modernizr enables HTML5 elements and feature detects -->
  <script type="text/javascript" src="js/modernizr-1.5.min.js"></script>
</head>

<title>REFAST - Refast Development Tools Feedback</title>

<body>
  <div id="main">
    <div id="site_content">
      <div id="content">
        <h1>Refast Development Tools Feedback</h1>
        
        <form id="feedback" action="feedback_RDT.php" method="post">
          <div class="form_settings">
            <p>Choose your language.<p/><br />
            <p>English&nbsp<input class="contact radio" type="radio" name="language" value="English"/><p/>
			<p>Deutsch&nbsp<input class="contact radio" type="radio" name="language" value="Deutsch"/><p/>
			<p>Français&nbsp<input class="contact radio" type="radio" name="language" value="Français"/><p/>
            <p><input class="submit" type="submit" name="language_choose" value="send" /></p>
          </div>
        </form>
      </div>
    </div>
    <?php include("1_includes/footer.html"); ?>
  </div>
  
  <!-- javascript at the bottom for fast page loading -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery.easing.min.js"></script>
  <script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
  <script type="text/javascript" src="js/jquery.kwicks-1.5.1.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#images').kwicks({
        max : 600,
        spacing : 2
      });
    });
  </script>
  <script type="text/javascript">
    $(function() {
      $("#lava_menu").lavaLamp({
        fx: "backout",
        speed: 700
      });
    });
  </script>
</body>
</html>
