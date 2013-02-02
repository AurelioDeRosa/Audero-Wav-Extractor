# Audero Wav Extractor #
[Audero Wav Extractor](https://bitbucket.org/AurelioDeRosa/audero-wav-extractor) is a PHP library that allows to extract a chunk from a wav file. The extracted chunk can be saved on the hard disk, can be forced to be prompted as download by the user's browser or returned as a string for a later processing.

## Requirements ##
This library requires PHP version 5.3 or higher

## Usage ##
"Audero Wav Extractor" is very easy to use. Since it uses namespaces, you can use your own autoloader or the one included in the library to dynamically load the classes needed. If you already have your class' loader, you've to just add the path to the library to the include path. Otherwise, you can set the included loader as shown in the following example. After that, you can simply create an AuderoWavExtractor instance and call the method that better fits your needs. With [Audero Wav Extractor](https://bitbucket.org/AurelioDeRosa/audero-wav-extractor) you can download the chunk using `downloadChunk()`, save it on the hard disk using `saveChunk()` or retrieve it as a string using `getChunk()`.

### Example 1 ###
#### Extract a chunk from a wav file and force the download to the user's browser ####

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
           $extractor->saveChunk($start, $end, $outputFile);
           echo 'Chunk extraction completed.';
        } catch (\Exception $ex) {
           echo 'An error has occurred: ' . $ex->getMessage();
        }
    ?>

### Example 2 ###
#### Extract a chunk from a wav file and save it into the local disk ####

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

        // Extract the chunk and force the download to the user browser
        try {
           $extractor = new \Audero\WavExtractor\AuderoWavExtractor($inputFile);
           $extractor->downloadChunk($start, $end, $outputFile);
           echo 'Chunk extraction completed.';
        } catch (\Exception $ex) {
           echo 'An error has occurred: ' . $ex->getMessage();
        }
    ?>

## License ##
[Audero Wav Extractor](https://bitbucket.org/AurelioDeRosa/audero-wav-extractor) is licensed under the [CC BY-NC 3.0](http://creativecommons.org/licenses/by-nc/3.0/) ("Creative Commons Attribution NonCommercial 3.0")

## Authors ##
[Aurelio De Rosa](http://www.audero.it) (Twitter: [@AurelioDeRosa](https://twitter.com/AurelioDeRosa))