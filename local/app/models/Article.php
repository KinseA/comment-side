<?php

class Article extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'articles';
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password', 'remember_token');
	public static function getFeature($id=null){
		if(!$id){ //if a specific id isn't given
			//fetch all features
			$article=self::where('type', '=', 1)->leftJoin('users', 'author', '=', 'users.id'); 
			if($article->count()){
				return $article->get();
			}else{
				return false;
			}
		}else{
			if(is_numeric($id)){
				//find by id
			}else{
				$article=self::where('unique_id','=',$id)->join('paragraphs','articles.unique_id','=','paragraphs.article_id');
				if($article->count()){
					return $article->get();
				}else{
					return false;
				}
				//find by slug. In this case you need to have an option for if the article is found
			}
		}
	}

	public static function generateId($unique=0,$length=9){
		$options=array_merge(range('0', '9'), range('a', 'z'));
		$code='';
		if(!$unique){
			for ($i=0; $i < $length; $i++) { 
				$code.=$options[rand(0,count($options)-1)];
				if(self::findByCode($code)){
					self::generateId();
				}
			}
		}
		return $code;
		
	}

	

	public static function getArticleByUrl($url=null){
		try{
			if($url){
				$id=substr($url,strrpos($url,'-')+1);
				if($article=self::getArticleByCode($id)){
					return $article;
				}
			}
			throw new Exception('Please specify a valid url slug');
		}catch(Exception $e){
			print_r($e->getMessage());
		}
		
	}

	public static function getArticleByCode($code=null){
		if($code){
			$article=self::where('unique_id','=',strtolower($code));
			if($article->count()){
				return $article->get();
			}
			return false;
		}
		return false;
	}

	public static function getArticleByUnique(){

	}

	public static function getArticleById($id){
		if($id){
			if(is_numeric($id)){
				$article=self::where('id','=',$id);
				if($article->count()){
					return $article->get();
				}else{
					return false;
				}
			}else{
				$article=self::where('slug','=',$id);
				if($article->count()){
					return $article->get();
				}else{
					return false;
				}
			}
		}
	}

	public static function findByCode($code=false){
		if($code){
			$article=self::where('unique_id','=',$code);
			if($article->count()){
				return $article->get();
			}
			return false;
		}
		return false;
	}

	public static function loadParagraph($a){
		$para_txt=$a->para_text;
		$ident=$a->para_ident;

		$doc=new DOMDocument;
		$doc->loadHTML($para_txt);
		
		$html = $doc->getElementsByTagName('body')->item(0)->childNodes;
		foreach($html as $child){
			if($child->tagName=='p'){
				$child->setAttribute('id',$a->para_ident);
			}	
		}
		$return_val=$doc->saveHTML();
		$return_val=substr($return_val, strpos($return_val, '<body>')+strlen('<body>'));
		$return_val=substr($return_val,0,strpos($return_val,'</body>'));
		return $return_val;

		//get paragraph text. Add name and order id to the paragraph

		//order attribute is less important

		//add unique id as the id

		//unique code to highlight text uses the unique id


	}

	public static function submit($article){
		//filter through content
		$content=array();
		$order=1;
		$bg_url='';
		foreach($article['content'] as $key=>$paragraph){
			$changes=array();
			$dom = new DOMDocument();
			$dom->loadHTML($paragraph);
			$image_parent = $dom->getElementsByTagName('img');
			if($image_parent->length>0){
				foreach($image_parent as $image){
					$image_array=array();

					$src=$image->getAttribute('src');
					//print_r($src);
					//print_r($src);
					//get the base-64 from data
					if(strpos($src, 'data:')!==false){
						$image_array['src']=$src;
						$base64_str = substr($src, strpos($src, ",")+1);

				        //decode base64 string
				        $image64 = base64_decode($base64_str);
				        $jpg_url = "article-image".time().Session::get('user').self::generateId(0,5).".jpeg";
				        $location=public_path().'/resources/uploaded_images/';
				        $return_src='resources/uploaded_images/'.$jpg_url;
				        $image_array['final_src'] = $return_src;
						$file=fopen($location.$jpg_url,'w');
						fwrite($file, $image64);
						fclose($file);

						$image->setAttribute('src',$return_src);

						array_push($changes, $image_array);
					}
			        
					
				}
			}
			$dom->saveHTML();
			if(count($changes)){
				foreach($changes as $change){
					$paragraph=str_replace($change['src'], $change['final_src'], $paragraph);
				}
			}
			/*foreach($html as $child){
				print_r($child);
			}*/
			try{
				array_push($content,array('ident'=>$key,'text'=>$paragraph,'order'=>$order));
			}catch(Exception $e){
				print_R($e->getMessage());
			}
			$order++;
		}

		$type=$article['type'];
		if($type!=2){
			if(User::hasPermission('use_header_image')){
				$base64_str = substr($article['bg_url'], strpos($article['bg_url'], ",")+1);
		        //decode base64 string
		        if($base64_str){
		        	$image64 = base64_decode($base64_str);
			        $jpg_url = "article-image".time().Session::get('user').self::generateId(0,5).".jpeg";
			        $location=public_path().'/resources/uploaded_images/';
			        $return_src='resources/uploaded_images/'.$jpg_url;
			        $bg_url = $return_src;
					$file=fopen($location.$jpg_url,'w');
					fwrite($file, $image64);
					fclose($file);
		        } 
			}
		}

		if(User::hasPermission('bypass_moderation')){
			$status=1;
		}else{
			$status=2;
		}

		
		if($type!=2){
			if(!User::hasPermission('create_articles')){
				$type=2;
			}
		}

		$slug=str_replace(' ','-',$article['title']);
		if($found_article=self::findByCode($article['unique_id'])){
			//its being edited update it
			$string_content='';
			foreach($content as $c){
				$string_content.=$c['text'];
			}
			$slug=str_replace(' ', '-', $article['title']);
			$update = self::where('unique_id', '=', $article['unique_id'])->update(array('title' => $article['title'],'content'=>$string_content,'slug'=>$slug,'bg_url'=>$bg_url));

			//need to find the comments related to missing paragraphs and remove them
		}else{
			//insert new article
			$string_content = '';
			foreach($content as $c){
				$string_content.=$c['text'];
			}
			//insert new article
			DB::table('articles')->insert(array('title' => $article['title'],'date'=>time(),'in_response'=>$article['in_response'],'author'=>Session::get('user'),'type'=>$article['type'],'status'=>$status,'slug'=>$slug,'unique_id'=>$article['unique_id'],'header_html'=>'','content'=>$string_content,'bg_url'=>$bg_url));
		}
		//print_r($insert_articles);
	}
}
