<?php

require_once 'Chunk.php';

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
 * @author     Aurelio De Rosa <aureliodersa@gmail.com>
 * @version    1.0
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 * @package    Audero\AuderoWavExtractor
 */
class Fmt extends Chunk
{

   const ID = 0x666D7420;

   /**
    *
    * @var ChunkField
    */
   private $CompressionCode;

   /**
    *
    * @var ChunkField
    */
   private $ChannelsNumber;

   /**
    *
    * @var ChunkField
    */
   private $SampleRate;

   /**
    *
    * @var ChunkField
    */
   private $DataRate;

   /**
    *
    * @var ChunkField
    */
   private $BlockSize;

   /**
    *
    * @var ChunkField
    */
   private $BitsPerSample;

   /**
    *
    * @var ChunkField
    */
   private $ExtentionSize;

   /**
    *
    * @var ChunkField
    */
   private $ValidBitsPerSample;

   /**
    *
    * @var ChunkField
    */
   private $ChannelMask;

   /**
    *
    * @var ChunkField
    */
   private $Subformat;

   /**
    * The default constructor
    */
   function __construct()
   {
      parent::__construct(self::ID);
      $this->CompressionCode = new ChunkField('v', 2);
      $this->ChannelsNumber = new ChunkField('v', 2);
      $this->SampleRate = new ChunkField('V', 4);
      $this->DataRate = new ChunkField('V', 4);
      $this->BlockSize = new ChunkField('v', 2);
      $this->BitsPerSample = new ChunkField('v', 2);
      $this->ExtentionSize = new ChunkField('v', 2);
      $this->ValidBitsPerSample = new ChunkField('v', 2);
      $this->ChannelMask = new ChunkField('V', 4);
      $this->Subformat = new ChunkField('H32', 16);
   }

   /**
    *
    * @return ChunkField
    */
   public function getCompressionCode()
   {
      return $this->CompressionCode;
   }

   /**
    *
    * @param ChunkField $CompressionCode
    */
   public function setCompressionCode($CompressionCode)
   {
      $this->CompressionCode = $CompressionCode;
   }

   /**
    *
    * @return ChunkField
    */
   public function getChannelsNumber()
   {
      return $this->ChannelsNumber;
   }

   /**
    *
    * @param ChunkField $ChannelsNumber
    */
   public function setChannelsNumber($ChannelsNumber)
   {
      $this->ChannelsNumber = $ChannelsNumber;
   }

   /**
    *
    * @return ChunkField
    */
   public function getSampleRate()
   {
      return $this->SampleRate;
   }

   /**
    *
    * @param ChunkField $SampleRate
    */
   public function setSampleRate($SampleRate)
   {
      $this->SampleRate = $SampleRate;
   }

   /**
    *
    * @return ChunkField
    */
   public function getDataRate()
   {
      return $this->DataRate;
   }

   /**
    *
    * @param ChunkField $DataRate
    */
   public function setDataRate($DataRate)
   {
      $this->DataRate = $DataRate;
   }

   /**
    *
    * @return ChunkField
    */
   public function getBlockSize()
   {
      return $this->BlockSize;
   }

   /**
    *
    * @param ChunkField $BlockSize
    */
   public function setBlockSize($BlockSize)
   {
      $this->BlockSize = $BlockSize;
   }

   /**
    *
    * @return ChunkField
    */
   public function getBitsPerSample()
   {
      return $this->BitsPerSample;
   }

   /**
    *
    * @param ChunkField $BitsPerSample
    */
   public function setBitsPerSample($BitsPerSample)
   {
      $this->BitsPerSample = $BitsPerSample;
   }

   /**
    *
    * @return ChunkField
    */
   public function getExtentionSize()
   {
      return $this->ExtentionSize;
   }

   /**
    *
    * @param ChunkField $ExtentionSize
    */
   public function setExtentionSize($ExtentionSize)
   {
      $this->ExtentionSize = $ExtentionSize;
   }

   /**
    *
    * @return ChunkField
    */
   public function getValidBitsPerSample()
   {
      return $this->ValidBitsPerSample;
   }

   /**
    *
    * @param ChunkField $ValidBitsPerSample
    */
   public function setValidBitsPerSample($ValidBitsPerSample)
   {
      $this->ValidBitsPerSample = $ValidBitsPerSample;
   }

   /**
    *
    * @return ChunkField
    */
   public function getChannelMask()
   {
      return $this->ChannelMask;
   }

   /**
    *
    * @param ChunkField $ChannelMask
    */
   public function setChannelMask($ChannelMask)
   {
      $this->ChannelMask = $ChannelMask;
   }

   /**
    *
    * @return ChunkField
    */
   public function getSubformat()
   {
      return $this->Subformat;
   }

   /**
    *
    * @param ChunkField $Subformat
    */
   public function setSubformat($Subformat)
   {
      $this->Subformat = $Subformat;
   }

   /**
    * Converts the current chunk into a string based on the values of its properties
    *
    * @return string
    */
   public function toString()
   {
      $String = '';
      $Class = new ReflectionClass($this);
      foreach ($Class->getProperties(ReflectionProperty::IS_PROTECTED) as $Property)
         $String .= pack($this->{$Property->name}->getFormat(), $this->{$Property->name}->getValue());

      foreach ($Class->getProperties(ReflectionProperty::IS_PRIVATE) as $Property)
      {
         if ($this->{$Property->name}->getValue() !== NULL)
            $String .= pack($this->{$Property->name}->getFormat(), $this->{$Property->name}->getValue());
      }

      return $String;
   }

   /**
    * Read the data inside the managed file to fill the properties of the
    * current chunk.
    *
    * @param resource $Handle
    *
    * @return int The amount of bytes read
    */
   public function readData($Handle)
   {
      $Class = new ReflectionClass($this);
      $TotalBytes = $this->getChunkSize();
      foreach ($Class->getProperties(ReflectionProperty::IS_PROTECTED) as $Property)
      {
         $DataRead = fread($Handle, $this->{$Property->name}->getBytes());
         $Result = unpack($this->{$Property->name}->getFormat(), $DataRead);
         $this->{$Property->name}->setValue(array_shift($Result));
      }

      $BytesLeft = $this->Size->getValue();
      foreach ($Class->getProperties(ReflectionProperty::IS_PRIVATE) as $Property)
      {
         $BytesLeft -= $this->{$Property->name}->getBytes();

         $DataRead = fread($Handle, $this->{$Property->name}->getBytes());
         $Result = unpack($this->{$Property->name}->getFormat(), $DataRead);
         $this->{$Property->name}->setValue(array_shift($Result));

         if ($BytesLeft == 0)
            break;
         else if ($BytesLeft < 0)
            throw new Exception('The file does not appear to be a valid wav');
      }

      return $TotalBytes;
   }
}

?>
