# Audero Wav Extractor #
[Audero Wav Extractor](https://bitbucket.org/AurelioDeRosa/auderowavextractor) is a PHP library that allows to extract a chunk from a wav file. The extracted chunk can be saved on the hard disk, can be forced to be prompted as download by the user's browser, returned as a string for a later processing or a combination of the first and second possibilities. It is very easy to use "Audero Wav Extractor" to extract a piece of audio from a wav file. All you have to do is give the name of the file, the start and the end time to extract (optionally you can provide a name for the extracted chunk).

## Requirements ##
This class requires PHP version 5.3 or higher

## Usage ##
"Audero Wav Extractor" is very easy to use. All you need to do is include it and call the library's main method `extractChunck()` as you can see in the following snippet.

    <?php
       require_once('AuderoWavExtractor/AuderoWavExtractor.php');

       $Extractor = new AuderoWavExtractor('path-to-source-file/filename.wav');
       $Extractor->extractChunk(0, 2000);
    ?>

## Examples ##
In this section you can see several examples of how to take advantage of "Audero Wav Extractor".

### Example 1 ###
#### Extract a chunk from a wav file and force the download to the user's browser ####

    <?php
       require_once('AuderoWavExtractor/AuderoWavExtractor.php');

       $InputFile = 'path-to-source-file/filename.wav';

       $Start = 0 * 1000; // From 0 seconds
       $End = 2 * 1000; // To 2 seconds

       try
       {
          $Extractor = new AuderoWavExtractor($InputFile);
          $Extractor->extractChunk($Start, $End);
          echo 'Chunk extraction completed.';
       }
       catch (Exception $Ex)
       {
          echo 'An error has occurred: ' . $Ex->getMessage();
       }
    ?>

### Example 2 ###
#### Extract a chunk from a wav file and save it into the local disk ####

    <?php
       require_once('AuderoWavExtractor/AuderoWavExtractor.php');

       $InputFile = 'path-to-source-file/filename.wav';
       $OutputFile = 'path-to-destination-file/output-filename.wav';

       $Start = 0 * 1000; // From 0 seconds
       $End = 2 * 1000; // To 2 seconds

       try
       {
          $Extractor = new AuderoWavExtractor($InputFile);
          $Extractor->extractChunk($Start, $End, 2, $OutputFile);
          echo 'Chunk extraction completed.';
       }
       catch (Exception $Ex)
       {
          echo 'An error has occurred: ' . $Ex->getMessage();
       }
    ?>

## License ##
[Audero Wav Extractor](https://bitbucket.org/AurelioDeRosa/auderowavextractor) is licensed under the [CC BY-NC 3.0](http://creativecommons.org/licenses/by-nc/3.0/) ("Creative Commons Attribution NonCommercial 3.0")

## Authors ##
[Aurelio De Rosa](http://www.audero.it) (Twitter: [@AurelioDeRosa](https://twitter.com/AurelioDeRosa))