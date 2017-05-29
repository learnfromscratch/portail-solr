<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Solarium;
use App\Repositories\Articles;
use App\ConcatPdf;
use Illuminate\Support\Facades\Auth;
use App\Services\Notification;
use App\User;
use  Illuminate\Support\Facades\Redis;
use App\Events\Alert;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\Solarium\Client $client)
    {
        $this->middleware('auth');
        $this->middleware('abonnement');
        $this->client = $client;
        
    }

   /* public function test()
    {
        $keywordGroup = KeywordGroup::first();
        Auth::user()->notify(new Newsletter ($keywordGroup));
    }*/

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->start);
      /*
      $id = User::findOrNew(10)->name;
      dd($id);
      */
        $keys = [];
        $counts = [];
        $Textfields = ['Title','Title_en', 'Title_fr', 'Title_ar' ,'Fulltext','Fulltext_en','Fulltext_fr', 'Fulltext_ar'];
        $params = $request->all();
        if (empty($params))
            $sign = "?";
        else
            $sign="&";

        
       if(empty($request->start))
            $start = 1;
        else
            $start = $request->start;

        $folderPdfs = "Articles";

        $resultset = (new Articles($this->client,$params,$start))->index();
        $facet1 = $resultset->getFacetSet()->getFacet('language');
        $facet2 = $resultset->getFacetSet()->getFacet('author');
        $facet3 = $resultset->getFacetSet()->getFacet('source');
        $facet4 = $resultset->getFacetSet()->getFacet('date');

       $query = (new Articles($this->client,$params,$start))->init();
       $helper = $query->getHelper();
        $keywordfacet = [];
        $user = Auth::user();
       
            foreach ($user->keywords as $keyword) {
              $thequery1="";
              foreach ($Textfields as $value) {
               $thequery1 .= $value.":".$helper->escapePhrase($keyword->name)." "; 
            }

              //$query->createFilterQuery($value)->setQuery('Fulltext_fr:'.$helper->escapePhrase($value));
              $query->setQuery($thequery1);
               $resultset1 = $this->client->select($query);
              $count1 = $resultset1->getNumFound();
              
                //echo $count1;
              array_push($keys, $keyword->name);
              array_push($counts, $count1);

              /*
                if ($count1 > 0) {
                  if (empty($_GET['keyword']))
                     echo '<a href="'.$url.$sign.'keyword='.$value1. '">'.$value1 . ' [' . $count1. ']</a><br/>';
                    else
                  echo $value1.' ['.$count1.'] <br>';
               }
               */
            
        }
         $numberss = array_combine($keys, $counts);
         $user_id = Auth::user()->id;
         //dd($resultset);
         //$this->user_id = 
        return view('home', compact('request','resultset', 'folderPdfs', 'facet1', 'facet2', 'facet3','facet4','params','sign','numberss','user_id'));
    }


    public function show(Request $request)
    {
        $folderPdfs = "Articles";
        $keys = [];
        $counts = [];
        $resultset = (new Articles($this->client,null,1))->show($request->id);   
        $user = Auth::user();

        foreach($user->keywords()->get() as $keyword) {
            $countkey = 0;
            $query = $this->client->createSelect();
        
            $query->setFields([
            'Title','Title_en','Title_fr', 'Title_ar',
            'Fulltext','Fulltext_en','Fulltext_fr','Fulltext_ar']);
        

        // get the dismax component and set a boost query
        $edismax = $query->getEDisMax();
        $helper = $query->getHelper();

        $query->createFilterQuery('filterid')->setQuery("id:".$helper->escapePhrase($request->id));
        //$query->setStart(0)->setRows(20);
        $thequery = "";
            $Textfields = ['Title','Title_en', 'Title_fr', 'Title_ar' ,'Fulltext','Fulltext_en','Fulltext_fr', 'Fulltext_ar'];

            foreach ($Textfields as $value) {
                $thequery .= $value.":'".$keyword->name."' ";

            }
            
            $query->setQuery($thequery);
        // Get highlighting component, and apply settings
       $hl = $query->getHighlighting();
        
        $hl->setFields(array('Title_en', 'Title_fr','Title_ar','Fulltext_en','Fulltext_fr','Fulltext_ar','Fulltext', 'Title'));
         $hl->setSnippets(100000); 
         //$hl->setHighlightMultiTerm(true); 
         $hl->setFragSize(2);
         //$hl->setMergeContiguous(true); 
        $hl->setSimplePrefix('<strong>');
        $hl->setSimplePostfix('</strong>');
          
        //$query->setQuery();
            $resultset1 = $this->client->select($query);
            $highlighting = $resultset1->getHighlighting();
            //$resultset1->getDocuments()[0]->getFields()['id']
            $highlightedDoc = $highlighting->getResult($request->id);
            $countkey = 0;
            
            if($highlightedDoc) {
              //dd($highlightedDoc);
            $countkey = count($highlightedDoc->getField('Title')) + count($highlightedDoc->getField('Title_en')) + count($highlightedDoc->getField('Title_fr')) + count($highlightedDoc->getField('Title_ar')) + count($highlightedDoc->getField('Fulltext')) + count($highlightedDoc->getField('Fulltext_en')) + count($highlightedDoc->getField('Fulltext_fr')) + count($highlightedDoc->getField('Fulltext_ar'));
            }

            array_push($keys, $keyword->name);
            array_push($counts, $countkey);

        //$hl->setSimplePrefix('<strong>');
        //$hl->setSimplePostfix('</strong>');
           //$countkey=0;
        }
        $numbers = array_combine($keys, $counts);
        $user_id = Auth::user()->id;
        return view('article', compact('resultset', 'folderPdfs','numbers','user_id'));
    }


    public function test(Request $request)
    {
         $pdf = new ConcatPdf;
         $pdf->SetFont('dejavusans', '', 12);
         $pdf->setFiles($request->pdf);
        $pdf->addPage();
        //$pdf->SetFont('Times','',12);
        //$link = $pdf->AddLink();
        $pdf->Cell(200,10,'Table Of Contents',0,1,'C');
        $pdf->Cell(5,5,'',0,1,'L',false);
        $pdf->concat2();
                //$pdf->useTemplate($tplIdx);
        $pdf->SetTitle('NewsLetter ITELSYS');
        $pdf->SetKeywords('Fed, simo harbouj');
        //$page = 1; 
        $number = ceil(count($request->pdf)/24);
         $start=$number + 1;
        for ($pageNo = 1; $pageNo <= count($request->pdf); $pageNo++) { // nombre de documents
            $pageCount = $pdf->setSourceFile($request->pdf[$pageNo-1]);
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
             // $strp_txt = iconv('UTF-8', 'windows-1252', $request->titles[$pageNo-1]);
                $pdf->Cell(180,5,$request->titles[$pageNo-1],0,0,'L',false,$pageNo);
                $pdf->Cell(5,5,$pdf->links[$pageNo]['p'],0,1,'R',false,$pageNo); 
                $pdf->Cell(5,5,'',0,1,'L',false);
            }
        }

        //print_r($pdf->links);
        $pdf->concat();
        $pdf->Output('concat.pdf', 'I');
    }

    public static function notifyUser($idArticle, $articleTitle, $pdfPath, $client) {
      /****************************************************************************************/ 
        // we need to send notification to all concerned client not only the authentified one;
      /***************************************************************************************/
      $users = User::all();
      $Textfields = ['Title','Title_en', 'Title_fr', 'Title_ar' ,'Fulltext','Fulltext_en','Fulltext_fr', 'Fulltext_ar'];
      $query = $client->createSelect();
      $j=0;
      foreach($users as $user) {
        $j++;
        $querySearched="";
    foreach($user->keywords()->get() as $keyword) 
    {
      $querySearched .= '"'.$keyword->name.'" ';
    }
    $keywordsquery = "";

    foreach ($Textfields as $value) {
      $keywordsquery .= $value.":(".$querySearched.") ";
    }
  
    // get a select query instance
   
      $helper = $query->getHelper();
    $query->createFilterQuery('filterkeywords'.$j)->setQuery($keywordsquery);
    $query->createFilterQuery('idfilter'.$j)->setQuery('id:'.$helper->escapePhrase($idArticle));
    $resultset = $client->select($query);
    $resultset->getNumFound();
    if ($resultset->getNumFound() > 0)
    {
      //Redis::publish('all-channel', json_encode($user));
      //echo $user;

      //insert into table News
      DB::table('news')->insert([
        'doc_title' => $articleTitle,
        'doc_path' => $pdfPath,
        'doc_user_id'=> $user->id
        ]);
      event(new Alert($user));
    }
    }
}
}
