<?php

/**
 * Description of UtilityTest
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
require_once '../src/AuderoWavExtractor/Utility/Converter.php';

class ConverterTest extends PHPUnit_Framework_TestCase
{
   public function testMegabyteToByte()
   {
      $Megabytes = array(
          1,
          4,
          3.6,
          0,
          -10,
          NULL,
          'test'
      );
      $Bytes = array(
          1048576,
          4194304,
          3774874,
          0,
          0,
          0,
          0
      );

      $this->assertSameSize($Megabytes, $Bytes);
      foreach($Megabytes as $Key => $Megabyte)
         $this->assertEquals(Converter::megabyteToByte($Megabytes[$Key]), $Bytes[$Key]);
   }

   public function testMillisecondsToByte()
   {
      $DataRate = array(
          44100,
          22050,
          88200,
          44100,
          -10,
          0
      );

      $Milliseconds = array(
          1000,
          40500,
          61800,
          120000,
          0,
          -10
      );

      $Bytes = array(
          44100,
          893026,
          5450760,
          5292000,
          0,
          0
      );

      $this->assertSameSize($Milliseconds, $DataRate);
      $this->assertSameSize($Milliseconds, $Bytes);
      foreach($Milliseconds as $Key => $Millisecond)
         $this->assertEquals(Utility::millisecondsToByte($Milliseconds[$Key], $DataRate[$Key]), $Bytes[$Key]);
   }
}

?>
