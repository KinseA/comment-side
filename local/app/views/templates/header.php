<?php
if(!Request::ajax()){
	
		?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<script src="<?php echo Request::root(); ?>/resources/medium/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo Request::root(); ?>/resources/js/velocity.min.js"></script>
	<script src="//cdn.jsdelivr.net/velocity/1.2.2/velocity.min.js"></script>
	<link rel="stylesheet" href="<?php echo Request::root(); ?>/resources/css/hubbubb-main.css">
	<link rel="stylesheet" href="<?php echo Request::root(); ?>/resources/medium/medium-editor/dist/css/medium-editor.min.css">
	<script src="<?php echo Request::root(); ?>/resources/js/hubbubb-main.js"></script>
	<script type="text/javascript" async src="//platform.twitter.com/widgets.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Noto+Sans|Noto+Serif:700' rel='stylesheet' type='text/css'>	
</head>
<body>
<header id="user-nav" class="nav">
	<?php if(User::loggedIn()){
		//display logout link
		echo '<a href="'.Request::root().'/user/logout">Logout</a>';
	}else{
		//display login link
		echo '<a href="'.Request::root().'/login/twitter">Login with twitter</a>';
	}
	?>
</header>
<div id="main-nav-container" class="navbar nav">
	<div class="inner">
		<div id="logo-container">
			<div id="logo">
				<svg id="logo-svg" version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 width="200px" viewBox="0 0 700 220" xml:space="preserve">
				<g id="logo-dark-grey-bg">
					<polygon fill="#3C3C3C" points="61.466,61.082 110.178,11.851 179.891,68.336 201.649,143.322 139.15,205.823 102.373,203.215 
					46.446,146.734 "/>
				</g>
				<g id="logo-h">
					<path fill="#F16724" d="M74.963,175.25L75.3,46.726l-57.123,59.279c-3.079,3.079-3.079,8.071,0,11.146l28.269,29.583l-3.499,15.569
						l-5.54,24.672l24.14-7.537l13.413-4.191L74.963,175.25z"/>
					<path fill="#F16724" d="M227.82,106.004L127.496,5.679c-3.077-3.08-8.071-3.08-11.146,0l-12.558,12.558v67.52
						c4.09-7.439,9.658-13.275,16.7-17.503c7.046-4.225,14.733-6.341,23.066-6.341c13.783,0,24.274,4.271,31.469,12.808
						c7.194,8.544,10.793,20.931,10.793,37.166v47.264l42-42C230.9,114.069,230.9,109.083,227.82,106.004"/>
					<path fill="#F16724" d="M157.419,115.439c0-8.456-2.047-14.881-6.138-19.28c-4.089-4.396-9.995-6.596-17.722-6.596
						c-5.907,0-11.553,1.777-16.926,5.327c-5.381,3.551-9.661,8.288-12.841,14.207l-1.418,94.118l13.977,14.262
						c3.075,3.079,8.068,3.079,11.146,0l29.923-29.921V115.439z"/>
				</g>
				<g id="logo-ubbubb" class="xs-hidden">
					<path fill="#6D6E71" d="M271.021,128.374v63.581h-14.541v-11.215c-2.301,4.027-5.278,7.109-8.934,9.246
						c-3.655,2.136-7.785,3.204-12.383,3.204c-7.312,0-12.898-2.056-16.758-6.161c-3.86-4.11-5.79-10.022-5.79-17.747v-24.797
						l15.403-16.111v38.936c0,4.113,1.023,7.23,3.08,9.367c2.053,2.137,5.052,3.202,8.996,3.202c3.12,0,6.037-0.862,8.75-2.585
						c2.708-1.728,4.926-4.108,6.65-7.151v-41.769H271.021z"/>
					<path fill="#6D6E71" d="M324.497,127.144c8.872,0,16.12,3.037,21.747,9.119c5.625,6.08,8.44,13.963,8.44,23.654
						c0,9.615-2.854,17.562-8.563,23.845c-5.708,6.283-12.919,9.428-21.625,9.428c-4.189,0-8.176-0.97-11.953-2.899
						c-3.777-1.928-6.736-4.535-8.872-7.821v9.485h-15.401v-88.592l15.401-2.588v37.335c2.384-3.533,5.381-6.243,8.998-8.134
						C316.282,128.089,320.225,127.144,324.497,127.144 M320.924,180.005c5.667,0,10.104-1.789,13.308-5.362
						c3.202-3.572,4.805-8.521,4.805-14.849c0-6.076-1.603-10.842-4.805-14.292c-3.204-3.449-7.641-5.176-13.308-5.176
						c-3.449,0-6.717,0.945-9.795,2.836c-3.082,1.891-5.566,4.515-7.458,7.887v19.588c2.136,3.042,4.703,5.361,7.703,6.962
						C314.373,179.202,317.554,180.005,320.924,180.005"/>
					<path fill="#6D6E71" d="M404.092,127.144c8.874,0,16.123,3.037,21.75,9.119c5.625,6.08,8.44,13.963,8.44,23.654
						c0,9.615-2.854,17.562-8.562,23.845c-5.712,6.283-12.919,9.428-21.628,9.428c-4.186,0-8.174-0.97-11.95-2.899
						c-3.775-1.928-6.737-4.535-8.874-7.821v9.485h-15.398v-88.592l15.398-2.588v37.335c2.385-3.533,5.38-6.243,9-8.134
						C395.883,128.089,399.822,127.144,404.092,127.144 M400.52,180.005c5.67,0,10.106-1.789,13.311-5.362
						c3.201-3.572,4.802-8.521,4.802-14.849c0-6.076-1.601-10.842-4.802-14.292c-3.204-3.449-7.641-5.176-13.311-5.176
						c-3.447,0-6.714,0.945-9.793,2.836c-3.081,1.891-5.568,4.515-7.459,7.887v19.588c2.137,3.042,4.704,5.361,7.705,6.962
						C393.973,179.202,397.15,180.005,400.52,180.005"/>
					<path fill="#6D6E71" d="M505.258,128.374v63.581h-14.54v-11.215c-2.304,4.027-5.28,7.109-8.934,9.246
						c-3.656,2.136-7.785,3.204-12.386,3.204c-7.312,0-12.896-2.056-16.755-6.161c-3.862-4.11-5.792-10.022-5.792-17.747v-40.908h15.401
						v38.936c0,4.113,1.023,7.23,3.082,9.367c2.052,2.137,5.051,3.202,8.993,3.202c3.123,0,6.038-0.862,8.752-2.585
						c2.707-1.728,4.927-4.108,6.65-7.151v-41.769H505.258z"/>
					<path fill="#6D6E71" d="M558.729,127.144c8.874,0,16.124,3.037,21.75,9.119c5.626,6.08,8.441,13.963,8.441,23.654
						c0,9.615-2.854,17.562-8.562,23.845c-5.713,6.283-12.92,9.428-21.629,9.428c-4.187,0-8.174-0.97-11.949-2.899
						c-3.775-1.928-6.738-4.535-8.874-7.821v9.485h-15.399v-88.592l15.399-2.588v37.335c2.385-3.533,5.38-6.243,9-8.134
						C550.52,128.089,554.46,127.144,558.729,127.144 M555.156,180.005c5.669,0,10.107-1.789,13.312-5.362
						c3.2-3.572,4.802-8.521,4.802-14.849c0-6.076-1.602-10.842-4.802-14.292c-3.204-3.449-7.643-5.176-13.312-5.176
						c-3.447,0-6.714,0.945-9.792,2.836c-3.083,1.891-5.568,4.515-7.459,7.887v19.588c2.136,3.042,4.703,5.361,7.704,6.962
						C548.61,179.202,551.787,180.005,555.156,180.005"/>
					<path fill="#6D6E71" d="M638.33,127.144c8.87,0,16.12,3.037,21.748,9.119c5.624,6.08,8.439,13.963,8.439,23.654
						c0,9.615-2.854,17.562-8.562,23.845c-5.709,6.283-12.918,9.428-21.625,9.428c-4.188,0-8.174-0.97-11.953-2.899
						c-3.777-1.928-6.736-4.535-8.87-7.821v9.485h-15.401v-88.592l15.401-2.588v37.335c2.381-3.533,5.379-6.243,8.996-8.134
						C630.114,128.089,634.058,127.144,638.33,127.144 M634.758,180.005c5.666,0,10.104-1.789,13.308-5.362
						c3.202-3.572,4.806-8.521,4.806-14.849c0-6.076-1.604-10.842-4.806-14.292c-3.204-3.449-7.642-5.176-13.308-5.176
						c-3.453,0-6.718,0.945-9.796,2.836c-3.081,1.891-5.564,4.515-7.455,7.887v19.588c2.134,3.042,4.703,5.361,7.7,6.962
						C628.206,179.202,631.389,180.005,634.758,180.005"/>
				</g>
				</svg>
			</div>
		</div>
		<nav id="main-nav">
			<ul>
				<li><a href="<?php echo Request::root(); ?>/"><span class="nav-icon icon-home"></span>Home</a></li>
				<li><a href="<?php echo Request::root(); ?>/articles"><span class="nav-icon icon-articles"></span>Articles</a></li>
				<li><a href="<?php echo Request::root(); ?>/videos"><span class="nav-icon icon-opinions"></span>Videos</a></li>
				<li><a href="<?php echo Request::root(); ?>/community"><span class="nav-icon icon-about"></span>Community</a></li>
			</ul>
		</nav>
	</div>
</div>
<script>
var pages = [];
</script>
<div id="container">
<div id="wrapper">
<?php

}
?>
