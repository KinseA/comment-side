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


/*http://stackoverflow.com/questions/1222915/can-one-use-window-onscroll-method-to-include-detection-of-scroll-direction*/
/*function scrollFunc(e) {
    if ( typeof scrollFunc.x == 'undefined' ) {
        scrollFunc.x=window.pageXOffset;
        scrollFunc.y=window.pageYOffset;
    }
    var diffX=scrollFunc.x-window.pageXOffset;
    var diffY=scrollFunc.y-window.pageYOffset;

    if( diffX<0 ) {
        // Scroll right
    } else if( diffX>0 ) {
        // Scroll left
    } else if( diffY<0 ) {
        console.log('down');
        var userNav=$('#userNav');
        var nav = $('#main-nav-container');
        var navs=$('.nav');
	    //nav.style.top= -nav.clientHeight - 30 + 'px';
	    //userNav.style.top = -userNav.clientHeight + 'px';
	    //Velocity(userNav, { top: -userNav.clientHeight + 'px' }, { duration: 1000 });
        //Velocity(nav, { top: -nav.clientHeight - 30 + 'px' }, { duration: 1000 });
        navs.velocity({
        	top: -nav.height() -30 + 'px'
        },{
        	duration:500

        });

        //animate the nav into position
    } else if( diffY>0 ) {
        console.log('up');
        //var userNav=document.getElementById('user-nav');
        //var nav = document.getElementById('main-nav');
        var userNav=$('#userNav');
        var nav = $('#main-nav-container');
        var navs=$('.nav');
        //userNav.style.top = 0;
        userNav.velocity({
        	top: 0
        },{
        	duration:500
        });

        //navs.velocity('reverse');


        
        //nav.style.top= 0 + userNav.clientHeight + 'px';
    } else {
        // First scroll event
    }
    scrollFunc.x=window.pageXOffset;
    scrollFunc.y=window.pageYOffset;
}*/

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