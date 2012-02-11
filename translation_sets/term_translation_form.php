
<html>
<head>
  <title>Please enter terms to translate</title>
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
</head>
<body>
  <style>
    input { border: 1px solid black; }
  </style>
  <div style="width:900px; margin-left:auto; margin-right:auto; text-align:center">
  <form method="post" action="build_translation_sets_terms_virgin.php">
    <h3>Please enter info for the terms in the first language</h3>
    <font color="red"><?php echo $error_msg; ?></font>
    Language ('en' for English, 'es' for Spanish, 'fr' for French, 'ar' for Arabic):
        <input type="input" name="language_1" value="<?php echo $_POST['language_1'];?>"/><br />
    tid for first term:
        <input type="input" name="first_tid_1" value="<?php echo $_POST['first_tid_1'];?>"/><br/>
    tid for last term:
        <input type="input" name="last_tid_1" value="<?php echo $_POST['last_tid_1'];?>"/><br/>        
        
    <h3>Please enter info for the terms in the second language</h3>
    <font color="red"><?php echo $error_msg; ?></font>
    Language ('en' for English, 'es' for Spanish, 'fr' for French, 'ar' for Arabic):
        <input type="input" name="language_2" value="<?php echo $_POST['language_2'];?>"/><br />
    tid for first term:
        <input type="input" name="first_tid_2" value="<?php echo $_POST['first_tid_2'];?>"/><br/>
    tid for last term:
        <input type="input" name="last_tid_2" value="<?php echo $_POST['last_tid_2'];?>"/><br/><br/><br/>  
        
   <input type="submit" name="Submit" value="Submit" />
  </form>
  <br />
  </div>
</body>
</html>
