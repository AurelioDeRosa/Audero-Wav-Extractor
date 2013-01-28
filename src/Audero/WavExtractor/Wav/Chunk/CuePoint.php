<?php
namespace Audero\WavExtractor\Wav\Chunk;

/**
 * The class that maps the Cue Points which is part of the Cue chunk.
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
class CuePoint
{

    /**
     * The id of the point
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $id;

    /**
     * The position that this point has into the set of the Cue points
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $position;

    /**
     *
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $dataChunkId;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $chunkStart;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $blockStart;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $sampleOffset;

    /**
     * The default constructor
     */
    public function __construct()
    {
        $this->id = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->position = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->dataChunkId = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->chunkStart = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->blockStart = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->sampleOffset = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
    }

    /**
     * Get the id of the point
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id of the point
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getDataChunkId()
    {
        return $this->dataChunkId;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $dataChunkId
     */
    public function setDataChunkId($dataChunkId)
    {
        $this->dataChunkId = $dataChunkId;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getChunkStart()
    {
        return $this->chunkStart;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $chunkStart
     */
    public function setChunkStart($chunkStart)
    {
        $this->chunkStart = $chunkStart;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getBlockStart()
    {
        return $this->blockStart;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $blockStart
     */
    public function setBlockStart($blockStart)
    {
        $this->blockStart = $blockStart;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getSampleOffset()
    {
        return $this->sampleOffset;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $sampleOffset
     */
    public function setSampleOffset($sampleOffset)
    {
        $this->sampleOffset = $sampleOffset;
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
        foreach ($class->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            $bytesRead = fread($handle, $this->{$property->name}->getBytes());
            $totalBytesRead += $this->{$property->name}->getBytes();
            $result = unpack($this->{$property->name}->getFormat(), $bytesRead);
            $this->{$property->name}->setValue(array_shift($result));
        }

        return $totalBytesRead;
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
        foreach ($class->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            $string .= pack($this->{$property->name}->getFormat(), $this->{$property->name}->getValue());
        }

        return $string;
    }

    /**
     *
     * @return int
     */
    public function getSize()
    {
        $size = 0;
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            $size += $this->{$property->name}->getBytes();
        }

        return $size;
    }
}
