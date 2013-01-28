<?php
namespace Audero\WavExtractor\Wav;

/**
 * This class represents the value of a chunk. Although a value in the chunks
 * is always a string, which of course can also represent an integer, I created
 * a whole class to keep track of other info about the value. In fact, every value
 * has potentially a different format (used by pack and unpack function of PHP
 * to extract the value - more info here: http://php.net/manual/en/function.pack.php)
 * and size (in bytes).
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
 * @package Audero\Audero\Wav
 * @author  Aurelio De Rosa <aurelioderosa@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link    https://bitbucket.org/AurelioDeRosa/auderowavextractor
 */
class ChunkField
{
    /**
     * The format of the field
     *
     * @var string
     */
    private $format;

    /**
     * The size in bytes of the field
     *
     * @var int
     */
    private $bytes;

    /**
     * The value of the field
     *
     * @var mixed
     */
    private $value;

    /**
     * The default constructor
     *
     * @param string $format The format of the field
     * @param int    $bytes  The size of the field
     */
    public function __construct($format, $bytes)
    {
        $this->setFormat($format);
        $this->setBytes($bytes);
    }

    /**
     * Get the format of the field
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the format of the field
     *
     * @param string $format The format of the field
     *
     * @return void
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Get the size of the field
     *
     * @return int
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * Set the size of the field
     *
     * @param int $bytes The size of the field
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the value isn't positive or integer
     */
    public function setBytes($bytes)
    {
        if (is_int($bytes) && $bytes >= 0) {
            $this->bytes = $bytes;
        } else {
            throw new \InvalidArgumentException(
                'The number of bytes must be a positive integer. Value provided: ' . $bytes
            );
        }
    }

    /**
     * Get the value of the field
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of the field
     *
     * @param mixed $value The value of the field
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
