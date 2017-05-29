<?php
namespace App\Services;

class Notification
{
	 public function __construct(\Solarium\Client $client)
    {
        $this->client = $client;
    }

    public function notifyUser($idArticle)
    {
    	/*********/	
    		// we need to send notification to all concerned client not only the authentified one;
    	/********/
        $user = Auth::user();

        foreach($user->keywords() as $keyword)
		{
			$querySearched .= '"'.$keyword->name.'" ';
		}
		$keywordsquery = "";
		$Textfields = ['Title','Title_en', 'Title_fr', 'Title_ar' ,'Fulltext','Fulltext_en','Fulltext_fr', 'Fulltext_ar'];

		foreach ($Textfields as $value) {
		  $keywordsquery .= $value.":(".$querySearched.") ";
		}
		$helper = $query->getHelper();
		// get a select query instance
		$query = $this->client->createSelect();
		$query->createFilterQuery('filterkeywords')->setQuery($keywordsquery);
		$query->createFilterQuery('idfilter')->setQuery('id:'.$helper->escapePhrase($idArticle));
		$resultset = $this->client->select($query);

		if ($resultset->getNumFound() > 0)
			return 'send notification to this user';
		else
			return 'dont send to this user';
    }
}