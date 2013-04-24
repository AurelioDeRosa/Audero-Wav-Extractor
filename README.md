# Audero Wav Extractor #
[Audero Wav Extractor](https://github.com/AurelioDeRosa/Audero-Wav-Extractor) is a PHP library that allows to extract a chunk from a wav file. The extracted chunk can be saved on the hard disk, can be forced to be prompted as download by the user's browser or returned as a string for a later processing.

## Requirements ##
This library requires PHP version 5.3 or higher.

## Download ##
### Download via [Composer](http://getcomposer.org/) ###
You can obtain "Audero Wav Extractor" via [Composer](http://getcomposer.org/) adding the following lines to your `composer.json`:

    "require": {
        "audero/audero-wav-extractor": "2.0.*"
    }

And then run the `install` command to resolve and download the dependencies:

    php composer.phar install

Composer will install the library to your project's `vendor/audero` directory.

### Download via [Git](http://git-scm.com/) ###
If you haven't or don't want to use [Composer](http://getcomposer.org/), you can download the library from its [repository](https://bitbucket.org/AurelioDeRosa/audero-wav-extractor) via [Git](http://git-scm.com/) running the following command:

    git clone https://bitbucket.org/AurelioDeRosa/audero-wav-extractor.git

## Usage ##
"Audero Wav Extractor" is very easy to use. However, since the library uses namespaces and follows the [PSR standards](https://github.com/php-fig/fig-standards), you've to use an autoloader to dynamically load the classes needed. After that, you have to create an `AuderoWavExtractor` instance and call the method that better fits your needs. With [Audero Wav Extractor](https://bitbucket.org/AurelioDeRosa/audero-wav-extractor) you can download the chunk using `downloadChunk()`, save it on the hard disk using `saveChunk()` or retrieve it as a string using `getChunk()`.

### Installed via [Composer](http://getcomposer.org/) ###
If you installed "Audero Wav Extractor" using [Composer](http://getcomposer.org/), you can rely on the built autoloader. So, after included the latter, you can use one of the previously cited methods as shown in the following example.

#### Extract a chunk from a wav file and force the download to the user's browser ####
    <?php
        // Include the Composer autoloader
        require_once 'vendor/autoload.php';

        $inputFile = 'sample1.wav';
        $outputFile = 'chunk.wav';
        $start = 0 * 1000; // From 0 seconds
        $end = 2 * 1000; // To 2 seconds

        // Extract the chunk and save it on the hard disk
        try {
           $extractor = new \Audero\WavExtractor\AuderoWavExtractor($inputFile);
           $extractor->downloadChunk($start, $end, $outputFile);
           echo 'Chunk extraction completed.';
        } catch (\Exception $ex) {
           echo 'An error has occurred: ' . $ex->getMessage();
        }
    ?>

### Installed via [Git](http://git-scm.com/) ###
If you obtained the code via [Git](http://git-scm.com/), you can use the autoloader provided by the library. However, before using it, you've to add the path to the library to the PHP include path as shown in the following example.

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
           $extractor->saveChunk($start, $end, $outputFile);
           echo 'Chunk extraction completed.';
        } catch (\Exception $ex) {
           echo 'An error has occurred: ' . $ex->getMessage();
        }
    ?>

## Resources ##
- [http://www.sonicspot.com/guide/wavefiles.html](http://www.sonicspot.com/guide/wavefiles.html)
- [http://www-mmsp.ece.mcgill.ca/documents/AudioFormats/WAVE/WAVE.html](http://www-mmsp.ece.mcgill.ca/documents/AudioFormats/WAVE/WAVE.html)

## License ##
[Audero Wav Extractor](https://github.com/AurelioDeRosa/Audero-Wav-Extractor) is licensed under the [CC BY-NC 3.0](http://creativecommons.org/licenses/by-nc/3.0/) ("Creative Commons Attribution NonCommercial 3.0")

## Authors ##
[Aurelio De Rosa](http://www.audero.it) (Twitter: [@AurelioDeRosa](https://twitter.com/AurelioDeRosa))
