<?php 
echo $header;
?>
<div class="homepage content">
	<section id="featured">
		<header>
			<h1><span class="left">Featured</span><span class="right">Articles</span></h1>
		</header>
		<div id="features">
			<?php
			foreach ($features as $f){
				?>
			<article id="feature-<?php echo $f->id; ?>" class="feature-box" style="background-image:url(<?php echo Request::root(); ?>/<?php echo $f->bg_url; ?>)">
				<div class="feature">
					<button class="changer"></button>
					<div class="feature-ribbon">
						<h3 class="headline"><a href="<?php echo Request::root(); ?>/article/view/<?php echo $f->slug; ?>-<?php echo $f->unique_id; ?>"><?php echo $f->title; ?></a></h3>
						<span class="article-author">By <a href="<?php echo Request::root(); ?>/profile/<?php echo $f->username; ?>"><?php echo $f->username; ?></a></span>
					</div>
					<div class="feature-image" style="background-image:url('<?php echo $f->bg_url; ?>')">

					</div>
					<div class="feature-opinions">

					</div>
				</div>
			</article>
			<?php
				}
			?>

		</div>
	</section>
</div>
<?php 
echo $footer;
?>