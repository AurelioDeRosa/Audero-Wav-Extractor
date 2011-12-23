<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title></title>
   </head>
   <body>
      <?php
         require_once('AuderoWavExtractor.php');

         $InputFile = './wav/sample.wav';
         $OutputFile = './wav/chunk.wav';
         
         $Start = 25 * 1000;
         $End = 35 * 1000;

         try
         {
            $Extractor = new AuderoWavExtractor($InputFile);
            $Chunk = $Extractor->extractChunk($Start, $End);
            echo 'Chunk extraction completed.';
         }
         catch (Exception $Ex)
         {
            echo 'An error has occurred: ' . $Ex->getMessage();
         }
      ?>
   </body>
</html>
