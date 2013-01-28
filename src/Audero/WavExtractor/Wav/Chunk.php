<?php
namespace Audero\WavExtractor\Wav;

/**
 * This is a generic class which represents a chunk. Every chunk which a wav file is
 * composed of, has common features and that is a unique id for its type
 * and a size. Thus in Audero Wav Extractor library every chunk extends this
 * generic class that contains a set of methods which are valid and useful for
 * every extended class.
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
class Chunk
{
    const ID_SIZE = 4;
    const ID_FORMAT = 'H8';

    /**
     * The id of the chunk
     *
     * @var ChunkField
     */
    protected $id;

    /**
     * The size of the chunk
     *
     * @var ChunkField
     */
    protected $size;

    /**
     * Magic method to execute the getter associated to the property name given
     *
     * @param string $name The name of the property
     *
     * @return mixed The result of the invoked method
     *
     * @throws \Exception If the method doesn't exists
     */
    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } else {
            throw new \Exception("The method $methodName doesn't exists.");
        }
    }

    /**
     * The default constructor
     *
     * @param int $id The id of the chunk. If not provided 0 will be used
     */
    public function __construct($id = 0)
    {
        $this->id = new ChunkField(self::ID_FORMAT, self::ID_SIZE);
        $this->id->setValue(dechex($id));
        $this->size = new ChunkField('V', 4);
    }

    /**
     * Get the id of the chunk
     *
     * @return ChunkField
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id of the chunk
     *
     * @param ChunkField $id The id of the chunk
     *
     * @return Chunk
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the size of the chunk
     *
     * @return ChunkField
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the size of the chunk
     *
     * @param ChunkField $size The size of the chunk
     *
     * @return Chunk
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Retrieves the chunk type based on the given id
     *
     * @param string $id The id of the chunk
     *
     * @return Chunk
     */
    public static function getChunkType($id)
    {
        switch (hexdec($id))
        {
            case \Audero\WavExtractor\Wav\Chunk\Data::ID:
                $type = new \Audero\WavExtractor\Wav\Chunk\Data();
                break;
            case \Audero\WavExtractor\Wav\Chunk\Fact::ID:
                $type = new \Audero\WavExtractor\Wav\Chunk\Fact();
                break;
            case \Audero\WavExtractor\Wav\Chunk\Fmt::ID:
                $type = new \Audero\WavExtractor\Wav\Chunk\Fmt();
                break;
            case \Audero\WavExtractor\Wav\Chunk\Riff::ID:
                $type = new \Audero\WavExtractor\Wav\Chunk\Riff();
                break;
            case \Audero\WavExtractor\Wav\Chunk\Cue::ID:
                $type = new \Audero\WavExtractor\Wav\Chunk\Cue();
                break;
            default:
                $type = new self();
                break;
        }

        return $type;
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
            if ($this->{$property->name}->getValue() === null) {
                continue;
            }
            $string .= pack(
                $this->{$property->name}->getFormat(),
                $this->{$property->name}->getValue()
            );
        }

        foreach ($class->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            if ($this->{$property->name}->getValue() === null) {
                continue;
            }
            $string .= pack(
                $this->{$property->name}->getFormat(),
                $this->{$property->name}->getValue()
            );
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
        $totalBytes = 0;
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            $totalBytes += $this->{$property->name}->getBytes();
            $dataRead = fread($handle, $this->{$property->name}->getBytes());
            $result = unpack($this->{$property->name}->getFormat(), $dataRead);
            $this->{$property->name}->setValue(array_shift($result));
        }

        $bytesLeft = $this->size->getValue();
        foreach ($class->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            if ($bytesLeft === 0) {
                break;
            } else if ($bytesLeft < $this->{$property->name}->getBytes()) {
                // Drop the extra bytes
                fread($handle, $bytesLeft);
                break;
            }
            $bytesLeft -= $this->{$property->name}->getBytes();
            $totalBytes += $this->{$property->name}->getBytes();
            $dataRead = fread($handle, $this->{$property->name}->getBytes());
            $result = unpack($this->{$property->name}->getFormat(), $dataRead);
            $this->{$property->name}->setValue(array_shift($result));
        }

        return $totalBytes;
    }

    /**
     * Retrieves the size of the current chunk
     *
     * @return int
     */
    public function getChunkSize()
    {
        $size = 0;
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties(
            \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE
        ) as $property) {
            $size += $this->{$property->name}->getBytes();
        }

        return $size;
    }
}
