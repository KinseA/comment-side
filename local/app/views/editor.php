<?php 
echo $header;
?>
<!--<link rel="stylesheet" href="<?php echo Request::root(); ?>/resources/medium/css/medium-editor.css"> 
<link rel="stylesheet" href="<?php echo Request::root(); ?>/resources/medium/css/themes/hubbubb.css">
<script type="text/javascript" src="<?php echo Request::root(); ?>/resources/medium/js/medium-editor.js"></script>-->

<link rel="stylesheet" href="<?php echo Request::root(); ?>/resources/medium/medium-editor/dist/css/medium-editor.min.css">
<link rel="stylesheet" href="<?php echo Request::root(); ?>/resources/medium/medium-editor/dist/css/themes/hubbubb.css" id="medium-editor-theme">
<!-- Font Awesome for awesome icons. You can redefine icons used in a plugin configuration -->

<!-- The plugin itself -->
<link rel="stylesheet" href="<?php echo Request::root(); ?>/resources/medium/medium-editor-insert-plugin/dist/css/medium-editor-insert-plugin.min.css">

<!-- JS -->

<script src="<?php echo Request::root(); ?>/resources/medium/medium-editor/dist/js/medium-editor.js"></script>
<script src="<?php echo Request::root(); ?>/resources/medium/handlebars/handlebars.runtime.min.js"></script>
<script src="<?php echo Request::root(); ?>/resources/medium/jquery-sortable/source/js/jquery-sortable-min.js"></script>
<!-- Unfortunately, jQuery File Upload Plugin has a few more dependencies itself -->
<script src="<?php echo Request::root(); ?>/resources/medium/blueimp-file-upload/js/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo Request::root(); ?>/resources/medium/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
<script src="<?php echo Request::root(); ?>/resources/medium/blueimp-file-upload/js/jquery.fileupload.js"></script>
<!-- The plugin itself -->
<script src="<?php echo Request::root(); ?>/resources/medium/medium-editor-insert-plugin/dist/js/medium-editor-insert-plugin.min.js"></script>

