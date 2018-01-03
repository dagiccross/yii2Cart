<?php
echo '<center><h1>Thank you for ordering!</h1></center>';
$headers = $_SERVER['SERVER_NAME'];

//echo "HTTP_HOST [{$_SERVER['HTTP_HOST']}]\n"; echo "SERVER_NAME [{$_SERVER['SERVER_NAME']}]";

echo '<center><a href="http://'.$headers.'/index.php?r=site/index">Return to homepage</a></center></div>';
//echo $headers;

?>
<html>
<body>
  <script type="text/javascript">
  var count = 6;
  var x = location.hostname;


  function countDown(){
      var timer = document.getElementById("timer");
      if(count > 0){
          count--;
          timer.innerHTML = "</br><center>This page will redirect in "+count+" seconds.</center>";
          setTimeout("countDown()", 1000);
      }else{
          var redirect = document.location.href="/";
          window.location.href = redirect;
      }
  }
  </script>

  <span id="timer">
  <script type="text/javascript">countDown();</script>
  </span>
</body>
</html>
