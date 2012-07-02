<?php
/**
 * This class allows to make some convertion and it is used as an utility by
 * other classes of the library. For example, this class allows to convert
 * an hex string into a decimal one and viceversa.
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
 * @author     Aurelio De Rosa <aureliodersa@gmail.com>
 * @version    1.0
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 * @package    Audero\AuderoWavExtractor
 */
class Converter
{
   /**
    * Converts a string composed of hex chars into a string of decimal chars
    *
    * @param string $Hex A string of hex chars
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
    * Converts a string composed of decimal chars into a string of hex chars
    *
    * @param string $String A string of decimal chars
    * @return string
    */
   public static function stringToHex($String)
   {
      $Hex = '';
      for ($i = 0; $i < strlen($String); $i++)
         $Hex .= dechex(ord($String[$i]));

      return $Hex;
   }

   /**
    * Converts a number expressed in megabyte into one expressed in bytes
    *
    * @param float $Megabyte
    * @return int
    */
   public static function megabyteToByte($Megabyte)
   {
      if (! is_numeric($Megabyte) ||  $Megabyte < 0)
         return 0;

      $Bytes = $Megabyte * pow(1024, 2);
      $Bytes = (int)ceil($Bytes);

      return $Bytes;
   }

   /**
    * Converts a number expressed in milliseconds into the corresponding number
    * of bytes which is dependent by the data rate of the wav current file.
    *
    * @param int $Milliseconds The number of milliseconds to convert
    * @param int $DataRate The data rate of the current wav file
    * @return int The corrisponding number of bytes
    */
   public static function millisecondsToByte($Milliseconds, $DataRate)
   {
      $Byte = (int) ($Milliseconds * $DataRate / 1000);
      if ($Byte % 2 != 0)
         $Byte++;

      return $Byte;
   }
}

?>
