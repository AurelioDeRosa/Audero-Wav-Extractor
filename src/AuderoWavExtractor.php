<?php

/**
 * Description of AuderoWavExtractor
 *
 * PHP versions 4 and 5
 *
 * The follow is an example of how to extract a chunk from a wav file and force the
 * download using this class:
 * <code>
 * <?php
 *    require_once('AuderoWavExtractor.php');
 * 
 *    $InputFile = 'audio.wav';
 *    $OutputFile = 'chunk.wav';
 *    $Start = 0 * 1000;
 *    $End = 20 * 1000;
 * 
 *    $Extractor = new AuderoWavExtractor($InputFile);
 *    $Extractor->extractChunk($Start, $End);
 * ?>
 * </code>
 * 
 * The follow is an example of how to extract a chunk from a wav file and save it
 * into the local disk:
 * <code>
 * <?php
 *    require_once('AuderoWavExtractor.php');
 * 
 *    $InputFile = 'audio.wav';
 *    $OutputFile = 'chunk.wav';
 *    $Start = 0 * 1000;
 *    $End = 20 * 1000;
 * 
 *    $Extractor = new AuderoWavExtractor($InputFile);
 *    $Extractor->extractChunk($Start, $End, 2, $OutputFile);
 * ?>
 * </code>
 * 
 * LICENSE: Permission is granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * @author     Aurelio De Rosa <aureliodersa@gmail.com>
 * @version    0.2
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
 * @link       https://bitbucket.org/AurelioDeRosa/auderowavextractor
 */

require_once 'Converter.php';

class AuderoWavExtractor
{
   /**
    * A set of constant to define the metadata in the wav format.
    */
   const CHUNK_ID = 'ChunkId';
   const CHUNK_SIZE= 'ChunkSize';
   const CHUNK_FORMAT = 'ChunkFormat';
   const SUBCHUNK1_ID = 'SubChunk1Id';
   const SUBCHUNK1_SIZE = 'SubChunk1Size';
   const AUDIO_FORMAT = 'AudioFormat';
   const CHANNELS_NUMBER = 'ChannelsNumber';
   const SAMPLE_RATE = 'SampleRate';
   const DATA_RATE = 'DataRate';
   const BLOCK_SIZE = 'BlockSize';
   const BITS_PER_SAMPLE = 'BitsPerSample';
   const SUBCHUNK2_ID = 'SubChunk2Id';
   const SUBCHUNK2_SIZE = 'SubChunk2Size';

   const BYTES_NOT_COUNTED = 8;

   /**
    * 
    * @var string The filename, including the path, of the managed file wav
    */
   private $FilePath;

   /**
    * Sets the filename
    *
    * @param string The filename, including the path, of the managed file wav
    */
   function __construct($FilePath)
   {
      $this->setFilePath($FilePath);
   }

   public function getFilePath()
   {
      return $this->FilePath;
   }

   public function getFilename($IncludeExtention = TRUE)
   {
      $Filename = $this->getFilePath();
      $Start = strrpos($Filename, '/');
      if ($Start !== FALSE)
         $Filename = substr($Filename, $Start + 1);

      if ($IncludeExtention === FALSE)
      {
         $Start = strrpos($Filename, '.');
         if ($Start !== FALSE)
            $Filename = substr($Filename, 0, $Start);
      }

      return $Filename;
   }

   /**
    *
    * @param type $FilePath 
    */
   public function setFilePath($FilePath)
   {
      if (! file_exists($FilePath))
         throw new InvalidArgumentException('The file does not exists: ' . $FilePath);
      else if (filesize($FilePath) < $this->getHeadersSize())
         throw new InvalidArgumentException('The file is too small to be a wav: ' . $FilePath);
      else
      {
         $this->FilePath = $FilePath;
         
         $Headers = $this->getHeaders();
         if ( Converter::hexToString($Headers[self::CHUNK_ID]) !== 'RIFF' ||
              Converter::hexToString($Headers[self::CHUNK_FORMAT]) !== 'WAVE'
            )
         {
            $this->FilePath = NULL;
            throw new InvalidArgumentException('The file does not appear to be a wav: ' . $FilePath);
         }
      }
   }

