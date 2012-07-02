<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8" />
      <title>Audero Wav Extractor</title>
   </head>
   <body>
      <?php
         require_once('AuderoWavExtractor/AuderoWavExtractor.php');

         $InputFile = 'path-to-source-file/filename.wav';
         $OutputFile = 'path-to-destination-file/output-filename.wav';

         $Start = 0 * 1000; // From 0 seconds
         $End = 2 * 1000; // To 2 seconds

         // EXAMPLE 1
         try
         {
            $Extractor = new AuderoWavExtractor($InputFile);
            // Extract the chunk and save it on the harddisk
            $Extractor->extractChunk($Start, $End, 2, $OutputFile);
            echo 'Chunk extraction completed.';
         }
         catch (Exception $Ex)
         {
            echo 'An error has occurred: ' . $Ex->getMessage();
         }

           // EXAMPLE 2
//         try
//         {
//            $Extractor = new AuderoWavExtractor($InputFile);
//            // Extract the chunk and force the download to the user browser
//            $Extractor->extractChunk($Start, $End, 1, $OutputFile);
//            echo 'Chunk extraction completed.';
//         }
//         catch (Exception $Ex)
//         {
//            echo 'An error has occurred: ' . $Ex->getMessage();
//         }
      ?>
   </body>
</html>
