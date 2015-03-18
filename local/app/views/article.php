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
	var aid = '<?php echo $article->unique_id; ?>';
	var paragraphs = [];

	if(window.location.hash) {
	  // Fragment exists
	} else {
	  // Fragment doesn't exist
	}

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
			var ident = 'button-'+paragraphs[i].id;
			button.id = ident;
			$.ajax({
			    type: "GET",
			    url: "<?php echo Request::root(); ?>/ajax/comment/getCount/"+paragraphs[i].id+"/"+aid,
			    async: false,
			    data: parms,
			    dataType: "json",
			    success: function (data) {
			        $("#rdy_msg").text("Completed: " + id);
			    },
			    error: function () {
			        var cdefg = data;
			    }
			});
			
			paragraphs[i].appendChild(button);
			button.style.position = "absolute";
			button.style.right=-(button.clientWidth+10)+'px';
			button.style.top=0;
			button.setAttribute('class','comment-button');
			button.setAttribute('onClick','openComments(this)');
			button.setAttribute('onLoad','addCount(this)');
		}
	}

	function addCount(element){
		var para_id = element.parentNode.id;
		$.get( "<?php echo Request::root(); ?>/ajax/comment/getCount/"+para_id+"/"+aid, function( data ) {
		  element.innerHTML = data;
		});
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
		if(code){ //needs to cycle through elements and wrap their text separately
			console.log(code);
			codeArray=parseCode(code);
			var id=codeArray[0].substring(2,codeArray[0].length)
			var node=$('#'+id);
			var start = codeArray[1].substring(2,codeArray[1].length);
			var end = codeArray[2].substring(2,codeArray[2].length);
			var nodeHtml=node.html();
			//var text=nodeHtml.substring(codeArray[1].substring(2,codeArray[1].length),codeArray[2].substring(2,codeArray[2].length));
			//console.log(text);
			//node.innerHTML=node.innerHTML.replace(text,'<span class="highlighted">'+text+'</span>');

			//get list of elements in paragraph
			//cycle trough the content of all of them (check if the childNode is text or not)
			//wrap the text and subtract the length
			var elem = document.getElementById(id);
			//preWrapText(start,end,elem,0,id);
			selectAndHighlightRange(id, start, end);
		}
	}

	function getTextNodesIn(node) {
    var textNodes = [];
    if (node.nodeType == 3) {
        textNodes.push(node);
    } else {
        var children = node.childNodes;
        for (var i = 0, len = children.length; i < len; ++i) {
            textNodes.push.apply(textNodes, getTextNodesIn(children[i]));
        }
    }
    return textNodes;
}

