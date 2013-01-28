<?php

/**
 * The class test for the Audero Wav Extractor class
 *
 * LICENSE: "Audero Wav Extractor" (from now on "The software") is released under
 * the CC BY-NC 3.0 ("Creative Commons Attribution NonCommercial 3.0") license.
 * More details can be found here: http://creativecommons.org/licenses/by-nc/3.0/
 *
 * WARRANTY: The software is provided "as is", without warranty of any kind,
 * express or implied, including but not limited to the warranties of merchantability,
 * fitness for a particular purpose and noninfringement. In no event shall the
 * authors or copyright holders be liable for any claim, damages or other
 * liability, whether in an action of contract, tort or otherwise, arising from,
 * out of or in connection with the software or the use or other dealings in
 * the software.
 *
 * @author  Aurelio De Rosa <aurelioderosa@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link    https://bitbucket.org/AurelioDeRosa/auderowavextractor
 */
class AuderoWavExtractorTest extends PHPUnit_Framework_TestCase
{
    private static $testRepetition = 10;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $files = glob('*.wav');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $files = glob('*.wav');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function dataProviderBadBoundaries()
    {
        return array(
            array(-1, -1),
            array(-1, 1),
            array(0, -1),
            array(1, 0)
        );
    }

    public function dataProviderBadInputFilenames()
    {
        return array(
            array(null),
            array('../wav/not-exists.wav'),
            array('../wav/not-a-wav.wav')
        );
    }

    public function dataProviderOutputFilenames()
    {
        return array(
            array(null),
            array('test.wav')
        );
    }

    public function testConstructorFileExists()
    {
        return new \Audero\WavExtractor\AuderoWavExtractor('../wav/sample.wav');
    }

    /**
     * @dataProvider dataProviderBadInputFilenames
     * @expectedException InvalidArgumentException
     */
    public function testConstructorBadFilenames($filename)
    {
        return new \Audero\WavExtractor\AuderoWavExtractor($filename);
    }

    /**
     * @depends  testConstructorFileExists
     */
    public function testGetDuration(\Audero\WavExtractor\AuderoWavExtractor $extractor)
    {
        $this->assertGreaterThanOrEqual(0, $extractor->getWav()->getDuration());
    }

    /**
     * @depends testConstructorFileExists
     * @runInSeparateProcess
     */
    public function testGetWavChunk(\Audero\WavExtractor\AuderoWavExtractor $extractor)
    {
        for ($i = 0; $i < self::$testRepetition; $i++) {
            $start = rand(0, $extractor->getWav()->getDuration());
            $end = rand($start, $extractor->getWav()->getDuration());

            $expectedException = (!$extractor->isEnoughMemory($start, $end)) || ($start >= $end);

            try {
                $chunk = $extractor->extractChunk($start, $end);

                $this->assertFalse($expectedException);
                $this->expectOutputString($chunk);
                $this->assertNull($chunk);
            } catch (\Exception $ex) {
                $this->assertTrue($expectedException);
            }
        }
    }

    /**
     * @dataProvider dataProviderBadBoundaries
     * @expectedException InvalidArgumentException
     * @depends testConstructorFileExists
     */
    public function testGetWavChunkBadParams($start, $end, \Audero\WavExtractor\AuderoWavExtractor $extractor)
    {
        $extractor->extractChunk($start, $end);
    }

    /**
     * @dataProvider dataProviderOutputFilenames
     * @depends testConstructorFileExists
     */
    public function testSaveChunk($filename, \Audero\WavExtractor\AuderoWavExtractor $extractor)
    {
        for ($i = 0; $i < self::$testRepetition; $i++) {
            $start = rand(0, $extractor->getWav()->getDuration());
            $end = rand($start, $extractor->getWav()->getDuration());

            $expectedException = (!$extractor->isEnoughMemory($start, $end)) || ($start >= $end);

            try {
                $chunk = $extractor->extractChunk($start, $end, 2, $filename);

                $this->assertFalse($expectedException);
                $this->assertFileExists($filename);
                $this->assertNull($chunk);
            } catch (Exception $ex) {
                $this->assertTrue($expectedException);
            }
        }
    }

    /**
     * @depends testConstructorFileExists
     */
    public function testGetChunk(\Audero\WavExtractor\AuderoWavExtractor $extractor)
    {
        for ($i = 0; $i < self::$testRepetition; $i++) {
            $start = rand(0, $extractor->getWav()->getDuration());
            $end = rand($start, $extractor->getWav()->getDuration());

            $expectedException = (!$extractor->isEnoughMemory($start, $end)) || ($start >= $end);

            try {
                $chunk = $extractor->extractChunk($start, $end, 3);

                $this->assertFalse($expectedException);
                $this->assertNotNull($chunk);
            } catch (Exception $ex) {
                $this->assertTrue($expectedException);
            }

            unset($chunk);
        }
    }

    /**
     * @dataProvider dataProviderOutputFilenames
     * @depends testConstructorFileExists
     */
    public function testSaveAndGetChunk($filename, \Audero\WavExtractor\AuderoWavExtractor $extractor)
    {
        for ($i = 0; $i < self::$testRepetition; $i++) {
            $start = rand(0, $extractor->getWav()->getDuration());
            $end = rand($start, $extractor->getWav()->getDuration());

            $expectedException = (!$extractor->isEnoughMemory($start, $end)) || ($start >= $end);

            try {
                $chunk = $extractor->extractChunk($start, $end, 4, $filename);

                $this->assertFalse($expectedException);
                $this->assertFileExists($filename);
                $this->assertNotNull($chunk);
            } catch (Exception $ex) {
                $this->assertTrue($expectedException);
            }

            unset($chunk);
        }
    }
}
