<?php

class ArticleController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function create($slug=null){
		$data['header'] = View::make('templates/header')->render();
		$data['footer'] = View::make('templates/footer')->render();
		var_dump($slug);
		if(!is_null($slug)){

			$data['in_response']=$slug;
		}

		return View::make('editor',$data);
	}

	public function edit($slug){
		$data['header'] = View::make('templates/header')->render();
		$data['footer'] = View::make('templates/footer')->render();
		$data['article'] = Article::getArticleByUrl($slug);
		return View::make('editor',$data);
	}

	public function view($slug,$code=null){
		$data['header'] = View::make('templates/header')->render();
		$data['footer'] = View::make('templates/footer')->render();
		$data['article'] = Article::getArticleByUrl($slug);
		if(isset($code)){
			$data['code']=$code;
		}
		
		return View::make('article',$data);
	}

	

	public static function addArticle(){
		$input=Input::json();
		$errors=array();
		if(User::loggedIn()){
			if(!$input->get('title')||!$input->get('content')||!$input->get('type')||$input->get('type')>3){
				array_push($errors,'Your article was missing important fields. Please try again');
			}else{
				//check lengths are correct
				$title=$input->get('title');
				$content=$input->get('content');
				$resp=$input->get('in_response');
				$bg_url=$input->get('bg_url');
				if($bg_url){
					$bg_url=substr($bg_url, strpos($bg_url, '(')+1,-1);
				}
				$t_length=true;
				$a_length=true;
				$author='me';
				if($input->get('a_id')){ //check if article exists
					$article=Article::findByCode($input->get('a_id'));
					$article_id=$input->get('a_id');
					$article=$article[0];
					$author=$article->author;

					if($author != Session::get('user') || !User::hasPermission('edit_user_opinions')){
						array_push($errors,'You do not have permission to edit this article');
					}
				}else{
					$article_id=Article::generateId();
					$author=Session::get('user');
				}

				if(strlen($title)<=3){
					array_push($errors,'Please provide a title of adequate length. Titles should be descriptive and informative'); //make this into an errors array
					$t_length=false;
				}

				if(strlen(strip_tags($content))< 5){
					array_push($errors, 'Please provide an article of adequate length. Articles should be long enough to show a considered and knowledgeable response');
					$a_length=false;
				}elseif(strlen(strip_tags($content)) > 4000){
					array_push($errors,'Please provide an article of adequate length. Articles should not exceed 2000 characters');
					$a_length=false;
				}

				//check if the article id in question exists
				if(!count($errors)){

					$dom = new DOMDocument();
				
					libxml_use_internal_errors(true);
					
					if($dom->loadHTML($content)){
						
					}

					$script = $dom->getElementsByTagName('script');

					$remove = [];
					foreach($script as $item){
						$remove[] = $item;
					}

					foreach ($remove as $item){
						$item->parentNode->removeChild($item);
					}

					$content = $dom->saveHTML();
					

					if($dom->loadHTML($content)){
						
					}

					libxml_clear_errors();

					//find the medium-buttons and remove them, if they are there. Cycle through them to find paragraphs and divs etc
					$xpath = new DOMXpath($dom);
					$nodes = $xpath->query( "//div[contains(concat(' ', @class, ' '), 'medium-insert-buttons')]");
					foreach( $nodes as $node) {
					    $node->parentNode->removeChild($node);
					}

					$nodes = $xpath->query( "//p[contains(concat(' ', @class, ' '), 'medium-insert-active')]");
					foreach( $nodes as $node) {
					    $node->parentNode->removeChild($node);
					}

					$content=$dom->saveHTML();
					//var_dump(htmlentities($content));
					$c_array=self::htmltoArray($content);

					$article_array=array('title'=>$title,'content'=>$c_array,'author'=>$author,'in_response'=>$resp,'bg_url'=>$bg_url,'type'=>$input->get('type'),'unique_id'=>$article_id);

					Article::submit($article_array);
				}else{
					foreach($errors as $e){
						print_r($e);
					}
				}
			}
		}else{
			//display login page
			print_r('You are not logged in. Please log in and try again');
		}
	}

	public static function htmltoArray($param){
		//print_r($param);
		$doc = new DOMDocument();
	    //loads html from the string given
	    $doc->loadHTML($param);
	    //decides to remove whitespace
	    // get children of the body elements
	    $html = $doc->getElementsByTagName('body')->item(0)->childNodes;
	    $return = array();
	    //cycles through all child nodes of the body (the entire document)
	    foreach($html as $v){
	    	if($v->nodeName != '#text'){
	    		if(!$v->getAttribute('id')){
	    			$p_id=Paragraph::generateParaId();
	    			$v->setAttribute('id',$p_id);
	    		}else{
	    			$p_id=$v->getAttribute('id');
	    		}
	    		
	    		$return[$p_id]=$doc->saveHTML($v);
	    	}
	    }
	    //returns the elements array
	    return $return;
	 }
}
