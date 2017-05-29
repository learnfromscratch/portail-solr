<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

/**
* 
*/
class Articles
{
	protected $client;
	
	function __construct($client, $params, $start)
	{
		$this->client = $client;
		$this->params = $params;
		$this->start = ($start - 1)*10;
	}


	public function init()
	{
		// get the connected User
		$user = Auth::user();

		// construct the query for searcg
    	$querySearched = "";

		foreach($user->keywords()->get() as $keyword)
		{
			$querySearched .= '"'.$keyword->name.'" ';
		}

		$keywordsquery = "";
		$Textfields = ['Title','Title_en', 'Title_fr', 'Title_ar' ,'Fulltext','Fulltext_en','Fulltext_fr', 'Fulltext_ar'];

		foreach ($Textfields as $value) {
		  $keywordsquery .= $value.":(".$querySearched.") ";
		}

		// get a select query instance
		$query = $this->client->createSelect();
		$query->createFilterQuery('filterkeywords')->setQuery($keywordsquery);
		
		$query->setFields([
			'id',
			'Title','Title_en','Title_fr', 'Title_ar',
			'Fulltext','Fulltext_en','Fulltext_fr','Fulltext_ar',
			'document', 'Source','ArticleLanguage', 'SourceDate','Author','score']);


		// get the dismax component and set a boost query
		

		$helper = $query->getHelper();
		if (isset($this->params['data']))
		{
			//$query->setQuery($helper->escapePhrase($_GET['searchterm']));
    	$edismax = $query->getEDisMax();
			$thequery = "";
			
			foreach ($Textfields as $value) {
		  		$thequery .= $value.":".$this->params['data']." ";
			}
			//dd($thequery);
			$query->setQuery($thequery);
			$datesss = date("Y-m-d");
			//dd($datesss);
    		$datesssm = $datesss."T00:00:00Z";

    		//$edismax->setQueryFields("Fulltext_ar^2 Fulltext_fr^2 Fulltext_en^4 Fulltext^2 Title^3 Title_en^3 Title_fr^3 Title_ar^3");
    		//$edismax->setBoostQuery('SourceDate:"'.$datesssm.'"^5');
    		//dd($edismax);
			//dd($edismax);
			
		}

		$query->setStart($this->start)->setRows(10);


		// get the facetset component
		$facetSet = $query->getFacetSet();
		$facetSet->createFacetField('language')->setField('ArticleLanguage');
		$facetSet->createFacetField('author')->setField('Author');
		$facetSet->createFacetField('source')->setField('Source');
		$facetSet->createFacetField('date')->setField('SourceDate');
		$facetSet->setMinCount(1);

		// Get highlighting component, and apply settings
		$hl = $query->getHighlighting();
		$hl->setSnippets(5);
		$hl->setFields(array('Title_en', 'Title_fr','Title_ar','Fulltext_en','Fulltext_fr','Fulltext_ar','Fulltext', 'Title'));

		$hl->setSimplePrefix('<strong>');
		$hl->setSimplePostfix('</strong>');
		if (isset($this->params['language'])) {
			$query->createFilterQuery('language')->setQuery('ArticleLanguage:"'.$helper->escapePhrase($this->params['language']).'"');
		}
		if (isset($this->params['source'])) {
			$query->createFilterQuery('source')->setQuery('Source:'.$helper->escapePhrase($this->params['source']));
		}
		if (isset($this->params['author'])) {
			$query->createFilterQuery('author')->setQuery('Author:'.$helper->escapePhrase(urldecode($this->params['author'])));
			//dd($query->getQuery());

			//dd('Author:'.$helper->escapeTerm($this->params['author']));
		}
		if (isset($this->params['keyword'])) {
			$key = $helper->escapePhrase($this->params['keyword']);
			$query->createFilterQuery('keyword')->setQuery('Title:'.$key.' Fulltext_en:'.$key.' Fulltext_fr:'.$key.' Fulltext_ar:'.$key.' Fulltext:'.$key.' Title_fr:'.$key.' Title_en:'.$key.' Title_ar:'.$key);
		}

		if (isset($this->params['sort']) and $this->params['sort'] == "date") {
			$query->addSort('SourceDate', $query::SORT_DESC);
		}
		if (isset($this->params['sort']) and $this->params['sort'] == "pertinence") {
			$query->addSort('score', $query::SORT_DESC);
		}

		if (empty($this->params['data']))
			$query->addSort('SourceDate', $query::SORT_DESC);
		
		if (isset($this->params['fromdate']) and isset($this->params['todate'])) {
			$fromdate = $this->params['fromdate'].'T00:00:00Z';
			$todate = $this->params['todate'].'T00:00:00Z';
			$query->createFilterQuery('fromdate')->setQuery('SourceDate:['.$fromdate.' TO '.$todate.']');
		}
		return $query;

	}


	/**
	 * @param Illuminate\Http\Request $request
	 * @return array $resultset
	*/
	public function index()
	{
		$query = Articles::init();

		// this executes the query and returns the result
		return $resultset = $this->client->select($query);
	}


	public function show($request)
	{
		$query = Articles::init();
		$helper = $query->getHelper();

		$thequery = 'id:"'.$request.'"';
		$query->setQuery($thequery);

		// this executes the query and returns the result
		return $resultset = $this->client->select($query);	
	}

}