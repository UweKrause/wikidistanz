<?php error_reporting(E_ALL); ?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wikipedia Abstand</title>
  </head>
  <body>
    
    <p>
    <form action="/" method="get">
      <fieldset>
        <legend>Eingabe</legend>
        <?php
        
        $form_start = (empty($_GET['start'])) ? 'Hamburg' : $_GET['start'];
        $form_ziel = (empty($_GET['ziel'])) ? 'Adolf Hitler' : $_GET['ziel'];
        
        ?>
        
      Start: <input type="text" name="start" value="<?= $form_start; ?>">
      &rarr; Ziel: <input type="text" name="ziel" value="<?=  $form_ziel; ?>">
      
      <?php
        // ignoriert eine etwaige Benutzereingabe
        ///$form_ziel = "Adolf Hitler"
      ?>
      
      <input type="submit" value="Weg finden!">
      Groß/Klein-Schreibung GENAU wie in der Wikipedia!
      </fieldset>
    </form>
    </p>
    
    
      <?php
      /**
       * The Program is loaded here.
       * Preparations are in the functions.php
       */
      
      require_once('functions.php');
      
      $time_start = microtime_float();
      
      require_once('programm.php');
      
      $time_end = microtime_float();
      
      $time = round($time_end - $time_start, 3);
      echo "<p>Ausführungszeit: $time Sekunden</p>";
      ?>
    
  </body>
</html>