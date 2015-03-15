<?php
if(!Request::ajax()){
	
		?>
</div>
</div>
<style>
#share-button {
  background-color:white;
  border:1px solid black;
  padding:10px;
  position:absolute;
  top:-9999px;
  left:-9999px;
  z-index:9999;
  box-shadow:0 1px 3px rgba(0,0,0,.4);
}
</style>
<div id="share-button"><button>Share!</button></div>
<script>
var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
/*window.addEventListener('resize', function(){
	w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
	var nav=document.getElementById('main-nav');
	var userNav=document.getElementById('user-nav');
	console.log('resizing');
	if(nav.getAttribute('style')){
		nav.removeAttribute('style');
		userNav.removeAttribute('style');
	}
	
});	*/

/*window.addEventListener('scroll',function(){
	//check if it's scrolling or not
	if(w <720){
		scrollFunc();
	}
});*/

/* modified version of http://stackoverflow.com/questions/18604022/slide-header-up-if-you-scroll-down-and-vice-versa */
var userNav = $('#user-nav'),
nav = $('#main-nav-container'),
headerHeight = nav.height() - 30,
treshold = -50,
lastScroll = 0;

var userHeight = userNav.height()*2,
userTreshold = 0;

$(document).on('scroll', function (evt) {
	if(w < 720){
		var newScroll = $(document).scrollTop(),
        diff = newScroll-lastScroll;

	    // normalize treshold range
	    treshold = (treshold+diff>headerHeight) ? headerHeight : treshold+diff; 
	   	treshold = (treshold < -47) ? -47 : treshold; //if its gone above the scroll height

	    userTreshold = (userTreshold+diff>userHeight) ? userHeight : userTreshold+diff;
	    userTreshold = (userTreshold < 0) ? 0 : userTreshold;

	    nav.css('top', (-treshold)+'px');
	    userNav.css('top',(-userTreshold)+'px');

	    lastScroll = newScroll;
	    
	}
    
});

$('#logo').on('click',function(){
	if(treshold == headerHeight){
		showNav();
		treshold=-50;
		userTreshold=0;
	}else{
		hideNav();
		treshold=headerHeight;
		userTreshold=userHeight;
	}
	
});

/*$('a').on('click',function(){
	if(pages.length>0){
		event.preventDefault();
		loadPage(this.getAttribute('href'));
		return false;
	}

});*/





/*https://css-tricks.com/forums/topic/highlight-text-and-share-it/*/
/*var savedText = null; // Variable to save the text

function saveSelection() {
  if (window.getSelection) {
    var sel = window.getSelection();
    if (sel.getRangeAt && sel.rangeCount) {
      return sel.getRangeAt(0);
    }
  } else if (document.selection && document.selection.createRange) {
    return document.selection.createRange();
  }
  return null;
}

function restoreSelection(range) {
  if (range) {
    if (window.getSelection) {
      var sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(range);
    } else if (ddocument.selection && range.select) {
      range.select();
    }
  }
}

var btnWrap = document.getElementById('share-button'),
    btnShare = btnWrap.children[0];

document.onmouseup = function(e) {
  savedText = saveSelection(); // Save selection on mouse-up
  console.log(savedText);

  setTimeout(function() {
  	console.log(savedText);
    var isEmpty = savedText.toString().length === 0; // Check selection text length

    // set sharing button position
    btnWrap.style.top = (isEmpty ? -9999 : e.pageY) + 'px';
    btnWrap.style.left = (isEmpty ? -9999 : e.pageX) + 'px';

  }, 10);

};

btnShare.onmousedown = function(e) {

  if (!savedText) return;

  window.open('https://twitter.com/intent/tweet?text=' + savedText, 'shareWindow', 'width=300,height=150,top=50,left=50'); // Insert the selected text into sharing URL
  restoreSelection(savedText); // select back the old selected text

  // hide if we are done
  setTimeout(function() {
    btnWrap.style.top = '-9999px';
    btnWrap.style.left = '-9999px';
  }, 1000);

  return false;

};*/
//changes id of the wrapper
document.getElementById('wrapper').id='p-1';
pages.push('p-1');

</script>
<div id="indicator" class="medium-editor-toolbar">
	<ul id="medium-editor-toolbar-actions1" class="medium-editor-toolbar-actions clearfix" style="display: block;">
		<li><button class="medium-editor-action medium-editor-button-first"><i class="fa fa-twitter"></i>
		</button></li>
		<li><button class="medium-editor-action"><i class="fa fa-comment"></i></button></li>
	</ul>
</body>
</html>
<?php
}
?>