   /**
    * Get the headers of the current wav file
    * 
    * @return array The array that contains the metadata
    */
   public function getHeaders()
   {
      $Fields = $this->getHeaderFields();
      $File = @fopen($this->getFilePath(), 'r');
      if ($File === false)
         throw new Exception('Unable to open the file: ' . $this->getFilePath());

      $Info = array();
      foreach ($Fields as $Key => $Elem)
      {
         $BytesRead = fread($File, $Elem['bytes']);
         $Result = unpack($Elem['format'], $BytesRead);
         $Info[$Key] = array_shift($Result);
      }
      fclose($File);
      unset($File);

      return $Info;
   }

   /**
    * This method get the current file duration
    * 
    * @return int The duration of the file in milliseconds
    */
   public function getDuration()
   {
      $Header = $this->getHeaders();

      $Milliseconds = $Header[self::SAMPLE_RATE];
      $Milliseconds *= ($Header[self::BITS_PER_SAMPLE] / 8);
      $Milliseconds *= $Header[self::CHANNELS_NUMBER];      
      $Milliseconds = ($Header[self::CHUNK_SIZE] - $this->getHeadersSize()) / $Milliseconds;
      $Milliseconds *= 1000;
      
      unset($Header);

      return $Milliseconds;
   }

   /**
    * Extract a portion of the current, from the $Start to $End. The chunk will be
    * saved, with name $Filename, in the local server, the browser or the user and
    * so on based on the value of $Destination.
    * 
    * The possible value of $Destination are:
    * <ul>
    *    <li>
    *       <b>1</b>: send the file to the browser and force the download with the
    *       name given by $Filename.
    *    </li>
    *    <li>
    *       <b>2</b>: save into the local disk the file with the name given by $Filename.
    *    </li>
    *    <li>
    *       <b>3</b>: return the wav as a string. In this case $Filename is ignored.
    *    </li>
    *    <li>
    *       <b>4</b>: both 2 and 3
    *    </li>
    * </ul>
    * 
    * @param int $Start The start time, in milliseconds, of the portion to extract
    * @param int $End The end time, in milliseconds, of the portion to extract
    * @param int $Destination Where to save the extracted chunk
    * @param string &$Filename The name to assing to the chunk. If no name is provided
    * the method generate one using this rule: "InputFilename-Start-End.wav"
    * @return string The chunk to extract, including the headers
    */
   public function extractChunk($Start, $End, $Destination = 1, &$Filename = '')
   {
      $Start = (int)$Start;
      $End = (int)$End;
      if ( $Start < 0 || $Start > $this->getDuration() ||
           $End < 0 || $End > $this->getDuration() ||
           $Start > $End
         )
      {
         throw new InvalidArgumentException('Invalid chunk boundaries');
      }

      $Destination = (int)$Destination;
      if ($Destination < 1 || $Destination > 4)
         throw new InvalidArgumentException('Invalid Destination value');

      if (empty($Filename))
         $Filename = $this->getFilename(FALSE) . "-$Start-$End.wav";

      if ($this->isEnoughMemory($Start, $End) === FALSE)
         throw new RuntimeException('Not enough memory to save the given range');

      $Headers = $this->getHeaders();
      $Headers[self::SUBCHUNK2_SIZE] = ($End - $Start) * $Headers[self::DATA_RATE] / 1000;
      $Headers[self::SUBCHUNK2_SIZE] = (int)$Headers[self::SUBCHUNK2_SIZE];
      $Headers[self::CHUNK_SIZE] = $Headers[self::SUBCHUNK2_SIZE] + $this->getHeadersSize() - self::BYTES_NOT_COUNTED;

      $Chunk = $this->headersToString($Headers);
      $Chunk .= $this->getWavChunk($Start, $End);

      switch ($Destination)
      {
         case 1:
            $this->downloadChuck($Chunk, $Filename);
            unset($Chunk);
            $Chunk = NULL;
            break;
         case 2:
            $this->saveChunk($Chunk, $Filename);
            unset($Chunk);
            $Chunk = NULL;
            break;
         case 3:
            break;
         case 4:
            $this->saveChunk($Chunk, $Filename);
            break;
      }

      return $Chunk;
   }

   /**
    *
    * 
    * @param int $Milliseconds
    * @param int $DataRate
    * @return int 
    */
   public function millisecondsToByte($Milliseconds, $DataRate = NULL)
   {
      if (! is_int($DataRate))
      {
         $Headers = $this->getHeaders();
         $DataRate = $Headers[self::DATA_RATE];
         unset($Headers);
      }         
      $Byte = (int)($Milliseconds * $DataRate / 1000);
      if ($Byte % 2 != 0)
         $Byte++;

      return $Byte;
   }