<div class="content">
<?php
	$resp=0;
	if(User::loggedIn()){ 	
		if(isset($in_response)){
			if($response_to=Article::getArticleByUrl($in_response)){
				$resp=$response_to[0]->id;
			}
		}
		if(isset($article)){ //if it's being edited
				if(Session::get('user')==$article[0]->author){
					$article_id=$article[0]->unique_id;
					$article=$article[0];
			?>
					<article id="article-<?php echo $article->unique_id; ?>" >
						<div class="article-content <?php $class=($article->type==1) ? 'featured-article' : 'article'; echo $class;?>">
						
						<?php if($article->bg_url){
							?>
							<header id="header-container" class="<?php echo $class; ?>-header has-image" style="background-image:url(<?php echo Request::root(); ?>/<?php echo $article->bg_url; ?>)">
								<h1 contenteditable id="title" class="header-editable"><?php echo $article->title; ?></h1>
								<form id="image-form" runat="server">
										<label id="image-upload-label" for"imgInp" style="display:inline-block">
											<span id="upload-button" style="display:inline-block"><i class="fa fa-camera"></i></span>
									    	<input type='file' id="imgInp" />
									    </label>
									    <div id="close-image-label" style="display:inline-block">
									    	<span id="close-button" ><i class="fa fa-times"></i></span>
									    </div>
									</form>
							</header>

							<?php
						}else{
							?>
							<header id="header-container" class="<?php echo $class; ?>-header">
								<h1 contenteditable id="title" class="header-editable"><?php echo $article->title; ?></h1>
								<?php 
								if($article->type==3){
									//get the image upload button
									?>

									<form id="image-form" runat="server">
										<label id="image-upload-label" for"imgInp" style="display:inline-block">
											<span id="upload-button"><i class="fa fa-camera"></i></span>
									    	<input type='file' id="imgInp" />
									    </label>
									    <div id="close-image-label" style="display:none">
									    	<span id="close-button"><i class="fa fa-times"></i></span>
									    </div>
									</form>
									<?php
								}
								?>
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
						<div id="response">

						</div>
						<div id="editor" class="article-content-inner editable">
							<?php 
							//load the content
							
							//print_r($a.'<br>');
							print_r($article->content);
						
						?>
							<button onclick="testhighlightText()"></button>
						</div>
					</article>
					<?php
				}else{ //if it's new. Need to check if the user has permission to create from scratch. Also check if resp is true.
					if($resp || User::hasPermission('create_articles')){
				?>
					<article>
						<div id="the-article"  name="0">
							<div class="article-content">
								<?php if(User::hasPermission('use_header_image')){
										?>
										<header id="header-container" class="<?php echo $class; ?>-header">
											<h1 contenteditable id="title" class="header-editable"><?php echo $article->title; ?></h1>
											<form id="image-form" runat="server">
													<label id="image-upload-label" for"imgInp" style="display:inline-block">
														<span id="upload-button" style="display:inline-block"><i class="fa fa-camera"></i></span>
												    	<input type='file' id="imgInp" />
												    </label>
												    <div id="close-image-label" style="display:none">
												    	<span id="close-button" ><i class="fa fa-times"></i></span>
												    </div>
												</form>
										</header>

										<?php
									}else{
										?>
										<header id="header-container" class="<?php echo $class; ?>-header">
											<h1 contenteditable id="title" class="header-editable"><?php echo $article->title; ?></h1>
										</header>
										<?php
									}
									if($resp){
										?>
										<div class="response-to">
											<?php

											$response_to=Article::getArticleByUrl($in_response);
											?>
											This article will be in response to 
											<?php
											$r=$response_to[0];
											
												?>
												<a href="<?php echo Request::root(); ?>/article/view/<?php echo $r->slug; ?>"><?php echo $r->title; ?></a>
												<?php
											

											?>
										</div>
										<?php
									}else{

									}
								?>
								<div id="response">

								</div>
								<div id="editor" class="editable article-content-inner">
									
									
								</div>
							</div>
						</div>
					</article>
				<?php
					}
				}
				?>
				<button id="submit" class="submit-btn">Submit</button>
				<?php
			}else{
				//show login
			}
			?>

	
</div>
<script type="text/javascript">
	function addImage(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function (e) {
	        	//sets the background image
	        	var header=$('#header-container');
	        	header.velocity({
	            	minHeight:300+'px'
	            },{
	            	duration:200,
	            	queue:false,
	            	easing:'ease-in-out'
	            });

	            $('#header-container h1').velocity({
	            	marginTop:'1em'
	            },{
	            	duration:250,
	            	queue:false,
	            	easing:'ease-in-out'
	            });

	            $('#close-image-label').css('display','inline-block');

	            header.css('background-image', 'url(' + e.target.result + ')');
	            header.addClass('has-image');
	           

	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$("#imgInp").change(function(){
	    addImage(this);
	});

	$('#close-image-label').on('click',function(){
		var header=$('#header-container');
    	header.velocity({
        	minHeight:0
        },{
        	duration:200,
        	queue:false,
        	easing:'ease-in-out'
        });

        $('#header-container h1').velocity({
        	marginTop:0
        },{
        	duration:250,
        	queue:false,
        	easing:'ease-in-out'
        });

        $('#close-image-label').css('display','none');
        $('#upload-image-label').css('display','inline-block');

        header.removeAttr('style');
        header.removeClass('has-image');
        $('#image-form').each(function(){
		    this.reset();
		});
	})

    var editor = new MediumEditor('.editable');

    $(function () {
	    $('.editable').mediumInsert({
	        editor: editor
	    });
	});

	var btn=document.getElementById("submit");

	btn.addEventListener('click',function(){
		var article=document.getElementById('the-article');
		var article_id='<?php if(isset($article_id)){
			echo $article_id;
		}else{
			echo false;
		}
		?>';
		<?php 
			if($resp){
				echo 'var type = 2;';
			}else{
				echo 'var type = 3;';
			}
		?>
		var article_title;
		if(document.getElementById('title').innerHTML=='<span id="new-title">Insert Header</span>'){
			article_title='';
		}else{
			article_title = document.getElementById('title').innerHTML;
		}
		
		var article_text = document.getElementById('editor').innerHTML;

		if(article_text){
			var articleData={};
			articleData['bg_url']='';
			if(document.getElementById('header-container').hasAttribute('style')){
				var header=document.getElementById('header-container');
				articleData['bg_url']=header.style.backgroundImage;
			}
			articleData['a_id'] = article_id;
			articleData['title'] = article_title;
			articleData['content'] = article_text;
			articleData['in_response'] = <?php echo $resp; ?>;
			articleData['type'] = type;
			
			//need a way to check if form has certain attributes for too high values
			AJAXsubmitData(articleData,'<?php echo Request::root(); ?>/ajax/article',document.getElementById('response'));
		}

		
	},false);

	var article=document.getElementById("the-article");

	$('#title').on('click',function(){
		ntitle=$('#title');
		if(ntitle.html() == '<span id="new-title">Insert Header</span>'){
			ntitle.html("");
		}
	});

	title=document.getElementById('title');
	window.addEventListener('keyup',function(){
		if(title.innerHTML.length == 0){
			title.innerHTML = '<span id="new-title">Insert Header</span>';
		}
	});

	window.addEventListener('keydown',function(){
		if(title.innerHTML=='<span id="new-title">Insert Header</span>'){
			title.innerHTML = '';
		}
	});
</script>

<?php
echo $footer;
?>