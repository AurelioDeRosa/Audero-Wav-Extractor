<?php
namespace Audero\WavExtractor\Wav\Chunk;

/**
 * The class of the Fmt chunk.
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
class Fmt extends \Audero\WavExtractor\Wav\Chunk
{

    const ID = 0x666D7420;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $compressionCode;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $channelsNumber;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $sampleRate;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $dataRate;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $blockSize;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $bitsPerSample;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $extensionSize;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $validBitsPerSample;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $channelMask;

    /**
     *
     * @var \Audero\WavExtractor\Wav\ChunkField
     */
    private $subFormat;

    /**
     * The default constructor
     */
    public function __construct()
    {
        parent::__construct(self::ID);
        $this->compressionCode = new \Audero\WavExtractor\Wav\ChunkField('v', 2);
        $this->channelsNumber = new \Audero\WavExtractor\Wav\ChunkField('v', 2);
        $this->sampleRate = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->dataRate = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->blockSize = new \Audero\WavExtractor\Wav\ChunkField('v', 2);
        $this->bitsPerSample = new \Audero\WavExtractor\Wav\ChunkField('v', 2);
        $this->extensionSize = new \Audero\WavExtractor\Wav\ChunkField('v', 2);
        $this->validBitsPerSample = new \Audero\WavExtractor\Wav\ChunkField('v', 2);
        $this->channelMask = new \Audero\WavExtractor\Wav\ChunkField('V', 4);
        $this->subFormat = new \Audero\WavExtractor\Wav\ChunkField('H32', 16);
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getCompressionCode()
    {
        return $this->compressionCode;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $compressionCode
     *
     * @return Fmt
     */
    public function setCompressionCode($compressionCode)
    {
        $this->compressionCode = $compressionCode;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getChannelsNumber()
    {
        return $this->channelsNumber;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $channelsNumber
     *
     * @return Fmt
     */
    public function setChannelsNumber($channelsNumber)
    {
        $this->channelsNumber = $channelsNumber;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getSampleRate()
    {
        return $this->sampleRate;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $sampleRate
     *
     * @return Fmt
     */
    public function setSampleRate($sampleRate)
    {
        $this->sampleRate = $sampleRate;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getDataRate()
    {
        return $this->dataRate;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $dataRate
     *
     * @return Fmt
     */
    public function setDataRate($dataRate)
    {
        $this->dataRate = $dataRate;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getBlockSize()
    {
        return $this->blockSize;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $blockSize
     *
     * @return Fmt
     */
    public function setBlockSize($blockSize)
    {
        $this->blockSize = $blockSize;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getBitsPerSample()
    {
        return $this->bitsPerSample;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $bitsPerSample
     *
     * @return Fmt
     */
    public function setBitsPerSample($bitsPerSample)
    {
        $this->bitsPerSample = $bitsPerSample;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getExtensionSize()
    {
        return $this->extensionSize;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $extensionSize
     *
     * @return Fmt
     */
    public function setExtensionSize($extensionSize)
    {
        $this->extensionSize = $extensionSize;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getValidBitsPerSample()
    {
        return $this->validBitsPerSample;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $validBitsPerSample
     *
     * @return Fmt
     */
    public function setValidBitsPerSample($validBitsPerSample)
    {
        $this->validBitsPerSample = $validBitsPerSample;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getChannelMask()
    {
        return $this->channelMask;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $channelMask
     *
     * @return Fmt
     */
    public function setChannelMask($channelMask)
    {
        $this->channelMask = $channelMask;

        return $this;
    }

    /**
     *
     * @return \Audero\WavExtractor\Wav\ChunkField
     */
    public function getSubFormat()
    {
        return $this->subFormat;
    }

    /**
     *
     * @param \Audero\WavExtractor\Wav\ChunkField $subFormat
     *
     * @return Fmt
     */
    public function setSubFormat($subFormat)
    {
        $this->subFormat = $subFormat;

        return $this;
    }
}
