<?php
		echo $header;
	
	/**check code**/
	if(isset($code)){
		if($code[0]=='p'){
			$code=$code;
		}else{
			$code=null;
		}
	}else{
		$code=null;
	}
	
?>
<div class="content">
<?php
	if($article){
		$article=$article[0];
		?>
		<article id="article-<?php echo $article->unique_id; ?>" >
			<div class="article-content <?php $class=($article->type==1) ? 'featured-article' : 'article'; echo $class;?>">
			
			<?php if($article->bg_url){
					?>
					<header id="header-container" class="<?php echo $class; ?>-header has-image" style="background-image:url(<?php echo Request::root(); ?>/<?php echo $article->bg_url; ?>)">
						<h1 contenteditable id="title" class="header-editable"><?php echo $article->title; ?></h1>
					</header>

					<?php
				}else{
					?>
					<header id="header-container" class="<?php echo $class; ?>-header">
						<h1 contenteditable id="title" class="header-editable"><?php echo $article->title; ?></h1>
					</header>
					<?php
				}
				if($article->in_response != 0){
						?>
						<div class="response-to">
							<?php
							$response_to=Article::getArticleById($article->in_response);
							?>
							This article is in response to 
							<?php
								$r=$response_to[0];
								?>
								<a href="<?php echo Request::root(); ?>/article/view/<?php echo $r->slug; ?>-<?php echo $r->unique_id; ?>"><?php echo $r->title; ?></a>
						</div>
						<?php
					}
				}
			?>
			<div id="response" style="display:none"></div>
			<div id="article" class="article-content-inner">
				<?php 
				//load the content
				
				//print_r($a.'<br>');
				print_r($article->content);
				?>
				<button onclick="testhighlightText()"></button>
			</div>
			<div id="c-response">
				<button id="agree-<?php echo $article->unique_id; ?>" class="agree" role="up" dataType="a" onclick="vote(this)">Agree</button>
				<button id="disagree-<?php echo $article->unique_id; ?>" class="disagree" role="down" dataType="a" onclick="vote(this)">Disagree</button>
				<a href="<?php echo Request::root(); ?>/opinion/create/<?php echo $article->slug; ?>-<?php echo $article->unique_id; ?>">Add a response</a>
			</div>
		</article>
			<?php
	?>
	<?php
?>
	</div>
	
