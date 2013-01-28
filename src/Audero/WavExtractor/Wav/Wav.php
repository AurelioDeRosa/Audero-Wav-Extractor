<?php
namespace Audero\WavExtractor\Wav;

use Audero\WavExtractor\Wav\Chunk;
use Audero\Utility\Converter;

/**
 * This class maps the current analyzed wav file. It has the methods to extract
 * the chunk and the data from the file.
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
class Wav
{
    /**
     * The filename, including the path, of the managed wav file
     *
     * @var string
     */
    private $filePath;

    /**
     * An array containing the headers of the wav file
     *
     * @var array
     */
    private $headers;

    /**
     * The size in byte of the wav file
     *
     * @var int
     */
    private $headersSize;

    /**
     * The constructor of the object. It initialized the object and set
     * the filename of the wav file.
     *
     * @param string $filePath The filename, including the path, of the managed file wav
     */
    public function __construct($filePath)
    {
        $this->setFilePath($filePath);
    }

    /**
     * Retrieves the path to the current file
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set the path to the wav file that has to be managed
     *
     * @param string $filePath The filename, including the path, of the managed file wav
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the file does not exist or if the file
     * does not appear to be a wav.
     */
    public function setFilePath($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException('The file does not exists: ' . $filePath);
        } else {
            $this->filePath = $filePath;
            $this->headers = $this->getHeaders();

            if (empty($this->headers[Chunk\Riff::ID])
                || Converter::hexToString(
                    $this->headers[Chunk\Riff::ID]->getFormat()->getValue()
                ) !== 'WAVE'
            ) {
                $this->filePath = null;
                $this->headers = null;
                throw new \InvalidArgumentException('The file does not appear to be a wav: ' . $filePath);
            }
        }
    }

    /**
     * Get the headers of the current wav file. If the headers contains not recognize
     * chunks, these will be drop.
     *
     * @param int &$headerSize The amount of bytes of the current wav headers (including dropped chunks)
     *
     * @return array The array that contains the headers (excluding dropped chunks)
     *
     * @throws \Exception If the wav file is not available or cannot be open.
     */
    public function getHeaders(&$headerSize = 0)
    {
        // If the headers have already been retrieved
        if ($this->headers != null) {
            $headerSize = $this->headersSize;

            return $this->headers;
        }

        $headerSize = 0;
        $file = @fopen($this->getFilePath(), 'r');
        if ($file === false) {
            throw new \Exception('Unable to open the file: ' . $this->getFilePath());
        }

        $id = null;
        $chunks = array();
        $chunk = null;
        // While the library is not retrieving the actual wav data and the file is
        // not end, retrieve the headers and fill the structure which will contains
        // all the information about the wav file
        while (!feof($file)
            && strcasecmp(get_class($chunk), get_class(new \Audero\WavExtractor\Wav\Chunk\Data())) !== 0
        ) {
            $bytesRead = fread($file, Chunk::ID_SIZE);
            $result = unpack(Chunk::ID_FORMAT, $bytesRead);
            $id = '0x' . array_shift($result);

            $chunk = Chunk::getChunkType($id);
            fseek($file, ftell($file) - Chunk::ID_SIZE);
            if (strcasecmp(get_class($chunk), get_class(new Chunk())) === 0) {
                $headerSize += $chunk->readData($file);
                $headerSize += $chunk->getSize()->getValue();
                // Drop the extra bytes in the header chunk and update the chunk size
                if ($chunk->getSize()->getValue() > 0) {
                    fread($file, $chunk->getSize()->getValue());
                }
            } else {
                $headerSize += $chunk->readData($file);
                $chunks[hexdec($id)] = $chunk;
            }
        }

        fclose($file);
        unset($file);

        $this->headersSize = $headerSize;

        return $chunks;
    }

    /**
     * Get the headers size in byte
     *
     * @param bool $onlyRecognizedChunks If the value is FALSE (default), the method will
     *                              calculate the size of the actual headers. This means that, if there were
     *                              any dropped chunks, these will be counted. Otherwise, the method returns just
     *                              the bytes of the recognized chunks.
     *
     * @return int The number of bytes
     */
    public function getHeadersSize($onlyRecognizedChunks = true)
    {
        $bytes = 0;
        if ($onlyRecognizedChunks === false) {
            if ($this->headers === null) {
                $this->getHeaders($bytes);

                return $bytes;
            } else {
                return $this->headersSize;
            }
        }

        foreach ($this->headers as $header) {
            $bytes += $header->getChunkSize();
        }

        return $bytes;
    }

    /**
     * Convert the array that contain the headers in a string
     *
     * @return string
     */
    public function headersToString()
    {
        $string = '';
        foreach ($this->headers as $chunk) {
            $string .= $chunk->toString();
        }

        return $string;
    }

    /**
     * Retrieves the number of channels of the current file.
     *
     * @return int|null
     */
    public function getChannelsNumber()
    {
        return $this->headers[Chunk\Fmt::ID]->getChannelsNumber()->getValue();
    }

    /**
     * Retrieves the number of sample slices per second.
     *
     * @return int|null
     */
    public function getDataRate()
    {
        return $this->headers[Chunk\Fmt::ID]->getDataRate()->getValue();
    }

    /**
     * Set the size of the data chunk.
     *
     * @param int $size The size of the data chunk
     *
     * @return void
     */
    public function setDataChunkSize($size)
    {
        $this->headers[Chunk\Riff::ID]->getSize()->setValue(
            $size + $this->getHeadersSize() - Chunk\Riff::BYTES_NOT_COUNTED
        );
        $this->headers[Chunk\Data::ID]->getSize()->setValue($size);
    }

    /**
     * This method get the current file duration
     *
     * @return int The duration of the file in milliseconds
     */
    public function getDuration()
    {
        $Milliseconds = $this->headers[Chunk\Fmt::ID]->getSampleRate()->getValue();
        $Milliseconds *= $this->headers[Chunk\Fmt::ID]->getBitsPerSample()->getValue() / 8;
        $Milliseconds *= $this->headers[Chunk\Fmt::ID]->getChannelsNumber()->getValue();
        $Milliseconds = ($this->headers[Chunk\Riff::ID]->getSize()->getValue() -
            $this->getHeadersSize()) / $Milliseconds;
        $Milliseconds = (int)ceil($Milliseconds * 1000);

        return $Milliseconds;
    }

    /**
     * Extract the portion from the original audio
     *
     * @param int $start The time in milliseconds from which to start
     * @param int $end   The time in milliseconds from which to end
     *
     * @return string
     *
     * @throws \Exception If the file wav is not available or cannot be open.
     */
    public function getWavChunk($start, $end)
    {
        $fromByte = Converter::millisecondsToByte($start, $this->getDataRate());
        $toByte = Converter::millisecondsToByte($end, $this->getDataRate());

        $fileInput = @fopen($this->getFilePath(), 'r');
        if ($fileInput === false) {
            throw new \Exception('Unable to open the file ' . $this->getFilePath());
        }

        $position = $this->getHeadersSize(false) + $fromByte;
        fseek($fileInput, $position);
        $result = fread($fileInput, $toByte - $fromByte);
        fclose($fileInput);

        return $result;
    }
}
