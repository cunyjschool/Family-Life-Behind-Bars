<?php get_header(); ?>

<div id="content" class="section">
<?php arras_above_content() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php arras_above_post() ?>
	<div id="post-<?php the_ID() ?>" <?php arras_single_post_class() ?>>

        <?php arras_postheader(); ?>

		<?php flbb_share_this(); ?>
        
        <div class="entry-content clearfix">
		<?php the_content( __('<p>Read the rest of this entry &raquo;</p>', 'arras') ); ?>  
        <?php wp_link_pages(array('before' => __('<p><strong>Pages:</strong> ', 'arras'), 
			'after' => '</p>', 'next_or_number' => 'number')); ?>
			
		<?php if ( in_category('photo-of-the-week') ) { ?>	
			<div class="clearfix">
				<div class="left"><?php previous_post_link('&laquo; %link', '%title', TRUE); ?></div>
				<div class="right"><?php next_post_link('%link  &raquo;', '%title', TRUE); ?></div>
			</div>
		<?php } ?>
		</div>

		<?php arras_postfooter() ?>

        <?php 
		if ( arras_get_option('display_author') ) {
			arras_post_aboutauthor();
		}
        ?>
    </div>
	
	<?php if ( in_category('photo-of-the-week') ) { ?>	
		<h4 class="module-title">Photo of the Week Archive</h4>
		<div class="nocomments no-bottom-pads">
			<ol id="foo">
				<?php 
					$args = array('category_name' => 'photo-of-the-week','posts_per_page' => 15);
					$featured = new WP_Query($args); 
					if( $featured->have_posts()) { 
						while($featured->have_posts()) : $featured->the_post(); ?>
							<li><a href="<?php echo the_permalink(); ?>" title="<?php the_title(); ?>" ><img src="<?php echo catch_that_image() ?>" class="avatar" alt="<?php the_title(); ?>" /></a></li>
						<?php endwhile; 
					} 
				?>
			</ol>
			<div class="clearfix"></div>
	   	</div>
	<?php } ?>

	<?php arras_below_post() ?>
	<a name="comments"></a>
    <?php comments_template('', true); ?>
	<?php arras_below_comments() ?>
    
<?php endwhile; else: ?>

<?php arras_post_notfound() ?>

<?php endif; ?>

<?php arras_below_content() ?>
</div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>