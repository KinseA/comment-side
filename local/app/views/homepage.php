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
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum maximus metus non nibh vulputate, quis pharetra dui scelerisque. Donec sem mauris, lobortis sit amet sollicitudin ut, auctor nec odio. Nam consectetur, odio at blandit condimentum, lectus mauris accumsan massa, in lacinia metus felis a purus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut metus urna, ullamcorper vitae tellus eu, egestas sollicitudin arcu. Curabitur sed dui sit amet massa tempus posuere mollis sed ipsum. Duis velit elit, condimentum eget aliquam sodales, dapibus vel augue. Curabitur ex dui, dictum sed augue ac, viverra feugiat mauris. Vivamus eu tortor vel justo vulputate pulvinar at sit amet nisl. Maecenas ullamcorper mauris lectus, sed facilisis mauris sagittis lacinia. Nam eleifend laoreet mauris, non imperdiet arcu imperdiet et. Nullam aliquet ipsum sit amet neque luctus pellentesque. Curabitur vitae turpis eu augue fermentum consectetur.</p>
		</div>
	</section>
</div>
<?php 
echo $footer;
?>