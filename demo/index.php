<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8" />
      <title>Audero Wav Extractor</title>
   </head>
   <body>
      <?php
         // Set include path
         set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../src/');
         // Include the class loader
         require_once 'Audero\Loader\AutoLoader.php';
         // Set the classes' loader method
         spl_autoload_register('Audero\Loader\AutoLoader::autoload');

         $inputFile = 'sample1.wav';
         $outputFile = 'chunk.wav';
         $start = 0 * 1000; // From 0 seconds
         $end = 2 * 1000; // To 2 seconds

         // Extract the chunk and save it on the hard disk
         try {
            $extractor = new \Audero\WavExtractor\AuderoWavExtractor($inputFile);
            $extractor->extractChunk($start, $end, 2, $outputFile);
            echo 'Chunk extraction completed.';
         } catch (\Exception $ex) {
            echo 'An error has occurred: ' . $ex->getMessage();
         }

           // Extract the chunk and force the download to the user browser
//         try {
//            $extractor = new \Audero\WavExtractor\AuderoWavExtractor($inputFile);
//            $extractor->extractChunk($start, $end, 1, $outputFile);
//            echo 'Chunk extraction completed.';
//         } catch (\Exception $ex) {
//            echo 'An error has occurred: ' . $ex->getMessage();
//         }
      ?>
   </body>
</html>
