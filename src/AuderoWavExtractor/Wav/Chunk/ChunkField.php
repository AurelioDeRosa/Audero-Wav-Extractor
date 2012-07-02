<?php

/**
 * This class represents the value of a chunk. Although a value in the chunks
 * is always a string, which of course can also represent an integer, I created
 * a whole class to keep track of other info about the value. Infact, every value
 * has potentially a different format (used by pack and unpack function of PHP
 * to extract the value - more info here: http://php.net/manual/en/function.pack.php)
 * and size (in bytes).
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
class ChunkField
{
   /**
    * The format of the field
    *
    * @var string
    */
   private $Format;

   /**
    * The size in bytes of the field
    *
    * @var int
    */
   private $Bytes;

   /**
    * The value of the field
    *
    * @var mixed
    */
   private $Value;

   /**
    * The default constructor
    *
    * @param string $Format The format of the field
    * @param int $Bytes The size of the field
    */
   function __construct($Format, $Bytes)
   {
      $this->setFormat($Format);
      $this->setBytes($Bytes);
   }

   /**
    * Gets the format of the field
    *
    * @return string
    */
   public function getFormat()
   {
      return $this->Format;
   }

   /**
    * Sets the format of the field
    *
    * @param string $Format
    */
   public function setFormat($Format)
   {
      $this->Format = $Format;
   }

   /**
    * Gets the size of the field
    *
    * @return int
    */
   public function getBytes()
   {
      return $this->Bytes;
   }

   /**
    * Sets the size of the field
    *
    * @param int $Bytes
    *
    * @throws InvalidArgumentException If the value isn't positive or integer
    */
   public function setBytes($Bytes)
   {
      if (is_int($Bytes) && $Bytes >= 0)
         $this->Bytes = $Bytes;
      else
         throw new InvalidArgumentException('The number of bytes must be a positive integer. Value provided: ' . $Bytes);
   }

   /**
    * Gets the value of the field
    *
    * @return mixed
    */
   public function getValue()
   {
      return $this->Value;
   }

   /**
    * Sets the value of the field
    *
    * @param mixed $Value
    */
   public function setValue($Value)
   {
      $this->Value = $Value;
   }

}

?>