//START http://stackoverflow.com/questions/6240139/highlight-text-range-using-javascript by Tim Down
function setSelectionRange(el, start, end) {
    if (document.createRange && window.getSelection) {
        var range = document.createRange();
        range.selectNodeContents(el);
        var textNodes = getTextNodesIn(el);
        var foundStart = false;
        var charCount = 0, endCharCount;

        for (var i = 0, textNode; textNode = textNodes[i++]; ) {
            endCharCount = charCount + textNode.length;
            if (!foundStart && start >= charCount && (start < endCharCount || (start == endCharCount && i < textNodes.length))) {
                range.setStart(textNode, start - charCount);
                foundStart = true;
            }
            if (foundStart && end <= endCharCount) {
                range.setEnd(textNode, end - charCount);
                break;
            }
            charCount = endCharCount;
        }

        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (document.selection && document.body.createTextRange) {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(true);
        textRange.moveEnd("character", end);
        textRange.moveStart("character", start);
        textRange.select();
    }
}

function makeEditableAndHighlight(colour) {
    sel = window.getSelection();
    if (sel.rangeCount && sel.getRangeAt) {
        range = sel.getRangeAt(0);
    }
    document.designMode = "on";
    if (range) {
        sel.removeAllRanges();
        sel.addRange(range);
    }
    // Use HiliteColor since some browsers apply BackColor to the whole block
    if (!document.execCommand("HiliteColor", false, colour)) {
        document.execCommand("BackColor", false, colour);
    }
    document.designMode = "off";
}

function highlight(colour) {
    var range, sel;
    if (window.getSelection) {
        // IE9 and non-IE
        try {
            if (!document.execCommand("BackColor", false, colour)) {
                makeEditableAndHighlight(colour);
            }
        } catch (ex) {
            makeEditableAndHighlight(colour)
        }
    } else if (document.selection && document.selection.createRange) {
        // IE <= 8 case
        range = document.selection.createRange();
        range.execCommand("BackColor", false, colour);
    }
}

function selectAndHighlightRange(id, start, end) {
    setSelectionRange(document.getElementById(id), start, end);
    highlight("#000");
}
//END http://stackoverflow.com/questions/6240139/highlight-text-range-using-javascript by Tim Down


	function removeHighlightText(code){
		if(code){
			codeArray=parseCode(code);
			var node=$('#'+codeArray[0].substring(2,codeArray[0].length));
			node.find('.highlighted').contents().unwrap();
		}
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
	  positionIndicator(sel);
	};

	document.onmousedown = function(e) {
	  var indicator = $('#indicator');
	  
	  indicator.css('position','absolute');
	  indicator.css('top','-9999px');
	  indicator.css('left','-9999px');
	  indicator.removeClass('tooltip-active');


	};

	function generateCode(sel){
		$('#indicator .add-comment').attr('role','false');
		if(sel || (window.getSelection() && window.getSelection()['type']=='Range')){
	 		var element,start,end,selection,code;
	 		selection = window.getSelection().getRangeAt(0);
	 		//fragment=stripTags(fragment);
	 		var parentNode = getSelectionParentNode();
	 		$('#indicator .add-comment').attr('role',parentNode);
	 		element = $('#'+parentNode);
	 		code = 'p_'+parentNode;
	 		highlightedString=stripTags(getSelectionHTML());

	 		start=stripTags(element.html()).search(highlightedString);
			end = start+highlightedString.length;
			if((end+start) > (stripTags(element.html()).length)){
				//add a warning about only being able to do to the length of the paragraph
				end = stripTags(element.html()).length -1;
			}
			code += '-s_'+start+'-l_'+end;

			return code;
			
			//do something with the code
	 		//find in the element

	 		//start = getSelectionStart();
	  	}
	}

	function stripTags(string){
		var html = jQuery.parseHTML(string);
		var string=''
		$.each(html,function(index,value){
			if(value.nodeName != 'BUTTON'){
				string+=value.textContent;
			}
		});

		return string;
	}

	function getSelectionHTML(){
	    var html = "";
	    if (typeof window.getSelection != "undefined") {
	        var sel = window.getSelection();
	        if (sel.rangeCount) {
	            var container = document.createElement("div");
	            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
	                container.appendChild(sel.getRangeAt(i).cloneContents());
	            }
	            html = container.innerHTML;
	        }
	    } else if (typeof document.selection != "undefined") {
	        if (document.selection.type == "Text") {
	            html = document.selection.createRange().htmlText;
	        }
	    }
	    return html;
	}

	function getSelectionParentNode(){
		var parent = null, sel;
	    if (window.getSelection) {
	        sel = window.getSelection();
	        if (sel.rangeCount) {
	            parent = sel.getRangeAt(0).commonAncestorContainer;
	            if (parent.nodeType != 1) {
	                parent = parent.parentNode;
	            }
	        }
	    } else if ( (sel = document.selection) && sel.type != "Control") {
	        parent = sel.createRange().parentElement();
	    }
	    return parent.id;
	}

	function positionIndicator(sel){
		var indicator = $('#indicator');
		if(sel || (window.getSelection() && window.getSelection()['type']=='Range')){
	  		var coord=getSelectionCoords();
	  		
		  indicator.css('position','absolute');
		  indicator.css('top',coord['y']-indicator.height()+'px');
		  indicator.css('left',coord['x']+(coord['width']/4)-indicator.width()/2+'px');
		  indicator.addClass('tooltip-active');
		  
		  highlightText(generateCode(sel));
	  }else{
	  	  indicator.css('position','absolute');
		  indicator.css('top','-9999px');
		  indicator.css('left','-9999px');
		  indicator.removeClass('tooltip-active');
	  }
	}

	//testSelectioncreate();
	highlightText(<?php $print=(!is_null($code)) ? $code : null; echo '"'.$print.'"'; ?>);
	</script>
<?php } 
		echo $footer;