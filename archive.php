<?php if ( is_post_type_archive() ): ?>
	<?php do_action( 'voxel/post-type-archive', \Voxel\Post_Type::get( get_queried_object() ) ) ?>
<?php else: ?>
	<?php get_header() ?>
	<?php \Voxel\print_header() ?>

	<div class="archive-page">
		<h1><?php the_archive_title() ?></h1>
		<p><?php the_archive_description() ?></p>

		<?php if ( have_posts() ): ?>
			<ul>
				<?php while ( have_posts() ): the_post(); ?>
					<li>
						<h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
						<p><?php the_excerpt() ?></p>
					</li>
				<?php endwhile ?>
			</ul>

			<?php echo paginate_links() ?>
		<?php else: ?>
			<p>No results. Try another search.</p>
		<?php endif ?>
	</div>

	<?php \Voxel\print_footer() ?>
	<?php get_footer() ?>
<?php endif ?>
