<?php

/**
 * Description of AuderoWavExtractorTest
 *
 * LICENSE: Permission is granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
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
 * @author     Aurelio De Rosa <aureliodersa@gmail.com>
 * @version    0.2
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 */
require_once '../src/Converter.php';

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
}

?>
