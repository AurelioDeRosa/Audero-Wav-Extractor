<?php

/**
 * Test class for the Converter class
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
class ConverterTest extends PHPUnit_Framework_TestCase
{
   public function testMegabyteToByte()
   {
      $megabytes = array(
          1,
          4,
          3.6,
          0,
          -10,
          null,
          'test'
      );
      $bytes = array(
          1048576,
          4194304,
          3774874,
          0,
          0,
          0,
          0
      );

      $this->assertSameSize($megabytes, $bytes);
      foreach($megabytes as $key => $megabyte)
         $this->assertEquals(Converter::megabyteToByte($megabytes[$key]), $bytes[$key]);
   }

   public function testMillisecondsToByte()
   {
      $dataRate = array(
          44100,
          22050,
          88200,
          44100,
          -10,
          0
      );
      $milliseconds = array(
          1000,
          40500,
          61800,
          120000,
          0,
          -10
      );
      $bytes = array(
          44100,
          893026,
          5450760,
          5292000,
          0,
          0
      );

      $this->assertSameSize($milliseconds, $dataRate);
      $this->assertSameSize($milliseconds, $bytes);
      foreach($milliseconds as $key => $millisecond) {
         $this->assertEquals(Utility::millisecondsToByte($milliseconds[$key], $dataRate[$key]), $bytes[$key]);
      }
   }
}
