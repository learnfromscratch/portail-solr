<?php

namespace App\Repositories;

/**
* 
*/
class Solarium
{
	protected $client;
	
	function __construct(\Solarium\Client $client)
	{
        $this->client = $client;
	}


	/**
	 * 
	 * @return void
	*/
	public function indexing()
	{
        $dir = './Articles';
        $directory = array_diff(scandir($dir), array('..', '.', '.DS_Store', 'time.txt'));

        $buffer = $this->client->getPlugin('bufferedadd');
        $buffer->setBufferSize(1000); // this is quite low, in most cases you can use a much higher value
        $doc1 = [];
        $filetime = "./Articles/time.txt";

        $date = file_get_contents($filetime);
        $j=0;
        $storedTime = 0;
        $keywordGroups = [];
        foreach ($directory as $files1) {
            $path = $dir.'/'.$files1;
            $dir1 = array_diff(scandir($path), array('..', '.DS_Store', '.'));
            foreach ($dir1 as $file) {

                // $base = basename($file, ".xml");

                $newtime = filectime($path."/".$file);
                if ($newtime > $storedTime)
                    $storedTime = $newtime;
                // we dont need this part anymore : && $base != $files1 
                if (strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml' && $newtime > $date ) 
                {           
                    $xml=simplexml_load_file($path."/".$file) or die("Error: Cannot create object");
                    //echo "Fichier indexe".$j++."<br>";
                    $j++;
                    $i = 0;
                    foreach($xml->Record->Field as $keys => $value) {
                        $key = (string) $xml->Record->Field[$i]['name'];
                        $value = (string) $xml->Record->Field[$i];
                        if ($key == 'RevDate' || $key == 'SourceDate') {
                            $time = strtotime($value);
                            $newformat = date('Y-m-d',$time);
                            $value =  $newformat;
                        }         
                        $doc1[$key] = $value;
                        $i++;
                    }
                    $id_doc = preg_replace(['/_/', '/\s+/'], '', basename($file, '.xml'));
                    $doc1['id'] = $id_doc;
                    $pdfname = (string) $xml->Record->Document['name'];
                    $pdfpath = "/".$files1."/".$pdfname;
                    $doc1["document"] = $pdfpath;
                    $buffer->createDocument($doc1);
                }
            }   
        }
        file_put_contents($filetime, $storedTime);
        $buffer->commit();
	}


    /**
     * 
     * @return int notIndexed
    */
    public function notIndexed()
    {
        $dir = './Articles';
        $directory = array_diff(scandir($dir), array('..', '.DS_Store', '.', 'time.txt'));
        $date = file_get_contents("./Articles/time.txt");
        $notIndexed = 0;
        foreach ($directory as $files1) {
            $path = $dir.'/'.$files1;
            $dir1 = array_diff(scandir($path), array('..', '.DS_Store', '.'));
            
            foreach ($dir1 as $file) {
                //$base = basename($file, ".xml");
                $newtime = filectime($path."/".$file);
                if (strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml' 
                && $newtime > $date ) 
                {
                    $notIndexed++;
                }
            }   
        }

        return $notIndexed;
    }

}