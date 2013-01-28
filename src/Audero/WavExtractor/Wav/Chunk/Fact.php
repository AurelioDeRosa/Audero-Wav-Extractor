<?php
namespace Audero\WavExtractor\Wav\Chunk;

/**
 * The class of the Fact chunk.
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
 * @link    https://bitbucket.org/AurelioDeRosa/auderowavextractor
 */
class Fact extends \Audero\WavExtractor\Wav\Chunk
{
    const ID = 0x66616374;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $data;

    /**
     * The default constructor
     */
    public function __construct()
    {
        parent::__construct(self::ID);
        $this->data = new \Audero\WavExtractor\Wav\ChunkField('H8', 4);
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
