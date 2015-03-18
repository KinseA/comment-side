<?php

class Comment extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'comments';
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public static function twitter($id){
		if(self::where('twitter_id','=',$id)->count()){
			return true;
		}
		return false;
	}

	public static function getComments($id=null,$a_id=null){
		if(isset($id)&&isset($a_id)){
			$comments=self::where('para_id','=',$id)->where('article_id','=',$a_id)->join('users','author_id','=','users.id');
			if($comments->count()){
				$comment=$comments->get();
				foreach($comment as $c){
					$c->votes=self::getCommentVotes($c->para_id,$c->c_id);
				}
				return $comment;
			}else{
				return false;
			}
		}
	}

	public static function getCommentVotes($p_id,$comment_id){
		$query=Votes::getVotes($comment_id);
		return $query;
	}

	public static function vote($c,$type){  //comment id, type of vote, 
		if(User::loggedIn()){
			//get from votes where user and comment id

			//check if the vote is less than 3 and greater than 0. If it matches to 1 or 2 (and matches type) then it adjusts accordingly.

			//updates or deletes from database

			//if it's equal to 3 then checks for flag, updates accordingly
		}
	}

	public static function getCount($p,$a){
		return self::where('para_id','=',$p)->where('article_id','=',$a)->count();
	}

}
