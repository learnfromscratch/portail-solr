<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use App\Http\Controllers\HomeController;

class notify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch the new files added to a folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\Solarium\Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $loop = \React\EventLoop\Factory::create();
        $inotify = new \MKraemer\ReactInotify\Inotify($loop);

        $finder = new Finder();

        $finder->directories()->in(base_path().'/public/Articles');
        $inotify->add(base_path().'/public/Articles', IN_CLOSE_WRITE | IN_CREATE | IN_DELETE | IN_MOVE);
        foreach ($finder as $dir) {
            $inotify->add($dir.'/', IN_CLOSE_WRITE | IN_CREATE | IN_DELETE | IN_MOVE);
        }

        $inotify->on(IN_CREATE, function ($path) {
            
            $doc1 = [];
            $buffer = $this->client->getPlugin('bufferedadd');
            $buffer->setBufferSize(50); 
            if (strtolower(substr($path, strrpos($path, '.') + 1)) == 'xml') 
            {  
                sleep(1);         
                $xml=simplexml_load_file($path) or die("Error: Cannot create object");

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
                $id_doc = preg_replace(['/_/', '/\s+/'], '', basename($path, '.xml'));
                $doc1['id'] = $id_doc;
                $pdfname = (string) $xml->Record->Document['name'];
            
                $pdfpath = dirname($path)."/".$pdfname;
                $doc1["document"] = $pdfpath;
                if ($doc1['ArticleLanguage'] == 'ENGLISH') {
                    $doc1['Fulltext_en'] = $doc1['Fulltext'];
                    $doc1['Title_en'] = $doc1['Title'];
                }
                if ($doc1['ArticleLanguage'] == 'FRENCH') {
                    $doc1['Fulltext_fr'] = $doc1['Fulltext'];
                    $doc1['Title_fr'] = $doc1['Title'];
                }
                if ($doc1['ArticleLanguage'] == 'Arabic') {
                    $doc1['Fulltext_ar'] = $doc1['Fulltext'];
                    $doc1['Title_ar'] = $doc1['Title'];
                }
                
                unset($doc1['Fulltext']);
                unset($doc1['Title']);
                $buffer->createDocument($doc1);
                //array_push($notif, $doc1['id']);
                
                $buffer->commit();
                HomeController::notifyUser($id_doc,$this->client);
                echo 'File created: '.$path.PHP_EOL;
            }

            
        });
        $loop->run();
    }
}
