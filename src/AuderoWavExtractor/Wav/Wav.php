<?php

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
 * @author     Aurelio De Rosa <aureliodersa@gmail.com>
 * @version    1.0
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 * @package    Audero\AuderoWavExtractor
 */
class Wav
{
   /**
    * The filename, including the path, of the managed file wav
    *
    * @var string
    */
   private $FilePath;

   /**
    * An array containing the headers of the wav file
    *
    * @var array
    */
   private $Headers;

   /**
    * The size in byte of the wav file
    *
    * @var int
    */
   private $HeadersSize;

   /**
    * This method is used to load all the needed classes to let this class works.
    */
   private function autoload()
   {
      $Files = glob(realpath(dirname(__FILE__)) . '\Chunk\*.php');
      $Files = array_merge($Files, glob(realpath(dirname(__FILE__)) . '..\Utility\*.php'));
      foreach ($Files as $File)
         require_once ($File);
   }

   /**
    * The constructor of the object. It initialized the object and set
    * the filename of the wav file.
    *
    * @param string The filename, including the path, of the managed file wav
    */
   function __construct($FilePath)
   {
      $this->autoload();
      $this->setFilePath($FilePath);
   }

   /**
    * Retrieves the path to the current file
    *
    * @return string
    */
   public function getFilePath()
   {
      return $this->FilePath;
   }

   /**
    * Set the path to the wav file that has to be managed
    *
    * @param string $FilePath
    *
    * @throws InvalidArgumentException If the file does not exist or if the file
    * does not appear to be a wav.
    */
   public function setFilePath($FilePath)
   {
      if (!file_exists($FilePath))
         throw new InvalidArgumentException('The file does not exists: ' . $FilePath);
      else
      {
         $this->FilePath = $FilePath;
         $this->Headers = $this->getHeaders();

         if (empty($this->Headers[Riff::ID]) || Converter::hexToString($this->Headers[Riff::ID]->getFormat()->getValue()) !== 'WAVE')
         {
            $this->FilePath = NULL;
            $this->Headers = NULL;
            throw new InvalidArgumentException('The file does not appear to be a wav: ' . $FilePath);
         }
      }
   }

   /**
    * Get the headers of the current wav file. If the headers contains not recognize
    * chunks, these will be drop.
    *
    * @param int $HeaderSize The amount of bytes of the current wav headers (including dropped chunks)
    *
    * @return array The array that contains the headers (excluding dropped chunks)
    *
    * @throws Exception If the wav file is not available or cannot be open.
    */
   public function getHeaders(&$HeaderSize = 0)
   {
      // If the headers have already been retrieved
      if ($this->Headers != NULL)
      {
         $HeaderSize = $this->HeadersSize;
         return $this->Headers;
      }

      $HeaderSize = 0;
      $File = @fopen($this->getFilePath(), 'r');
      if ($File === FALSE)
         throw new Exception('Unable to open the file: ' . $this->getFilePath());

      $Id = NULL;
      $Chunks = array();
      $Type = NULL;
      // While the library is not retriving the actual wav data and the file is
      // not end, retrieve the headers and fill the structure which will contains
      // all the information about the wav file
      while (!feof($File) && $Type != 'Data')
      {
         $BytesRead = fread($File, Chunk::ID_SIZE);
         $Result = unpack(Chunk::ID_FORMAT, $BytesRead);
         $Id = '0x' . array_shift($Result);

         $Type = Chunk::getChunkType($Id);
         // If the chunk type is not recognized
         if ($Type == NULL)
            $Type = 'Chunk';

         fseek($File, ftell($File) - Chunk::ID_SIZE);
         $Chunk = new $Type();
         if ($Type == 'Chunk')
         {
            $HeaderSize += $Chunk->readData($File);
            $HeaderSize += $Chunk->getSize()->getValue();
            // Drop the extra bytes in the header chunk and update the chunk size
            if ($Chunk->getSize()->getValue() > 0)
               fread($File, $Chunk->getSize()->getValue());
         }
         else
         {
            $HeaderSize += $Chunk->readData($File);
            $Chunks[hexdec($Id)] = $Chunk;
         }
      }

      fclose($File);
      unset($File);

      $this->HeadersSize = $HeaderSize;

      return $Chunks;
   }

   /**
    * Get the headers size in byte
    *
    * @param $OnlyRecognizedChunks If the value is FALSE (default), the method will
    * calculate the size of the actual headers. This means that, if there were
    * any dropped chunks, these will be counted. Otherwise, the method returns just
    * the bytes of the recognized chunks.
    *
    * @return int The number of bytes
    */
   public function getHeadersSize($OnlyRecognizedChunks = TRUE)
   {
      $Bytes = 0;
      if ($OnlyRecognizedChunks === FALSE)
      {
         if ($this->Headers === NULL)
         {
            $this->getHeaders($Bytes);
            return $Bytes;
         }
         else
            return $this->HeadersSize;
      }

      foreach ($this->Headers as $Header)
         $Bytes += $Header->getChunkSize();

      return $Bytes;
   }

   /**
    * Convert the array that contain the headers in a string
    *
    * @return string
    */
   public function headersToString()
   {
      $String = '';
      foreach ($this->Headers as $Chunk)
         $String .= $Chunk->toString();

      return $String;
   }

   /**
    * Retrieves the number of channles of the current file.
    *
    * @return int|null
    */
   public function getChannelsNumber()
   {
      return $this->Headers[Fmt::ID]->getChannelsNumber()->getValue();
   }

   /**
    * Retrieves the number of sample slices per second.
    *
    * @return int|null
    */
   public function getDataRate()
   {
      return $this->Headers[Fmt::ID]->getDataRate()->getValue();
   }

   /**
    * Set the size of the data chunk.
    *
    * @param int $Size
    */
   public function setDataChunkSize($Size)
   {
      $this->Headers[Riff::ID]->getSize()->setValue($Size + $this->getHeadersSize() - Riff::BYTES_NOT_COUNTED);
      $this->Headers[Data::ID]->getSize()->setValue($Size);
   }

   /**
    * This method get the current file duration
    *
    * @return int The duration of the file in milliseconds
    */
   public function getDuration()
   {
      $Milliseconds = $this->Headers[Fmt::ID]->getSampleRate()->getValue();
      $Milliseconds *= $this->Headers[Fmt::ID]->getBitsPerSample()->getValue() / 8;
      $Milliseconds *= $this->Headers[Fmt::ID]->getChannelsNumber()->getValue();
      $Milliseconds = ($this->Headers[Riff::ID]->getSize()->getValue() - $this->getHeadersSize()) / $Milliseconds;
      $Milliseconds = (int) ceil($Milliseconds * 1000);

      return $Milliseconds;
   }

   /**
    * Extract the portion from the original audio
    *
    * @param int $Start The time in milliseconds from which to start
    * @param int $End The time in milliseconds from which to end
    *
    * @return string
    *
    * @throws Exception If the file wav is not available or cannot be open.
    */
   public function getWavChunk($Start, $End)
   {
      $FromByte = Utility::millisecondsToByte($Start, $this->getDataRate());
      $ToByte = Utility::millisecondsToByte($End, $this->getDataRate());

      $FileInput = @fopen($this->getFilePath(), 'r');
      if ($FileInput === FALSE)
         throw new Exception('Unable to open the file ' . $this->getFilePath());

      $Position = $this->getHeadersSize(FALSE) + $FromByte;
      fseek($FileInput, $Position);
      $Result = fread($FileInput, $ToByte - $FromByte);
      fclose($FileInput);

      return $Result;
   }
}

?>