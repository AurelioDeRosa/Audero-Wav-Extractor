<?php
namespace Audero\WavExtractor\Utility;

/**
 * This class allows to make some conversion and it is used as an utility by
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
 * @package Audero\Utility
 * @author  Aurelio De Rosa <aurelioderosa@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link    https://bitbucket.org/AurelioDeRosa/audero-wav-extractor
 */
class Converter
{
    /**
     * Converts a string composed of hex chars into a string of decimal chars
     *
     * @param string $hex A string of hex chars
     *
     * @return string
     */
    public static function hexToString($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }

        return $string;
    }

    /**
     * Converts a string composed of decimal chars into a string of hex chars
     *
     * @param string $string A string of decimal chars
     *
     * @return string
     */
    public static function stringToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }

        return $hex;
    }

    /**
     * Converts a number expressed in megabyte into one expressed in bytes
     *
     * @param float $megabyte The number to convert
     *
     * @return int The corresponding number of bytes
     */
    public static function megabyteToByte($megabyte)
    {
        if (!is_numeric($megabyte) || $megabyte < 0) {
            return 0;
        }

        $bytes = $megabyte * pow(1024, 2);
        $bytes = (int)ceil($bytes);

        return $bytes;
    }

    /**
     * Converts a number expressed in milliseconds into the corresponding number
     * of bytes which is dependent by the data rate of the wav current file.
     *
     * @param int $milliseconds The number of milliseconds to convert
     * @param int $dataRate     The data rate of the current wav file
     *
     * @return int The corresponding number of bytes
     */
    public static function millisecondsToByte($milliseconds, $dataRate)
    {
        $byte = (int)($milliseconds * $dataRate / 1000);
        if ($byte % 2 != 0) {
            $byte++;
        }

        return $byte;
    }
}
