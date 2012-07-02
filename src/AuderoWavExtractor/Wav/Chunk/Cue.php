<?php

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
 * @author     Aurelio De Rosa <aureliodersa@gmail.com>
 * @version    1.0
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 * @package    Audero\AuderoWavExtractor
 */
class Cue extends Chunk
{
   const ID = 0x63756520;

   /**
    * The number of Cue Points in the wav file
    *
    * @var ChunkField
    */
   private $CuePointsNumber;

   /**
    *
    * @var array
    */
   private $CuePoints;

   /**
    * The default constructor
    */
   function __construct()
   {
      parent::__construct(self::ID);
      $this->CuePointsNumber = new ChunkField('V', 4);
      $this->CuePointsNumber->setValue(0);
      $this->CuePoints = array();
   }

   /**
    * Gets the number of Cue Points in the wav file
    *
    * @return ChunkField
    */
   public function getCuePointsNumber()
   {
      return $this->CuePointsNumber;
   }

   /**
    * Sets the number of Cue Points in the wav file
    *
    * @param ChunkField $CuePointsNumber
    */
   public function setCuePointsNumber($CuePointsNumber)
   {
      $this->CuePointsNumber = $CuePointsNumber;
   }

   /**
    *
    * @return array
    */
   public function getCuePoints()
   {
      return $this->CuePoints;
   }

   /**
    *
    * @param array $CuePoints
    */
   public function setCuePoints($CuePoints)
   {
      $this->CuePoints = $CuePoints;
   }

   /**
    * Push a new CuePoint into the current set of CuePoints
    *
    * @param CuePoint $CuePoint
    */
   public function addCuePoint($CuePoint)
   {
      array_push($this->CuePoints, $CuePoint);
   }

   /**
    * Retrieves actual number of CuePoints
    *
    * @param int $CueSize
    *
    * @return int
    */
   private function calculateCuePointsNumber($CueSize)
   {
      $CuePoint = new CuePoint();
      return ($CueSize - 4) / $CuePoint->getSize();
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

      $String .= pack($this->CuePointsNumber->getFormat(), $this->CuePointsNumber->getValue());
      foreach($this->CuePoints as $CuePoint)
         $String .= $CuePoint->toString();

      return $String;
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
      foreach ($Class->getProperties(ReflectionProperty::IS_PROTECTED) as $Property)
      {
         $BytesRead = fread($Handle, $this->{$Property->name}->getBytes());
         $TotalBytesRead += $this->{$Property->name}->getBytes();
         $Result = unpack($this->{$Property->name}->getFormat(), $BytesRead);
         $this->{$Property->name}->setValue(array_shift($Result));
      }

      $TotalBytesRead += $this->CuePointsNumber->getBytes();
      // Read the bytes which contain the number of cue points. Because this data could be wrong,
      // the calculation of the actual number of cue points is done based on the chunk size
      fread($Handle, $this->CuePointsNumber->getBytes());
      $this->CuePointsNumber->setValue($this->calculateCuePointsNumber($this->Size->getValue()));
      for($i = 0; $i < $this->CuePointsNumber->getValue(); $i++)
      {
         $CuePoint = new CuePoint();
         $TotalBytesRead += $CuePoint->readData($Handle);
         $this->addCuePoint($CuePoint);
      }

      return $TotalBytesRead;
   }

   /**
    *
    * @return int
    */
   public function getChunkSize()
   {
      $Size = 0;
      $Class = new ReflectionClass($this);
      foreach ($Class->getProperties(ReflectionProperty::IS_PROTECTED) as $Property)
         $Size += $this->{$Property->name}->getBytes();

      $Size += $this->CuePointsNumber->getBytes();
      foreach($this->CuePoints as $CuePoint)
         $Size += $CuePoint->getSize();

      return $Size;
   }
}
?>
