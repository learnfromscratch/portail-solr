@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/home.css">
@endsection

@section('content')
    <div class="container">
        <div class="row col-lg-12">
            <form class="search-form" role="search" action="{{ route('roots') }}" method="get">
                <div class="input-group search">
                    <input type="text" name="data" id="data" placeholder="Recherche" class="form-control input-lg" ng-model="name">
                    <a  class="input-group-addon w3-blue"><i class="fa fa-search fa-fw" aria-hidden="true"></i></a>
                </div>
            </form>
        </div>

        <div class="row col-lg-12">
            <hr class="w3-blue w3-text-blue hr-search" />
        </div>

        
        <div class="row w3-margin-bottom">
        </div>
        
        
        @php 
        $highlighting = $resultset->getHighlighting(); 
          $array = $resultset->getDocuments();
          $mom = $array[0]->getFields();
        @endphp
       <!--  {{$array[0]->getFields()['id']}}-->
        @foreach ($resultset as $document)

            @php
            
                $highlightedDoc = $highlighting->getResult($document->id);
                $pdf = $document->document;
                $pdf = str_replace('/home/harbouj/laravel/app/public', '', $pdf);
                $datesolr = substr($document->SourceDate,0,10);

                $date = date("d-m-Y", strtotime($datesolr));
                $dateme = date("d-m-Y", strtotime($datesolr." +3 month" ));
                $datenow = date("d-m-Y", strtotime('now'));
                
                $date3mois = new DateTime($dateme);
                $datenows = new DateTime($datenow);

            @endphp
            <!--
            date source : <strong>{{$date}} </strong> <br>
            date source + 3 mois :<strong> {{$dateme}} </strong> <br>
            date du jour :<strong>{{$datenow}}</strong>
            -->
            @php
            if ($datenows > $date3mois) {
                $doc_ar = substr($document->Title_ar,0,300);
                $doc_fr = substr($document->Title_fr,0,300);
                $doc_en = substr($document->Title_en,0,300);
                $doc = substr($document->Title,0,300);

                $text_ar = substr($document->Fulltext_ar,0,300);
                $text_fr = substr($document->Fulltext_fr,0,300);
                $text_en = substr($document->Fulltext_en,0,300);
                $text = substr($document->Fulltext,0,300);
              }
              
              else {
                $doc_ar = $document->Title_ar;
                $doc_fr = $document->Title_fr;
                $doc_en = $document->Title_en;
                $doc = $document->Title;

                $text_ar = $document->Fulltext_ar;
                $text_fr = $document->Fulltext_fr;
                $text_en = $document->Fulltext_en;
                $text = $document->Fulltext;
            }
            $class = '';
            @endphp
               <!-- 
               {{$dateme}} <br>
               {{$date}} 
               -->
                @if($document->ArticleLanguage ==  "Arabic") 
                @php 
                    $class = 'arabic';
                @endphp
            @endif
                <div class="row w3-margin-bottom">
                    <div class="media {{$class}}">
                      <div class="media-left">
                        <img src="https://www.heighpubs.org/articleIcons/0423042031704article-icon[1].png" class="media-object" style="width:90px">
                      </div>
                      <div class="media-body">
                        <h4 class="media-heading w3-xlarge">
                            {{ $doc }}
                            {{ $doc_en }}
                            {{ $doc_fr }}
                            {{ $doc_ar }}
                        </h4>
                        <p>
                            
                            {{ $text }}
                            {{ $text_en }}
                            {{ $text_fr }}
                            {{ $text_ar }}
                        </p>
                        <i>Source : {{ $document->Source }}</i><br>
                        <i>Publié le {{ $date }}</i>
                        <p>
                        Keywords : 
                            @foreach($numbers as $key => $count)
                                <i class="badge"  >{{$key}}({{$count}})</i>
                            @endforeach
                        </p>
                        <a href="{{ $pdf }}" target="_blank" class="w3-btn pull-right w3-green">Visualiser pdf</a>
                      </div>
                    </div>
                </div>
        @endforeach

    </div>
@endsection
