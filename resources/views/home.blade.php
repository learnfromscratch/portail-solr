@extends('layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/home.css">
   
@endsection

@section('content')
    <div class="container">
        <div class="row col-lg-12">
        
            <form class="search-form" role="search" action="{{ route('roots') }}" method="get">
                <div class="input-group search">
                    <input type="text" value="{{array_key_exists('data',$params) ? $params['data'] : ''}}" name="data" id="data" placeholder="Recherche" class="form-control input-lg" ng-model="name">
                    <a  class="input-group-addon w3-blue"><i class="fa fa-search fa-fw" aria-hidden="true"></i></a>
                </div>
            </form>
        </div>

        <div class="row col-lg-12">
            <hr class="w3-blue w3-text-blue hr-search" />
        </div>

        
        <!-- <h4>{{ __('welcome to itelsys application') }}</h4> -->
        


        <!-- A voir l'optimisation du Code-->
        @if(!empty($params['start']) and count($params) >= 1)
                @php 
                    $params['start'] = 1; 
                @endphp
        @endif
        <!--
        <div class="row w3-padding-4 w3-margin-bottom w3-blue">
            <h3>{{ __('All Articles : ') }}</h3>
           
        </div>
-->
<!--
    <p>Active Filters : <i class="badge"  >English</i><i class="badge"  >Date <a href="#">x</a></i></p>
-->
        <div class="widget">
        <em class="number-articles">{{ __('Number of articles : ') }}<i>{{ $resultset->getNumFound() }} </i></em>
            @if(!empty($params['data']))
            <!-- {{$user_id}}
            {{ route('roots',$request->all()) }}
            -->
            <br><br>
            <select id="comboA" onchange="getComboA(this)" class="form-control">
                @if(!empty($params['sort']) and $params['sort'] == 'pertinence')
                    <option value='pertinence' selected="selected">Relevance</option>
                @elseif(empty($params['sort']) or $params['sort'] != 'pertinence')
                    <option value='pertinence'>Relevance</option>
                @endif

                @if(!empty($params['sort']) and $params['sort'] == 'date')
                    <option value='date' selected="selected">Newest</option>
                @elseif(empty($params['sort']) or $params['sort'] != 'date')
                    <option value='date'>Newest</option>
                @endif
                
            </select>
        @endif
        <!-- This is custom facade, so I have to code everything-->
        <div class="keywords">
            <br><h4>{{ __('Filter by keywords: ') }}</h4><br>
            @foreach($numberss as $key => $count)
                @if (empty($params['keyword']))
                    @if($count > 0)
                    <a href="{{ route('roots', $params) }}{{$sign}}keyword={{$key}}">{{ $key}}</a><i>{{$count}}</i><br>
                    @endif
                @elseif ($params['keyword'] == $key)
                 <p class="actives">{{ $key}}<i>{{$count}}</i></p><br>
                  
                @endif
            @endforeach
        </div> 
        <div class="language">       
            <h4>{{ __('Filter by Language :') }}</h4><br>
            @foreach ($facet1 as $value => $count)
                @if (empty($params['language']))
                <a href="{{ route('roots', $params) }}{{$sign}}language={{$value}}">{{ $value}}<i>{{$count}}</i></a><br>
                @elseif ($params['language'] == $value)
                     <p class="actives">
                    {{ $value}}
                     <a href="#" class="closes"><i class="fa fa-times-circle" aria-hidden="true"></i></a href="#">
                     <i>{{$count}}</i>
                    </p><br>
                @endif
            @endforeach
        </div>
        <div class="source">  
            <h4>{{ __('Filter by Source :') }}</h4><br>
            @foreach ($facet3 as $value => $count)
               @if (empty($params['source']))

                    <a href="{{ route('roots', $params)}}{{$sign}}source={{$value}}">{{ $value}}</a><i>{{$count}}</i><br>
                 @elseif ($params['source'] == $value)
                    <p class="actives">{{ $value}}<i>{{$count}}</i></p><br>
                @endif
            @endforeach
        </div>
        </div>

        
<div class="articles">
<form class="search-form form-date" role="search" action="{{ route('roots') }}" method="get">
    <div class="input-daterange input-group ranges" id="datepicker">
        <input type="text" name="fromdate" class="input-sm form-control" value="{{array_key_exists('fromdate',$params) ? $params['fromdate'] : ''}}"  placeholder="Date du Debut" />
        <span class="input-group-addon">to</span>
        <input type="text" name="todate" class="input-sm form-control" placeholder="Date du Fin" value="{{array_key_exists('todate',$params) ? $params['todate'] : ''}}"/>
    </div>
     <!-- <input type="submit" class="btn btn-primary input-date" value="Afficher Articles">-->
     <button class="btn btn-primary input-date"><i class="fa fa-search fa-fw" aria-hidden="true"></i></button>
</form><br>

        @php 
            $highlighting = $resultset->getHighlighting(); 
            $pdfs = [];
            $titles = [];
        @endphp

        @foreach ($resultset as $document)
            @php
                $highlightedDoc = $highlighting->getResult($document->id);
                $pdf = $document->document;
                $pdf1 = str_replace('/home/harbouj/laravel/app/public', '', $pdf);
               
                $datesolr = substr($document->SourceDate,0,10);
               $timess = strtotime($datesolr);

               $date = date("d-m-Y", $timess);

               if(!empty($document->Title))
                    $title = $document->Title;

              if(!empty($document->Title_en))
                    $title = $document->Title_en;
              if(!empty($document->Title_fr))
                    $title = $document->Title_fr;
            if(!empty($document->Title_ar))
                    $title = $document->Title_ar;
            $class='';
            @endphp

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
                        <a href="{{ route('articles.show', ['id' => $document->id]) }}">
                            {!! (count($highlightedDoc->getField('Title'))) ? implode(' ... ', $highlightedDoc->getField('Title')) : $document->Title !!}
                            {!! (count($highlightedDoc->getField('Title_en'))) ? implode(' ... ', $highlightedDoc->getField('Title_en')) : $document->Title_en !!}
                            {!! (count($highlightedDoc->getField('Title_fr'))) ? implode(' ... ', $highlightedDoc->getField('Title_fr')) : $document->Title_fr !!}
                            {!! (count($highlightedDoc->getField('Title_ar'))) ? implode(' ... ', $highlightedDoc->getField('Title_ar')) : $document->Title_ar !!}
                        </a>

                        </h4>
                        <i>{{ __('Published on ') }}{{ $date }}</i>
                        <p>
                       
                            {!! (count($highlightedDoc->getField('Fulltext'))) ? implode(' ... ', $highlightedDoc->getField('Fulltext')) : substr($document->Fulltext,0,500) !!}
                            {!! (count($highlightedDoc->getField('Fulltext_en'))) ? implode(' ... ', $highlightedDoc->getField('Fulltext_en')) : substr($document->Fulltext_en,0,500) !!}
                            {!! (count($highlightedDoc->getField('Fulltext_fr'))) ? implode(' ... ', $highlightedDoc->getField('Fulltext_fr')) : substr($document->Fulltext_fr,0,500) !!}
                            {!! (count($highlightedDoc->getField('Fulltext_ar'))) ? implode(' ... ', $highlightedDoc->getField('Fulltext_ar')) : substr($document->Fulltext_ar,0,500) !!}
                        </p>
                        <i>{{ __('Source : ') }}{{ $document->Source }}</i><br>
                        
                        <i>{{ __('Author : ') }}<a href="{{ route('roots', ['author' => urlencode($document->Author)]) }}">{{ $document->Author }}</a></i>
                        @if(!empty($params['data']))
                            <br><i>Score {{ $document->score }}</i>
                        @endif
                        <br><a href="{{ $pdf1 }}" target="_blank" class="w3-btn w3-green">{{ __('View PDF') }}</a>
                      
                      </div>
                    </div>

                </div>
                @php 
                    array_push($pdfs, $pdf);
                    array_push($titles, $title);
                    
                @endphp
        @endforeach
          <a href="{{ route('articles.test', ['pdf' => $pdfs, 'titles' => $titles]) }}" target="_blank" class="w3-btn pull-left w3-green download">{{ __('Download PDF') }}</a>
        @php 
        
            $num = $resultset->getNumFound() / 10;
            $num++;
            $endpag = (int) $num;
        @endphp
      

    @php
        $starts = 1;
        if ($num < 10)
            $end = $endpag;
        else
            $end = 10;
        
      
    if(!empty($request->start)) {
        if($request->start - 5 >= 1) {
            $starts = $request->start - 5;
        }

        if ($request->start + 4 >= 10 ) { 
            $end = $request->start + 4;
        } 
        if ($request->start + 4 > $endpag)
            $end = $request->start + ($endpag - $request->start);

        if ($request->start >= (int) $num) {
            $end = $num;
        }

    }
    @endphp
    
  <nav aria-label="Page navigation">
  <ul class="pagination">
    <li>
      <a href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    @for ($i = $starts; $i <= $end; $i++)
            @php $params['start'] = $i; @endphp

            @if($request->start == $i)
                <li class="page-item active">
                    <a class="page-link" href="#">{{$i}} <span class="page-link sr-only">(current)</span></a>
                </li>
            @else 
                <li><a href="{{ route('roots', $params) }}">{{$i}}</a></li>
            @endif

    @endfor
    <li>
      <a href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>

        </div>
      
    </div>

<script>



    function getComboA(selectObject) {
        var value = selectObject.value;
        
          
        //console.log( getParameterByName('data'));

        window.location =  '?data='+getParameterByName('data')+'&sort='+value;
    }
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
}


</script>

@endsection
