<?php
/**
 * Description of Converter
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
class Converter
{
   /**
    * 
    * @param string $Hex
    * @return string 
    */
   public static function hexToString($Hex)
   {
      $String = '';
      for ($i = 0; $i < strlen($Hex) - 1; $i+=2)
         $String .= chr(hexdec($Hex[$i] . $Hex[$i + 1]));

      return $String;
   }

   /**
    *
    * @param string $String
    * @return string 
    */
   public static function stringToHex($String)
   {
      $Hex = '';
      for ($i = 0; $i < strlen($String); $i++)
         $Hex .= dechex(ord($String[$i]));

      return $Hex;
   }

   public static function megabyteToByte($Megabyte)
   {
      if (! is_numeric($Megabyte) ||  $Megabyte < 0)
         return 0;

      $Bytes = $Megabyte * pow(1024, 2);
      $Bytes = (int)ceil($Bytes);

      return $Bytes;
   }
}

?>
