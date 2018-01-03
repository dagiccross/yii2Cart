<?php
$headers = $_SERVER['SERVER_NAME'];

//echo "HTTP_HOST [{$_SERVER['HTTP_HOST']}]\n"; echo "SERVER_NAME [{$_SERVER['SERVER_NAME']}]";

$url = 'http://' . $headers . '/index.php?r=cart/confirm-thank';
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
  $url .= '&' . $_SERVER['QUERY_STRING'];
}


//echo $headers;

?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<style>
@font-face {
  font-family: 'myfont';
  src: url('/frontend/web/fonts/OpenSans-Semibold.ttf');
}
@font-face {
  font-family: 'myfont-bold';
  src: url('/frontend/web/fonts/OpenSans-Bold.ttf');
}
@font-face {
  font-family: 'myfont-regular';
  src: url('/frontend/web/fonts/OpenSans-Regular.ttf');
}
body {  font-family: 'myfont', 'Microsoft YaHei'; position: relative; }
center { padding: 10px 0; }
h1 {  font-weight: 600; margin: 0; }
#wrap {
  font-size: 25px;
  width: 100%;
  max-width: 650px;
  border: 2px solid #ccc;
  border-radius: 4px;
  position: absolute;
  padding: 20px 0;
  top: 50%;
  left: 50%;
  -moz-transform: translate(-50%, -52%);
  -webkit-transform: translate(-50%, -52%);
  -o-transform: translate(-50%, -52%);
  -ms-transform: translate(-50%, -52%);
  transform: translate(-50%, -52%);
}
@media (max-width: 600px) {
  #wrap {
    max-width: 500px;
    font-size: 18px;
  }  
}
</style>
<div id="wrap">
  <center><span style="font-size:2.0em;"><img src="/frontend/web/images/infoico.png"></span></center>
  <center><h1>Thank you for ordering!</h1></center>
  <center>This page will redirect in <span id="timer"></span> seconds.</center>
</div>

<script type="text/javascript">
var count = 11;
var x = location.hostname;

function countDown(){
  var timer = document.getElementById("timer");
  if(count > 0){
    count--;
    timer.innerHTML = count;
    setTimeout("countDown()", 1000);
  }else{
    var redirect = document.location.href="<?php echo $url ?>";
    window.location.href = redirect;
  }
}
countDown();

</script>

</body>
</html>
