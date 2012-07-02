<?php

require_once 'Chunk.php';

/**
 * The class of the Fact chunk.
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
class Fact extends Chunk
{
   const ID = 0x66616374;

   /**
    *
    * @var ChunkField
    */
   private $Data;

   /**
    * The default constructor
    */
   function __construct()
   {
      parent::__construct(self::ID);
      $this->Data = new ChunkField('H8', 4);
   }

   /**
    *
    * @return ChunkField
    */
   public function getData()
   {
      return $this->Data;
   }

   /**
    *
    * @param ChunkField $Data
    */
   public function setData($Data)
   {
      $this->Data = $Data;
   }
}

?>
