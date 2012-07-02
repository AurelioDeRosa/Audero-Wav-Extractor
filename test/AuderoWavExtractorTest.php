<?php

/**
 * Description of AuderoWavExtractorTest
 *
 * LICENSE: This software is released under the CC BY-NC 3.0
 * ("Creative Commons Attribution-NonCommercial 3.0") license.
 * More details can be found here: http://creativecommons.org/licenses/by-nc/3.0/
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author Aurelio De Rosa <aurelioderosa@gmail.com>
 * @version    1.0
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 * 2-mag-2012
 */

class AuderoWavExtractorTest extends PHPUnit_Framework_TestCase
{
   private static $TestRepetition = 10;

   private static function autoload()
   {
      set_include_path(implode(PATH_SEPARATOR,
                      array(
                  realpath(dirname(__FILE__) . '/Wav'),
                  realpath(dirname(__FILE__) . '/Utility'),
                  get_include_path(),
              )));

      $Files = glob('..\src\AuderoWavExtractor\*.php');
      $Files = array_merge($Files, glob('..\src\AuderoWavExtractor\Chunk\*.php'));
      foreach($Files as $File)
         require_once ($File);
   }

   public static function setUpBeforeClass()
   {
      parent::setUpBeforeClass();
      self::autoload();
      $Files = glob('*.wav');
      foreach($Files as $File)
         unlink($File);
   }

   public static function tearDownAfterClass()
   {
      parent::tearDownAfterClass();
      $Files = glob('*.wav');
      foreach($Files as $File)
         unlink($File);
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
          array(NULL),
          array('../wav/not-exists.wav'),
          array('../wav/not-a-wav.wav')
      );
   }

   public function dataProviderOutputFilenames()
   {
      return array(
          array(NULL),
          array('test.wav')
      );
   }

   public function testConstructorFileExists()
   {
      $Filename = '../wav/sample.wav';
      $Extractor = new AuderoWavExtractor($Filename);

      return $Extractor;
   }

   /**
    * @dataProvider dataProviderBadInputFilenames
    * @expectedException InvalidArgumentException
    */
   public function testConstructorBadFilenames($Filename)
   {
      $Extractor = new AuderoWavExtractor($Filename);

      return $Extractor;
   }

   /**
    * @depends  testConstructorFileExists
    */
   public function testGetDuration(AuderoWavExtractor $Extractor)
   {
      $this->assertGreaterThanOrEqual(0, $Extractor->getWav()->getDuration());
   }

   /**
    * @depends testConstructorFileExists
    * @runInSeparateProcess
    */
   public function testGetWavChunk(AuderoWavExtractor $Extractor)
   {
      for($i = 0; $i < self::$TestRepetition; $i++)
      {
         $Start = rand(0, $Extractor->getWav()->getDuration());
         $End = rand($Start, $Extractor->getWav()->getDuration());

         $ExpectedException = (! $Extractor->isEnoughMemory($Start, $End)) || ($Start >= $End);

         try
         {
            $Chunk = $Extractor->extractChunk($Start, $End);

            $this->assertFalse($ExpectedException);
            $this->expectOutputString($Chunk);
            $this->assertNull($Chunk);
         }
         catch(Exception $Ex)
         {
            $this->assertTrue($ExpectedException);
         }
      }
   }

   /**
    * @dataProvider dataProviderBadBoundaries
    * @expectedException InvalidArgumentException
    * @depends testConstructorFileExists
    */
   public function testGetWavChunkBadParams($Start, $End, AuderoWavExtractor $Extractor)
   {
      $Extractor->extractChunk($Start, $End);
   }

   /**
    * @dataProvider dataProviderOutputFilenames
    * @depends testConstructorFileExists
    */
   public function testSaveChunk($Filename, AuderoWavExtractor $Extractor)
   {
      for($i = 0; $i < self::$TestRepetition; $i++)
      {
         $Start = rand(0, $Extractor->getWav()->getDuration());
         $End = rand($Start, $Extractor->getWav()->getDuration());

         $ExpectedException = (! $Extractor->isEnoughMemory($Start, $End)) || ($Start >= $End);

         try
         {
            $Chunk = $Extractor->extractChunk($Start, $End, 2, $Filename);

            $this->assertFalse($ExpectedException);
            $this->assertFileExists($Filename);
            $this->assertNull($Chunk);
         }
         catch(Exception $Ex)
         {
            $this->assertTrue($ExpectedException);
         }
      }
   }

   /**
    * @depends testConstructorFileExists
    */
   public function testGetChunk(AuderoWavExtractor $Extractor)
   {
      for($i = 0; $i < self::$TestRepetition; $i++)
      {
         $Start = rand(0, $Extractor->getWav()->getDuration());
         $End = rand($Start, $Extractor->getWav()->getDuration());

         $ExpectedException = (! $Extractor->isEnoughMemory($Start, $End)) || ($Start >= $End);

         try
         {
            $Chunk = $Extractor->extractChunk($Start, $End, 3);

            $this->assertFalse($ExpectedException);
            $this->assertNotNull($Chunk);
         }
         catch(Exception $Ex)
         {
            $this->assertTrue($ExpectedException);
         }

         unset($Chunk);
      }
   }

   /**
    * @dataProvider dataProviderOutputFilenames
    * @depends testConstructorFileExists
    */
   public function testSaveAndGetChunk($Filename, AuderoWavExtractor $Extractor)
   {
      for($i = 0; $i < self::$TestRepetition; $i++)
      {
         $Start = rand(0, $Extractor->getWav()->getDuration());
         $End = rand($Start, $Extractor->getWav()->getDuration());

         $ExpectedException = (! $Extractor->isEnoughMemory($Start, $End)) || ($Start >= $End);

         try
         {
            $Chunk = $Extractor->extractChunk($Start, $End, 4, $Filename);

            $this->assertFalse($ExpectedException);
            $this->assertFileExists($Filename);
            $this->assertNotNull($Chunk);
         }
         catch(Exception $Ex)
         {
            $this->assertTrue($ExpectedException);
         }

         unset($Chunk);
      }
   }
}

?>
