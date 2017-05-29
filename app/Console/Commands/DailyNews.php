<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\ConcatPdf;
use App\Mail\pdfmail;
use App\User;
use Mail;

class DailyNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SendMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily Newsletter to clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
            // send mails by chunks

            foreach ($users as $user) {
                $paths = [];
                $titles = [];
                $news = DB::table('news')->where('doc_user_id', '=', $user->id)
                                         ->get();
                //echo $news;
                if($news->isNotEmpty()) {
                foreach ($news as $new) {
                    array_push($paths, $new->doc_path);
                    array_push($titles, $new->doc_title);
                }
         $pdf = new ConcatPdf;
            $pdf->SetFont('dejavusans', '', 12);       
         $pdf->setFiles($paths);
        $pdf->addPage();
        //$pdf->SetFont('Times','',12);

        $pdf->Cell(200,10,'Table Of Contents',0,1,'C');
        $pdf->Cell(5,5,'',0,1,'L',false);
        $pdf->concat2();
                //$pdf->useTemplate($tplIdx);
        $pdf->SetTitle('newsLetter ITELSYS');
        //$pdf->SetKeywords('Fed, simo harbouj');
        //$page = 1; 
        $number = ceil(count($paths)/24);

         $start=$number+1;
        for ($pageNo = 1; $pageNo <= count($paths); $pageNo++) { // nombre de documents
            $pageCount = $pdf->setSourceFile($paths[$pageNo-1]);
            //$pageCount = $pdf->setSourceFile($value);

            if ($pageNo == 1){
                $pdf->links[$pageNo]['p'] = $start;
                $startpage = $pageCount + $start;
            }
            else {
                $pdf->links[$pageNo]['p'] = $startpage;
                 $startpage = $startpage + $pageCount; 
            }

            if (isset($pdf->links[$pageNo])) {     
              //$convertedString = iconv('ISO-8859-1', 'UTF-8//IGNORE',);
             // $strp_txt = iconv('UTF-8', 'windows-1252', $titles[$pageNo-1]);
                $pdf->Cell(180,5,$titles[$pageNo-1],0,0,'L',false,$pageNo);


                $pdf->Cell(5,5,$pdf->links[$pageNo]['p'],0,1,'R',false); 
                $pdf->Cell(5,5,'',0,1,'L',false);
            }
        }

                //print_r($pdf->links);
                $pdf->concat();
                $data = $pdf->Output('concat.pdf', 'S');
                \Mail::to($user)->send(new pdfmail($data));
        
            }
           }
           DB::table('news')->truncate();
    }
}
