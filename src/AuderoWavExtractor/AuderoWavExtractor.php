<?php

require_once 'Utility/Converter.php';
require_once 'Wav/Wav.php';

/**
 * "Audero Wav Extractor" is a PHP library that allows to extract a chunk from a
 * wav file. The extracted chunk of the wav file can be saved on the hard disk,
 * can be forced to be prompted as download by the user's browser, returned
 * as a string for a later processing or a combination of the first and second
 * possibilities. It is very easy to use "Audero Wav Extractor" to extract a piece
 * of audio from a wav file. All you have to do is give the name of the file,
 * the start and the end time to extract (optionally you can provide a name
 * for the extracted chunk).
 *
 * The follow is an example of how to extract a chunk from a wav file and force the
 * download using this class:
 * <pre>
 *    require_once('AuderoWavExtractor/AuderoWavExtractor.php');
 *
 *    $InputFile = 'audio.wav'; // path to input wav file
 *    $OutputFile = 'chunk.wav'; // path to the output chunk
 *    $Start = 0 * 1000; // Start time of the chunk
 *    $End = 20 * 1000; // End time of the chunk
 *
 *    $Extractor = new AuderoWavExtractor($InputFile);
 *    $Extractor->extractChunk($Start, $End);
 * </pre>
 *
 * The follow is an example of how to extract a chunk from a wav file and save it
 * into the local disk:
 * <pre>
 *    require_once('AuderoWavExtractor.php');
 *
 *    $InputFile = 'audio.wav'; // path to input wav file
 *    $OutputFile = 'chunk.wav'; // path to the output chunk
 *    $Start = 12 * 1000; // Start time of the chunk
 *    $End = 20 * 1000; // End time of the chunk
 *
 *    $Extractor = new AuderoWavExtractor($InputFile);
 *    $Extractor->extractChunk($Start, $End, 2, $OutputFile);
 * </pre>
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
class AuderoWavExtractor
{

   /**
    * The current wav file
    *
    * @var Wav
    */
   private $Wav;

   /**
    * This method sets the include paths in order to allow the library to work
    */
   private function init()
   {
      set_include_path(implode(PATH_SEPARATOR,
                      array(
                  realpath(dirname(__FILE__) . '/Wav'),
                  realpath(dirname(__FILE__) . '/Utility'),
                  get_include_path(),
              )));
   }

   /**
    * The constructor of the object. It loads all the required class and create
    * the Wav object that represents the choosen one.
    *
    * @param string The filename, including the path, of the managed file wav
    */
   function __construct($FilePath)
   {
      $this->init();
      $this->Wav = new Wav($FilePath);
   }

   /**
    * Get the instance of the current wav file
    *
    * @return Wav The current wav file
    */
   public function getWav()
   {
      return $this->Wav;
   }

   /**
    * Get the filename (include the path) of the current wav file.
    * If false is passed, the extention of the file is stripped.
    *
    * @param boolean $IncludeExtention Include or not the extention (.wav)
    * of the file. Default is true.
    *
    * @return string The filename of the managed wav file
    */
   private function getFilename($IncludeExtention = TRUE)
   {
      $Filename = $this->Wav->getFilePath();
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
    *
    * @return string The chunk to extract, including the headers
    *
    * @throws InvalidArgumentException If the give boundary is invalid (for example
    * if the end is lower than start) or if the destination value isn't valid
    * (for example greater than 4).
    * @throws RuntimeException If there's not enough memory to save the given range
    */
   public function extractChunk($Start, $End, $Destination = 1, &$Filename = '')
   {
      $Start = (int) $Start;
      $End = (int) $End;
      if ( $Start < 0 || $Start > $this->Wav->getDuration() ||
           $End < 0 || $End > $this->Wav->getDuration() ||
           $Start >= $End
      )
      {
         throw new InvalidArgumentException('Invalid chunk boundaries');
      }

      $Destination = (int) $Destination;
      if ($Destination < 1 || $Destination > 4)
         throw new InvalidArgumentException('Invalid Destination value');

      if (empty($Filename))
         $Filename = $this->getFilename(FALSE) . "-$Start-$End.wav";

      if ($this->isEnoughMemory($Start, $End) === FALSE)
         throw new RuntimeException('Not enough memory to save the given range');

      $Size = ($End - $Start) * $this->Wav->getDataRate() / 1000;
      $Difference = $Size % $this->Wav->getChannelsNumber();
      if ($Difference != 0)
         $Size += $Difference;
      $Size = (int) $Size;

      $this->Wav->setDataChunkSize($Size);

      $Chunk = $this->Wav->headersToString();
      $Chunk .= $this->Wav->getWavChunk($Start, $End);

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
    * This method sets the headers to force the download and send the data
    * of the chunk.
    *
    * @param string $Chunk The string containing the bytes of the chunk
    * @param string $Filename The filename that will be shown by the browser
    */
   private function downloadChuck($Chunk, $Filename)
   {
      // Clear any previuos data sent
      $Output = ob_get_contents();
      if (!empty($Output) || headers_sent() === TRUE)
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

   /**
    * Save the extracted chunk on the harddisk
    *
    * @param string $Chunk The string containing the bytes of the chunk
    * @param string $Filename The filename to use to save the file on the harddisk
    *
    * @throws InvalidArgumentException If the chunk is empty
    * @throws Exception If the library is unable to create the file on the harddisk
    */
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
    * Try to test if the available memory is enough to extract the chunk
    *
    * @param int $Start The start time, in milliseconds, of the chunk that
    * has to be extracted
    * @param int $End The end time, in milliseconds, of the chunk that
    * has to be extracted
    *
    * @return bool True if the memory is enough. False otherwise.
    */
   public function isEnoughMemory($Start, $End)
   {
      $FromByte = Converter::millisecondsToByte($Start, $this->Wav->getDataRate());
      $ToByte = Converter::millisecondsToByte($End, $this->Wav->getDataRate());

      $MemoryLimit = (int) ini_get('memory_limit');
      $MemoryLimit = Converter::megabyteToByte($MemoryLimit);
      $MemoryUsage = memory_get_usage();
      $ExpectedMemoryAllocation = $this->Wav->getHeadersSize() + $ToByte - $FromByte;

      return ($ExpectedMemoryAllocation + $MemoryUsage <= $MemoryLimit);
   }

}

?>
