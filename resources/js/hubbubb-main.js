function parseForm(form){
	var data={};
	for(var i=0;i<form.elements.length;i++){
		data[form.elements[i].name] = form.elements[i].value;
	}
	return data;
}

function AJAXsubmitData(data,location,returnElement){
	if(window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest();
	}else{
		xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
	}
	
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			console.log(xmlhttp.responseText);
			returnElement.innerHTML=xmlhttp.responseText;
		}
	}

	xmlhttp.open('POST',location,true);
	xmlhttp.setRequestHeader('Content-type','application/x-www-form-urlencoded'); //sending form data as a post
	xmlhttp.send(JSON.stringify(data));	
}

function AJAXgetData(location,params,returnE){
	var stringParams ='';
	var paramsArray=[];
	if(params){
		for (var key in params) {
		  if (params.hasOwnProperty(key)) {
		  	paramsArray.push(key+'='+params[key]);
		  }
		}

		for(var i=0;i<paramsArray.length;i++){
			if(location.slice(-1) != '/' && i==0){
				stringParams += '?';
			}

			stringParams += paramsArray[i];

			if(i<paramsArray.length-1){
				stringParams = stringParams+'&';
			}
		}
	}
	

	if(window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest()
	}else{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4 && xmlhttp.status==200){
			returnE.innerHTML=xmlhttp.responseText;
		}
	}

	requestLocation = location+stringParams;
	xmlhttp.open('GET',requestLocation,true); 
	xmlhttp.send();
}

function hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}


/*animation functions*/
function showNav(){
	console.log('show nav');
	var userNav = $('#user-nav'),
	nav = $('#main-nav-container')

	userNav.velocity({
		top:0
	},{
		duration: 90,
		queue:false,
		easing:'easeOutSine'
	});

	nav.velocity({
		top: '2.6rem'
	},{
		duration:200,
		queue:false,
		easing:'easeOutSine'
	});

}

function hideNav(){
	var userNav = $('#user-nav'),
	nav = $('#main-nav-container');
	userNav.velocity({
		top: '-2.6rem'
	},{
		duration: 90,
		queue:false,
		easing:'easeOutSine'
	});

	nav.velocity({
		top: -(nav.height() - 30)
	},{
		duration:200,
		queue:false,
		easing:'easeOutSine'
	});
}

function loadPage(url){
	var container = document.getElementById('container');
	var currentPage = pages[pages.length-1];
	var newDiv = document.createElement('div');
	newDiv.id='p-'+(currentPage.replace('p-','')/1+1);
	container.appendChild(newDiv);
	AJAXgetData(url,null,newDiv);
	$('#'+currentPage).velocity({
		opacity:0
	},{
		complete: function() { $('#'+currentPage).css('display','none') },
		queue:false,
		duration:500,
		easing:'ease-in-out'
	});
	var stateObj = { foo: "bar" };
	history.pushState(stateObj, "page 2", url);
	newDiv.marginTop = $(window).height();
	$('#'+newDiv.id).velocity({
		marginTop: 0
	},{
		queue:false,
		duration:1000,
		delay:600,
		easing:'ease-in-out'
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

function sendTweet(element){
	var text,code,url,sourceURL;
	if(string.length > 0){
		text=string.replace(' ','+');
		if(text.length > 97){
			text=text.substring(0,97)+'...';
		}
	}

	code = savedCode;
	
	url='https://twitter.com/intent/tweet?text='+text+'+'+window.location.href+'&via=theHubbubb';
	window.open(url);
	hideIndicator();
	return false;
}