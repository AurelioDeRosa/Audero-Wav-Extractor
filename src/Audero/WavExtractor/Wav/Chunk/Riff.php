<?php
namespace Audero\WavExtractor\Wav\Chunk;

/**
 * The class of the Riff chunk.
 * More info here: http://www.sonicspot.com/guide/wavefiles.html
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
 * @package Audero\Audero\Wav\Chunk
 * @author  Aurelio De Rosa <aurelioderosa@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link    https://bitbucket.org/AurelioDeRosa/audero-wav-extractor
 */
class Riff extends \Audero\WavExtractor\Wav\Chunk
{
    const ID = 0x52494646;
    const BYTES_NOT_COUNTED = 8;

    /**
     * The format of the file. This property should always contains
     * as value the string "WAVE"
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $format;

    /**
     * The default constructor
     */
    public function __construct()
    {
        parent::__construct(self::ID);
        $this->format = new \Audero\WavExtractor\Wav\ChunkField('H8', 4);
    }

    /**
     * Get the format of the wav file
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the format of the wav file
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $format The format of the wav file
     *
     * @return Riff
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
}