</div>
<?php if($article){
?>
	<script>
	var interval;
	var commentBoxes = document.getElementsByClassName('comment-box');

	//reset comment box values
	for(var i=0;i<commentBoxes.length;i++){
		commentBoxes[i].comment.value='';
	}

	//assign the comment buttons to paragraphs


	// Add methods like this.  All Person objects will be able to invoke this
	function loadCommentButtons(){
	   var paragraphs=document.getElementById('article').getElementsByTagName('p');
		for(var i=0;i<paragraphs.length;i++){
			paragraphs[i].style.position = "relative";
			button = document.createElement('button');
			button.innerHTML = "press me"+i;
			paragraphs[i].appendChild(button);
			button.style.position = "absolute";
			button.style.right=-(button.clientWidth+10)+'px';
			button.style.top=0;
			button.setAttribute('class','comment-button');
			button.setAttribute('onClick','openComments(this)');
		}
	}

	function showReplies(comment_id){
		console.log(comment_id);
	}

	function openComments(caller){
		parent=caller.parentNode;
		var p_id=parent.id;
		var params = {
			paragraph: p_id,
			article: "<?php echo $article->unique_id; ?>",
			ajax:true
		}

		var child = document.createElement("div");
		child.id="comment-container";
		if (parent.nextSibling) {
		  parent.parentNode.insertBefore(child, parent.nextSibling);
		}else {
		  parent.parentNode.appendChild(child);
		}
		var response = document.getElementById('response');

		$('#comment-container').velocity({
			minHeight:300
		},{
			duration:100,
			easing:'ease-in-out',
			queue:false
		});
		$('#comment-container').html('<div id="upper-comment"></div><div id="comment-box"></div>');
		<?php
		if(User::loggedIn()){

			?>
			var func='onclick=addComment("'+p_id+'")';
			$('#comment-box').html('<div id="add-comment-'+p_id+'" class="comment-c new-comment comment-card"><div class="insert-comment"><span class="response-to">0</span><textarea class="new-comment-box" placeholder="Insert comment.."></textarea><button id="add-comment" '+func+'>Add comment</div></div></div>');
			
			<?php
		}
		
		?>
		//$('#comment-box').prepend('<div class="add-comment">Add a comment</div>')

		AJAXgetData('<?php echo Request::root(); ?>/ajax/comment/get',params,response);

		interval = setInterval(function(){
			if(response.innerHTML.length > 0){
				clearInterval(interval);
				var commentJSON = JSON.parse(response.innerHTML);
				var commentBox=document.getElementById('comment-box');
				for(var i=0;i<commentJSON.length;i++){
					c=commentJSON[i];
					commentBox.innerHTML+=prepareComment(c);

				}

					positionComments();
				
			}
		},200);

	}

	function positionComments(){
		var position=10;
		var comments = document.getElementById('comment-box').getElementsByClassName('comment-c');
		for(i=0;i<comments.length;i++){
			var id=comments[i].id;
			$('#'+id).css('left',position+'px');
			position= $('#'+id).width() + position + 10;
		}
	}

	function prepareComment(c,position){
		var func='';
		if(c['text_code']){
			func='onmouseover=highlightText("'+c['text_code']+'") onmouseout=removeHighlightText("'+c['text_code']+'")';
		}
		comment='<div id="'+c['c_id']+'" class="comment-c comment-card"'+func+'>';
		var show,flagged,vote;
		show='visible';
		if(c['votes']['user'].indexOf(3) > -1){
			//flagged
			flagged=true;
			show='hidden';
		}

		if(c['votes']['flags'].length >= 5){
			show='hidden';
		}

		if((c['votes']['user'].indexOf(1)) > -1){
			vote=1;
		}

		if(c['votes']['user'].indexOf(2) > -1){
			vote=2;
		}

		

		comment+='<div class="flag">';
		comment+='<input id="flag-'+c['c_id']+'" onclick="flag(this)" role="flag" dataType="c" type="button" class="flag '+(flagged ? 'flagged' : '')+'">'
		comment+='</div>';
		if(show=='hidden'){
			comment += '<span class="comment-warning">This comment has been flagged click <a id="'+c['c_id']+'" onclick="showComment(this.id)"> here </a></span>';
		}
		comment+='<div class="comment '+show+'"><div class="comment-author"><a href="<?php echo Request::root(); ?>/profile/'+c['username']+'">'+c['username']+'</a></div><div class="comment-text">'+c['comment_text']+'</div><div class="voting">';
			comment+='<button id="upvote-'+c['c_id']+'" class="upvote '+(vote==1 ? 'active' : '')+'" role="up" dataType="c" onclick="vote(this)">agree '+c['votes']['up'].length+' </button>';
		comment+='<button id="downvote-'+c['c_id']+'" class="downvote '+(vote==2 ? 'active' : '')+'" role="down" dataType="c" onclick="vote(this)">disagree '+c['votes']['down'].length+' </button>';
		comment+='</div></div></div>';

		return comment;
	}

	function prepareArticleVotes(){
		var c = <?php print_r(json_encode(Votes::getVotes($article->unique_id))); ?>;
		var show,flagged,vote;
		show='visible';

		if(c['flags'].length >= 5){
			show='hidden';
		}

		if((c['user'].indexOf(1)) > -1){
			vote=1;
		}

		if(c['user'].indexOf(2) > -1){
			vote=2;
		}

		

		console.log(vote);
	}

	prepareArticleVotes()

	function addComment(p_id){
		var formData=[];
		var commentAreaId = $('#add-comment-'+p_id);
		var textarea = commentAreaId.find('.insert-comment').find('.new-comment-box');
		var response=commentAreaId.find('.insert-comment').find('.response-to').html();
		$.ajax({
		  type: "POST",
		  url: "<?php echo Request::root(); ?>/ajax/comment/create",
		  data: {
			p_id: p_id,
			a_id: '<?php echo $article->unique_id; ?>',
			comment: textarea.val(),
			response_to: response
			}
		}).done(function(msg) {
		    alert(msg);
		    //updates accordingly
		});

	}

	function flag(comment){
		//checks if has the class of 'flagged'. If it does then it deletes the flag
		var id=comment.id;
		var elem=$('#'+id);
		if(!elem.hasClass('flagged') || true){
			if(elem.attr('role')=='flag'){
				$.ajax({
				  type: "POST",
				  url: "<?php echo Request::root(); ?>/ajax/interaction/vote",
				  data: {
					comment_id: elem.parent().parent().attr('id'),
					type: elem.attr('role')
					}
				}).done(function( msg ) {
				    if(msg=='removed'){
				    	elem.removeClass('flagged');
				    }else if(msg=='added'){
				    	elem.addClass('flagged');
				    }
				    //updates accordingly
				});
			}
		}
		
		return false;
		//send to the server
	}

	function vote(comment){
		var id=comment.id;
		var comment_id = id.substring(id.indexOf('-') + 1, id.length);
		var elem=$('#'+id);
		var role=elem.attr('role');
		var d=elem.attr('dataType');
		
		$.ajax({
		  type: "POST",
		  url: "<?php echo Request::root(); ?>/ajax/interaction/vote",
		  data: { 
		  	dataType: d,
		  	comment_id: comment_id, 
		  	type: role 
			}
		}).done(function( msg ) {
		    msg=JSON.parse(msg);
		    console.log(msg);
		    if(msg['dataType']=='c'){
		    	if(msg['removed'].indexOf(1)>-1){
					//removed an agree
					var elem = $('#upvote-'.comment_id);
					if(elem.hasClass('active')){
						elem.removeClass('active');
					}
				}else if(msg['removed'].indexOf(2)>-1){
					//removed a disagree
					var elem = $('#downvote-'.comment_id);
					if(elem.hasClass('active')){
						elem.removeClass('active');
					}
				}

				if(msg['added'].indexOf(1)>-1){
					//removed an agree
					var elem = $('#upvote-'.comment_id);
					if(!elem.hasClass('active')){
						elem.addClass('active');
					}
				}else if(msg['added'].indexOf(2)>-1){
					//removed a disagree
					var elem = $('#downvote-'.comment_id);
					if(!elem.hasClass('active')){
						elem.addClass('active');
					}
				}
		    }else if(msg['dataType']=='a'){
		    	if(msg['removed'].indexOf(1)>-1){
					//removed an agree
					var elem = $('#agree-'.comment_id);
					if(elem.hasClass('active')){
						elem.removeClass('active');
					}
				}else if(msg['removed'].indexOf(2)>-1){
					//removed a disagree
					var elem = $('#disagree-'.comment_id);
					if(elem.hasClass('active')){
						elem.removeClass('active');
					}
				}

				if(msg['added'].indexOf(1)>-1){
					//removed an agree
					var elem = $('#agree-'.comment_id);
					if(!elem.hasClass('active')){
						elem.addClass('active');
					}
				}else if(msg['added'].indexOf(2)>-1){
					//removed a disagree
					var elem = $('#disagree-'.comment_id);
					if(!elem.hasClass('active')){
						elem.addClass('active');
					}
				}
		    }
		    //updates accordingly
		});
		//checks type of vote by looking at the role attribute.
		return false;
		//sends to database
	}


	loadCommentButtons();

	//each button, when pressed, uses the id of its parent to create a comments box


	//add event listeners to comment boxes

	
	   

	var paragraphs = document.getElementById('article').getElementsByTagName('p');

	function testhighlightText(){
	    var span = document.createElement("span"); //this can be used to alter highlighted text
	    span.style.fontWeight = "bold";
	    span.style.color = "green";
	    
	    if (window.getSelection) {
	        var sel = window.getSelection();
	        if (sel.rangeCount) {
	            var range = sel.getRangeAt(0).cloneRange();
	            range.surroundContents(span);
	            sel.removeAllRanges();
	            sel.addRange(range);
	        }
	        console.log(sel);
	    }
	}

	function highlightText(code){
		if(code){
			codeArray=parseCode(code);
			var node=document.getElementById(codeArray[0].substring(2,codeArray[0].length));
			var nodeHtml=node.innerHTML;
			var text=nodeHtml.substring(codeArray[1].substring(2,codeArray[1].length),codeArray[2].substring(2,codeArray[2].length));
			node.innerHTML=node.innerHTML.replace(text,'<span class="highlighted">'+text+'</span>');
		}
	}

	function removeHighlightText(code){
		if(code){
			codeArray=parseCode(code);
			var node=$('#'+codeArray[0].substring(2,codeArray[0].length));
			node.find('.highlighted').contents().unwrap();
		}
	}

	function testSelectioncreate(){
		var node=document.getElementById('p_1')
	    var range = document.createRange();
	    range.selectNode(document.getElementById('p_1'));
	    var sel = window.getSelection();
	    sel.removeAllRanges();
	    sel.addRange(range);
	}

	function parseCode(code){
		cArray=new Array();
		cArray=code.split('-');
		return cArray;
	}

	function getSelectionCoords() {
	    var sel = document.selection, range, rects, rect;
	    var x = 0, y = 0;
	    var width;
	    if (sel) {
	        if (sel.type != "Control") {
	            range = sel.createRange();
	            range.collapse(true);
	            x = range.boundingLeft;
	            y = range.boundingTop;
	            width=range.boundingWidth;
	            height=range.boundingHeight;
	        }
	    } else if (window.getSelection) {
	        sel = window.getSelection();
	        if (sel.rangeCount) {
	            range = sel.getRangeAt(0).cloneRange();

	            if (range.getBoundingClientRect) {
	                var rect = range.getBoundingClientRect();
	                width = rect.right - rect.left;
	                height = rect.bottom - rect.top;
	            }

	            if (range.getClientRects) {
	                range.collapse(true);
	                rects = range.getClientRects();
	                console.log(rects);
	                if (rects.length > 0) {
	                    rect = range.getClientRects()[0];
	                }
	                x = rect.left;
	                y = rect.top;
	            }
	            // Fall back to inserting a temporary element
	            if (x == 0 && y == 0) {
	                var span = document.createElement("span");
	                if (span.getClientRects) {
	                    // Ensure span has dimensions and position by
	                    // adding a zero-width space character
	                    span.appendChild( document.createTextNode("\u200b") );
	                    range.insertNode(span);
	                    rect = span.getClientRects()[0];
	                    x = rect.left;
	                    y = rect.top;
	                    var spanParent = span.parentNode;
	                    spanParent.removeChild(span);

	                    // Glue any broken text nodes back together
	                    spanParent.normalize();
	                }
	            }
	        }
	    }
	    return { x: x, y: y ,width:width,height:height};
	}

	document.getElementById('article').onmouseup = function(e) {
	  var sel = document.selection;
	  var indicator = $('#indicator');
	  
	  if(sel || (window.getSelection() && window.getSelection()['type']=='Range')){
	  		var coord=getSelectionCoords();
	  		console.log(coord);
		  indicator.css('position','absolute');
		  indicator.css('top',coord['y']-indicator.height()+'px');
		  indicator.css('left',coord['x']+(coord['width']/4)-indicator.width()/2+'px');
		  indicator.addClass('tooltip-active');
	  }else{
	  	  indicator.css('position','absolute');
		  indicator.css('top','-9999px');
		  indicator.css('left','-9999px');
		  indicator.removeClass('tooltip-active');
	  }
	  
	  

	};

	document.onmousedown = function(e) {
	  var indicator = $('#indicator');
	  
	  indicator.css('position','absolute');
	  indicator.css('top','-9999px');
	  indicator.css('left','-9999px');
	  indicator.removeClass('tooltip-active');


	};

	//testSelectioncreate();
	highlightText(<?php $print=(!is_null($code)) ? $code : null; echo '"'.$print.'"'; ?>);
	</script>
<?php } 
		echo $footer;