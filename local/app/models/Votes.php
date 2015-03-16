<?php

class Votes extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'votes';
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public static function getVotes($comment){ //get votes for an individual comment
		$upvotes = array();
		$downvotes=array();
		$flags=array();
		$user=array();
		$return_votes=array();

		$votes = self::where('item_id','=',$comment);
		$comment_votes=$votes->get(array('user_id','item_id','vote_code'));
		foreach($comment_votes as $vote){
			if($vote->comment_code == 1){
				array_push($upvotes, $vote);
			}elseif($vote->comment_code==2){
				array_push($downvotes, $vote);
			}elseif($vote->comment_code==3){
				array_push($flags, $vote);
			}

			if($vote->user_id == Session::get('user')){
				array_push($user, $vote->comment_code);
			}
		}
		$return_votes['up']=$upvotes;
		$return_votes['down']=$downvotes;
		$return_votes['flags']=$flags;
		$return_votes['user']=$user;
		return $return_votes;
	}

	public static function flag($comment){
		//check if it's already been flagged
		$user=Session::get('user');
		if(self::isFlagged($comment,$user)){
			//remove the flag
			self::where('item_id','=',$comment)->where('user_id','=',$user)->where('vote_code','=',3)->delete();
			print_r('removed');
		}else{
			//add a flag
			$flag=new Votes;
			$flag->user_id = $user;
			$flag->comment_id=$comment;
			$flag->comment_code=3;
			$flag->save();
			print_r('added');
		}
		
	}

	public static function isFlagged($comment,$user){
		$count=self::where('item_id','=',$comment)->where('user_id','=',$user)->where('vote_code','=',3)->count();
		if($count){
			return true;
		}else{
			return false;
		}
	}

	public static function vote($comment,$code,$dT){
		$user=Session::get('user');
		$upvotes=self::where('item_id','=',$comment)->where('user_id','=',$user)->where('vote_code','=',1)->count();
		$downvotes=self::where('item_id','=',$comment)->where('user_id','=',$user)->where('vote_code','=',2)->count();
		$return_array=array();
		$return_array['removed']=array();
		$return_array['added']=array();
		$return_array['dataType']=$dT;
		$type='comment';
		if($dT=='a'){
			$type='article';
		}

		if($code==1){
			if($downvotes){
				self::where('item_id','=',$comment)->where('user_id','=',$user)->where('vote_code','=',2)->delete();
				array_push($return_array['removed'],2);
			}

			if($upvotes){
				self::where('item_id','=',$comment)->where('user_id','=',$user)->where('vote_code','=',1)->delete();
				array_push($return_array['removed'],1);
			}else{
				array_push($return_array['added'], $code);
				$flag=new Votes;
				$flag->user_id = $user;
				$flag->item_id = $comment;
				$flag->vote_code = $code;
				$flag->type = $type;
				$flag->save();
			}
		}elseif($code==2){
			//downvotes
			if($upvotes){
				self::where('item_id','=',$comment)->where('user_id','=',$user)->where('vote_code','=',1)->delete();
				array_push($return_array['removed'],1);
			}

			if($downvotes){
				self::where('item_id','=',$comment)->where('user_id','=',$user)->where('vote_code','=',2)->delete();
				array_push($return_array['removed'],2);
			}else{
				array_push($return_array['added'], $code);
				$flag=new Votes;
				$flag->user_id = $user;
				$flag->item_id = $comment;
				$flag->vote_code = $code;
				$flag->save();
			}
		}

		
		print_r(json_encode($return_array));
		
	}
}
