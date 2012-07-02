<?php

require_once 'ChunkField.php';

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
 * @author     Aurelio De Rosa <aureliodersa@gmail.com>
 * @version    1.0
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 * @package    Audero\AuderoWavExtractor
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
   protected $Id;

   /**
    * The size of the chunk
    *
    * @var ChunkField
    */
   protected $Size;

   /**
    *
    * @param string $Name
    *
    * @return method
    */
   public function __get($Name)
   {
      $MethodName = 'get' . $Name;
      return $this->$MethodName();
   }

   /**
    * The default constructor
    *
    * @param int $Id  The id of the chunk. If not provided 0 will be used
    */
   function __construct($Id = 0)
   {
      $this->init($Id);
   }

   /**
    * Initialize the chunk setting the Id and the Size properties
    *
    * @param int $Id The id of the chunk
    */
   private function init($Id)
   {
      $this->Id = new ChunkField(self::ID_FORMAT, self::ID_SIZE);
      $this->Id->setValue(dechex($Id));
      $this->Size = new ChunkField('V', 4);
   }

   /**
    * Gets the id of the chunk
    *
    * @return ChunkField
    */
   public function getId()
   {
      return $this->Id;
   }

   /**
    * Sets the id of the chunk
    *
    * @param ChunkField $Id
    */
   public function setId($Id)
   {
      $this->Id = $Id;
   }

   /**
    * Gets the size of the chunk
    *
    * @return ChunkField
    */
   public function getSize()
   {
      return $this->Size;
   }

   /**
    * Sets the size of the chunk
    *
    * @param ChunkField $Size
    */
   public function setSize($Size)
   {
      $this->Size = $Size;
   }

   /**
    * Retrieves the type of the chunk based on the id passed.
    *
    * @param string $Id The id of the chunk
    * @return string|null A string if the chunk is recognized. Null otherwise
    */
   public static function getChunkType($Id)
   {
      $Types = array(
          Data::ID => 'Data',
          Fact::ID => 'Fact',
          Fmt::ID => 'Fmt',
          Riff::ID => 'Riff',
          Cue::ID => 'Cue'
      );

      return isset($Types[hexdec($Id)]) ? $Types[hexdec($Id)] : NULL;
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
         $MethodName = 'get' . $Property->name;
         $String .= pack($this->$MethodName()->getFormat(), $this->$MethodName()->getValue());
      }

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
      $TotalBytes = 0;
      $Class = new ReflectionClass($this);
      foreach ($Class->getProperties(ReflectionProperty::IS_PROTECTED) as $Property)
      {
         $TotalBytes += $this->{$Property->name}->getBytes();

         $DataRead = fread($Handle, $this->{$Property->name}->getBytes());
         $Result = unpack($this->{$Property->name}->getFormat(), $DataRead);
         $this->{$Property->name}->setValue(array_shift($Result));
      }

      foreach ($Class->getProperties(ReflectionProperty::IS_PRIVATE) as $Property)
      {
         $MethodName = 'get' . $Property->name;
         $TotalBytes += $this->$MethodName()->getBytes();

         $DataRead = fread($Handle, $this->$MethodName()->getBytes());
         $Result = unpack($this->$MethodName()->getFormat(), $DataRead);
         $this->$MethodName()->setValue(array_shift($Result));
      }

      return $TotalBytes;
   }

   /**
    * Retrieves the size of the current chunk
    *
    * @return int
    */
   public function getChunkSize()
   {
      $Size = 0;
      $Class = new ReflectionClass($this);
      foreach ($Class->getProperties(ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE) as $Property)
         $Size += $this->{$Property->name}->getBytes();

      return $Size;
   }
}

?>