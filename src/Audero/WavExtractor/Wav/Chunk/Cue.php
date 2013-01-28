<?php
namespace Audero\WavExtractor\Wav\Chunk;

/**
 * The class of the Cue chunk.
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
class Cue extends \Audero\WavExtractor\Wav\Chunk
{
    const ID = 0x63756520;

    /**
     * The number of Cue Points in the wav file
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $cuePointsNumber;

    /**
     * The cue points
     *
     * @var array
     */
    private $cuePoints;

    /**
     * The default constructor
     */
    public function __construct()
    {
        parent::__construct(self::ID);
        $this->cuePointsNumber = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->cuePointsNumber->setValue(0);
        $this->cuePoints = array();
    }

    /**
     * Get the number of Cue Points in the wav file
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getCuePointsNumber()
    {
        return $this->cuePointsNumber;
    }

    /**
     * Set the number of Cue Points in the wav file
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $cuePointsNumber The number of Cue Points in the wav file
     *
     * @return Cue
     */
    public function setCuePointsNumber($cuePointsNumber)
    {
        $this->cuePointsNumber = $cuePointsNumber;

        return $this;
    }

    /**
     * Get the cue points
     *
     * @return array
     */
    public function getCuePoints()
    {
        return $this->cuePoints;
    }

    /**
     * Set the cue points
     *
     * @param array $cuePoints The cue points
     *
     * @return Cue
     */
    public function setCuePoints($cuePoints)
    {
        $this->cuePoints = $cuePoints;

        return $this;
    }

    /**
     * Push a new CuePoint into the current set of Cue points
     *
     * @param CuePoint $cuePoint The Cue point to add
     *
     * @return void
     */
    public function addCuePoint($cuePoint)
    {
        array_push($this->cuePoints, $cuePoint);
    }

    /**
     * Retrieves the actual number of cue points
     *
     * @param int $cueSize The size of the cue points chunk in bytes
     *
     * @return int
     */
    private function calculateCuePointsNumber($cueSize)
    {
        $cuePoint = new CuePoint();

        return ($cueSize - 4) / $cuePoint->getSize();
    }

    /**
     * Converts the current chunk into a string based on the values of its properties
     *
     * @return string
     */
    public function toString()
    {
        $string = '';
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            $string .= pack(
                $this->{$property->name}->getFormat(),
                $this->{$property->name}->getValue()
            );
        }

        $string .= pack($this->cuePointsNumber->getFormat(), $this->cuePointsNumber->getValue());
        foreach ($this->cuePoints as $cuePoint) {
            $string .= $cuePoint->toString();
        }

        return $string;
    }

    /**
     * Read the data inside the managed file to fill the properties of the
     * current chunk.
     *
     * @param resource $handle The handle of the managed file
     *
     * @return int The amount of bytes read
     */
    public function readData($handle)
    {
        $totalBytesRead = 0;
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            $bytesRead = fread($handle, $this->{$property->name}->getBytes());
            $totalBytesRead += $this->{$property->name}->getBytes();
            $result = unpack($this->{$property->name}->getFormat(), $bytesRead);
            $this->{$property->name}->setValue(array_shift($result));
        }

        $totalBytesRead += $this->cuePointsNumber->getBytes();
        // Read the bytes which contain the number of cue points. Because this data could be wrong,
        // the calculation of the actual number of cue points is done based on the chunk size
        fread($handle, $this->cuePointsNumber->getBytes());
        $this->cuePointsNumber->setValue($this->calculateCuePointsNumber($this->size->getValue()));
        for ($i = 0; $i < $this->cuePointsNumber->getValue(); $i++) {
            $cuePoint = new CuePoint();
            $totalBytesRead += $cuePoint->readData($handle);
            $this->addCuePoint($cuePoint);
        }

        return $totalBytesRead;
    }

    /**
     * Calculate the size of the chunk
     *
     * @return int
     */
    public function getChunkSize()
    {
        $size = 0;
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            $size += $this->{$property->name}->getBytes();
        }

        $size += $this->cuePointsNumber->getBytes();
        foreach ($this->cuePoints as $cuePoint) {
            $size += $cuePoint->getSize();
        }

        return $size;
    }
}
