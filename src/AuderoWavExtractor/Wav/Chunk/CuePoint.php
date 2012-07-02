<?php

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
 * @author     Aurelio De Rosa <aureliodersa@gmail.com>
 * @version    1.0
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 * @package    Audero\AuderoWavExtractor
 */
class CuePoint
{

   /**
    *
    * @var ChunkField
    */
   private $Id;

   /**
    *
    * @var ChunkField
    */
   private $Position;

   /**
    *
    * @var ChunkField
    */
   private $DataChunkId;

   /**
    *
    * @var ChunkField
    */
   private $ChunkStart;

   /**
    *
    * @var ChunkField
    */
   private $BlockStart;

   /**
    *
    * @var ChunkField
    */
   private $SampleOffset;

   /**
    * The default constructor
    */
   function __construct()
   {
      $this->Id = new ChunkField('V', 4);
      $this->Position = new ChunkField('V', 4);
      $this->DataChunkId = new ChunkField('V', 4);
      $this->ChunkStart = new ChunkField('V', 4);
      $this->BlockStart = new ChunkField('V', 4);
      $this->SampleOffset = new ChunkField('V', 4);
   }

   /**
    *
    * @return ChunkField
    */
   public function getId()
   {
      return $this->Id;
   }

   /**
    *
    * @param ChunkField $Id
    */
   public function setId($Id)
   {
      $this->Id = $Id;
   }

   /**
    *
    * @return ChunkField
    */
   public function getPosition()
   {
      return $this->Position;
   }

   /**
    *
    * @param ChunkField $Position
    */
   public function setPosition($Position)
   {
      $this->Position = $Position;
   }

   /**
    *
    * @return ChunkField
    */
   public function getDataChunkId()
   {
      return $this->DataChunkId;
   }

   /**
    *
    * @param ChunkField $DataChunkId
    */
   public function setDataChunkId($DataChunkId)
   {
      $this->DataChunkId = $DataChunkId;
   }

   /**
    *
    * @return ChunkField
    */
   public function getChunkStart()
   {
      return $this->ChunkStart;
   }

   /**
    *
    * @param ChunkField $ChunkStart
    */
   public function setChunkStart($ChunkStart)
   {
      $this->ChunkStart = $ChunkStart;
   }

   /**
    *
    * @return ChunkField
    */
   public function getBlockStart()
   {
      return $this->BlockStart;
   }

   /**
    *
    * @param ChunkField $BlockStart
    */
   public function setBlockStart($BlockStart)
   {
      $this->BlockStart = $BlockStart;
   }

   /**
    *
    * @return ChunkField
    */
   public function getSampleOffset()
   {
      return $this->SampleOffset;
   }

   /**
    *
    * @param ChunkField $SampleOffset
    */
   public function setSampleOffset($SampleOffset)
   {
      $this->SampleOffset = $SampleOffset;
   }

   /**
    * Read the data inside the managed file to fill the properties of the
    * current chunk.
    *
    * @param resource $Handle The handle of the managed file
    *
    * @return int The amount of bytes read
    */
   public function readData($Handle)
   {
      $TotalBytesRead = 0;
      $Class = new ReflectionClass($this);
      foreach ($Class->getProperties(ReflectionProperty::IS_PRIVATE) as $Property)
      {
         $BytesRead = fread($Handle, $this->{$Property->name}->getBytes());
         $TotalBytesRead += $this->{$Property->name}->getBytes();
         $Result = unpack($this->{$Property->name}->getFormat(), $BytesRead);
         $this->{$Property->name}->setValue(array_shift($Result));
      }

      return $TotalBytesRead;
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
      foreach ($Class->getProperties(ReflectionProperty::IS_PRIVATE) as $Property)
         $String .= pack($this->{$Property->name}->getFormat(), $this->{$Property->name}->getValue());

      return $String;
   }

   /**
    *
    * @return int
    */
   public function getSize()
   {
      $Size = 0;
      $Class = new ReflectionClass($this);
      foreach ($Class->getProperties(ReflectionProperty::IS_PRIVATE) as $Property)
         $Size += $this->{$Property->name}->getBytes();

      return $Size;
   }
}

?>