   /**
    *
    * 
    * @return array 
    */
   private function getHeaderFields()
   {
      $Fields = array();

      $Fields[self::CHUNK_ID] = array('format' => 'H8', 'bytes' => 4);
      $Fields[self::CHUNK_SIZE] = array('format' => 'V', 'bytes' => 4);
      $Fields[self::CHUNK_FORMAT] = array('format' => 'H8', 'bytes' => 4);
      $Fields[self::SUBCHUNK1_ID] = array('format' => 'H8', 'bytes' => 4);
      $Fields[self::SUBCHUNK1_SIZE] = array('format' => 'V', 'bytes' => 4);
      $Fields[self::AUDIO_FORMAT] = array('format' => 'v', 'bytes' => 2);
      $Fields[self::CHANNELS_NUMBER] = array('format' => 'v', 'bytes' => 2);
      $Fields[self::SAMPLE_RATE] = array('format' => 'V', 'bytes' => 4);
      $Fields[self::DATA_RATE] = array('format' => 'V', 'bytes' => 4);
      $Fields[self::BLOCK_SIZE] = array('format' => 'v', 'bytes' => 2);
      $Fields[self::BITS_PER_SAMPLE] = array('format' => 'v', 'bytes' => 2);
      $Fields[self::SUBCHUNK2_ID] = array('format' => 'H8', 'bytes' => 4);
      $Fields[self::SUBCHUNK2_SIZE] = array('format' => 'V', 'bytes' => 4);
      
      return $Fields;
   }

   /**
    * Get the headers size in byte
    * 
    * @return int The number of bytes
    */
   private function getHeadersSize()
   {
      $Bytes = 0;
      $Fields = $this->getHeaderFields();   
      foreach ($Fields as $Elem)
         $Bytes += $Elem['bytes'];

      return $Bytes;
   }

   /**
    * Convert the array that contain the headers in a string
    * 
    * @param array $Headers The wav headers
    * @return string 
    */
   private function headersToString($Headers)
   {
      $Fields = $this->getHeaderFields();
      $String = '';
      foreach($Headers as $Key => $Elem)
         $String .= pack($Fields[$Key]['format'], $Elem);

      return $String;
   }

   /**
    * Extract the portion from the original audio
    * 
    * @param int $Start The time in milliseconds from which to start
    * @param int $End The time in milliseconds from which to end
    * @return string 
    */
   private function getWavChunk($Start, $End)
   {
      $FromByte = $this->millisecondsToByte($Start);
      $ToByte = $this->millisecondsToByte($End);

      $FileInput = @fopen($this->getFilePath(), 'r');
      if ($FileInput === FALSE)
         throw new Exception('Unable to open the file ' . $this->getFilePath());
      $Position = $this->getHeadersSize() + $FromByte;
      fseek($FileInput, $Position);
      $Result = fread($FileInput, $ToByte - $FromByte);
      fclose($FileInput);

      return $Result;
   }

   private function downloadChuck($Chunk, $Filename)
   {
      $Output = ob_get_contents();
      if (!empty($Output) || headers_sent() === TRUE )
         ob_clean();

      header('Content-Description: File Transfer');
      header('Cache-Control: public, must-revalidate, max-age=0');
      header('Pragma: public');
      header('Expires: Fri, 06 Nov 1987 12:00:00 GMT');
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
      header('Content-Type: audio/x-wav', false);
      header('Content-Disposition: attachment; filename="' . basename($Filename) . '";');
      header('Content-Transfer-Encoding: binary');
      header('Content-Length: ' . strlen($Chunk));
      echo $Chunk;
   }

   private function saveChunk($Chunk, $Filename)
   {
      if (empty($Chunk))
         throw new InvalidArgumentException('Invalid chunk');

      $File = @fopen($Filename, 'wb');
      if ($File === FALSE)
         throw new Exception('Unable to create the file on the disk');
      fwrite($File, $Chunk);
      fclose($File);
   }

   /**
    *
    * @param int $Start
    * @param int $End
    * @return bool 
    */
   public function isEnoughMemory($Start, $End)
   {
      $FromByte = $this->millisecondsToByte($Start);
      $ToByte = $this->millisecondsToByte($End);

      $MemoryLimit = (int)ini_get('memory_limit');
      $MemoryLimit = Converter::megabyteToByte($MemoryLimit);
      $MemoryUsage = memory_get_usage();
      $ExpectedMemoryAllocation = $this->getHeadersSize() + $ToByte - $FromByte;

      return ($ExpectedMemoryAllocation + $MemoryUsage <= $MemoryLimit);
   }
}

?>
