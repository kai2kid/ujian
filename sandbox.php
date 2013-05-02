<html>
  <head>
    <script src="script/tinymce/tinymce.min.js"></script>  
    <script type='text/javascript'>
    tinymce.init({
      selector: "textarea"
    });
  </script>
  </head>
  <body>
    <?php
  echo $_POST['tests'];
?>
<br><br><br>
    <form method=post>
      <textarea name=tests id=tests></textarea>
      <input type="submit">
    </form>
  </body>
</html>