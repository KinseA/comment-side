<?php

class Paragraph extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'paragraphs';
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password', 'remember_token');
	public static function findByCode($code){
		if($code){
			$article=self::where('para_ident','=',$code);
			if($article->count()){
				return $article->get();
			}
			return false;
		}
		return false;
	}

	public static function generateParaId($unique=0,$length=5){
		$options=array_merge(range('0', '9'), range('a', 'z'));
		$code='';
		if(!$unique){
			for ($i=0; $i < $length; $i++) { 
				$code.=$options[rand(0,count($options)-1)];
				if(self::findByCode($code)){
					self::generateParaId();
				}
			}
		}
		return $code;
	}

	public static function prepareParagraph($paragraph){
		$dom=new DOMDocument();
		$dom->loadHTML($paragraph);
		foreach($dom->childNodes as $child){
			print_r($child);
			echo '<br><br>';
		}
	}

	public static function upload($content,$article_id=0){
		if($article_id){
			$paragraphs = self::where('article_id', '=', $article_id)->get();
			foreach($content as $entry){
				//find all entries for that article.
				//print_r($paragraphs);
				//check how many there are. Download them

				//delete all entries where that article id is found. Match new paragraph data by code

				//upload new data


				//ALTERNATIVELY

				//Get the array from the database

				//get the content array

				//Match all the ones you can. Remove them from the original arrays when you do. Check if any haven't been matched. Also check if thet need to be updated (if content is different, or the ident is different) If not matched in original array, delete the entry. If not matched in the new array, insert them


				//OR UPDATE CODE TO BE FOR ARTICLE of id 0 THEN UPDATE ALL THE ONES WHICH ALREADY EXIST. THEN PURGE DB OF ALL REFERENCES TO ARTICLE 0
				/*if(self::findByCode($entry['ident'])){
					//update
					print_r('found');
					self::where('para_ident', '=', $entry['ident'])->update(['para_text' => $entry['text'],'paragraph_n'=>$entry['order']]);
				}else{
					$para=new Paragraph;
					$para->para_ident=
					self::create(['para_ident'=>$entry['ident'],'para_text'=>$entry['text'],'paragraph_n'=>$entry['order'],'article_id'=>$article_id]);
				}*/
			}
		}else{
			//insert new article
			print_r($content);
		}
		
		//DB::statement("INSERT INTO paragraphs (article_id,b,c) VALUES (1,2,3) ON DUPLICATE KEY UPDATE c=c+1");
	}


}
