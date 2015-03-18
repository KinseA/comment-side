<?php

class CommentController extends BaseController {
	public function submitComment(){
		$input=Input::all();
		print_r($input);
		if(User::loggedIn()){
			if(!$input['comment']||!$input['p_id']||!$input['a_id']){
				print_r('Your comment was missing important fields. Please try again later');
			}else{
				$comment=$input['comment'];
				if(strlen($comment)<=200){
					$c=array('author_id'=>Session::get('user'),'comment_text'=>$comment,'para_id'=>$input['p_id'],'article_id'=>$input['a_id'],'date'=>time());
					$data=new Comment;
					$data->author_id=Session::get('user');
					$data->comment_text=$comment;
					$data->para_id=$input['p_id'];
					$data->article_id=$input['a_id'];
					$data->date=time();


					if($input['response_to']){
						$data->in_reply=$input['response_to'];
					}else{
						$data->in_reply='0';
					}

					if($data->save()){
						print_r('Comment submitted successfully');
					}else{
						print_r('Our bad. Something went wrong submitting your comment. We\'ll get the code monkeys to check it out');
						//TODO: Send email to the admin account
					}
				}else{
					print_r('Comment was too long. We allow a maximum of 200 characters!');
				}
			}
		}else{
			print_r('You are not logged in. Please log in and try again');
		}
		//print_r($Session::token());
		//echo Input::get('_token');
		/*if ( Session::token() !== Input::get( '_token' ) ) {
            return Response::json( array(
                'msg' => 'Unauthorized attempt to create setting'
            ) );
        }*/
	}

	public function getComments(){
		$input = Input::all();
		if(isset($input['paragraph']) && isset($input['article'])){
			//Comment::getComments($input['paragraph'],$input['article']);
			$comments=Comment::getComments($input['paragraph'],$input['article']);
			$return_array=array();
			if($comments){
				foreach($comments as $comment){
					array_push($return_array, $comment);
				}
				print_r(json_encode($return_array));
			}else{
				print_r('There are no comments for this paragraph (yet)');
			}
		}else{
			print_r('Comments not found. Please ensure that you have provided at least a valid paragraph id and a valid article id');
		}
		
	}

	public function receiveVote(){
		$input=Input::all();
		// comment_id
		// type
		if($input['type']=='flag'){
			Votes::flag($input['comment_id']);
		}elseif($input['type']=='up' || $input['type']== 'down' ){
			$comment_code = $input['type']=='up' ? 1 : 2;
			Votes::vote($input['comment_id'],$comment_code,$input['dataType']);
		}
		//check what the type is. If it's a flag then call the comment::flag() function

		//if it's an upvote or downvote call the comment::sendVote() function

		//return whatever the response is to use in JS
		//remove classes if appropriate. Update vote count if appropriate. 
	}

	public function getCount($pid,$aid){
		print_r(Comment::getCount($pid,$aid));
	}

}
