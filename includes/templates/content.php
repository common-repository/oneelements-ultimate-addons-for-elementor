<?php

if( isset( $post_args['post_style'] ) ) :
	/**
	 * Contnet Ticker
	 */
	if( $post_args['post_style'] == 'ticker' ) :
		?>
		<div class="swiper-slide">
			<div class="ticker-content">
				<a href="<?php the_permalink(); ?>" class="ticker-content-link"><?php the_title(); ?></a>
			</div>
		</div>
	<?php
	endif;
	/**
	 * Content timeline
	 */
	if( $post_args['post_style'] == 'timeline' ) :
		?>
		<article class="one-elements-timeline-post one-elements-timeline-column">
			<div class="one-elements-timeline-bullet"></div>
			<div class="one-elements-timeline-post-inner">
				<a class="one-elements-timeline-post-link" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>">
					<time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time>
					<div class="one-elements-timeline-post-image" <?php if( $post_args['one_elements_show_image'] == 1 ){ ?> style="background-image: url('<?php echo wp_get_attachment_image_url(get_post_thumbnail_id(), $post_args['image_size'])?>');" <?php } ?>></div>
					<?php if($post_args['one_elements_show_excerpt']){ ?>
						<div class="one-elements-timeline-post-excerpt">
							<p><?php echo one_elements_get_excerpt_by_id( get_the_ID(), $post_args['one_elements_excerpt_length'] );?></p>
						</div>
					<?php } ?>

					<?php if($post_args['one_elements_show_title']){ ?>
						<div class="one-elements-timeline-post-title">
							<h2><?php the_title(); ?></h2>
						</div>
					<?php } ?>
				</a>
			</div>
		</article>
	<?php endif; ?>


	<?php
	if( $post_args['post_style'] == 'grid' ) :
		$post_hover_style = !empty($post_args['one_elements_post_grid_hover_style']) ? ' grid-hover-style-'.$post_args['one_elements_post_grid_hover_style'] : 'none';
		$post_carousel_image = wp_get_attachment_image_url(get_post_thumbnail_id(), $post_args['image_size']);
		?>
		<article class="one-elements-grid-post one-elements-post-grid-column">
			<div class="one-elements-grid-post-holder">
				<div class="one-elements-grid-post-holder-inner">
					<?php if ( $thumbnail_exists = has_post_thumbnail() && $post_args['one_elements_show_image'] == 1 ): ?>
						<div class="one-elements-entry-media<?php echo esc_attr($post_hover_style); ?>">

							<?php if('none' !== $post_args['one_elements_post_grid_hover_animation']) : ?>
								<div class="one-elements-entry-overlay<?php echo ' '.esc_attr($post_args['one_elements_post_grid_hover_animation']); ?>">
									<?php if( ! empty($post_args['one_elements_post_grid_bg_hover_icon']) ) : ?>
										<i class="<?php echo esc_attr($post_args['one_elements_post_grid_bg_hover_icon']); ?>" aria-hidden="true"></i>
									<?php else : ?>
										<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
									<?php endif; ?>
									<a href="<?php echo get_permalink(); ?>"></a>
								</div>
							<?php endif; ?>

							<?php if( ! empty($post_carousel_image) ) : ?>
								<div class="one-elements-entry-thumbnail">
									<img src="<?php echo esc_url($post_carousel_image); ?>">
								</div>
							<?php endif; ?>

						</div>
					<?php endif; ?>

					<?php
					if(!empty( $post_args['one_elements_show_title'])
					   ||!empty( $post_args['one_elements_show_meta'])
					   || !empty($post_args['one_elements_show_excerpt'])) :
					?>
					<div class="one-elements-entry-wrapper">
						<header class="one-elements-entry-header">
							<?php if(!empty( $post_args['one_elements_show_title'])){ ?>
								<h2 class="one-elements-entry-title"><a class="one-elements-grid-post-link" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
							<?php } ?>

							<?php if($post_args['one_elements_show_meta'] && $post_args['meta_position'] == 'meta-entry-header'){ ?>
								<div class="one-elements-entry-meta">
									<span class="one-elements-posted-by"><?php the_author_posts_link(); ?></span>
									<span class="one-elements-posted-on"><time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></span>
								</div>
							<?php } ?>
						</header>

						<?php if(!empty( $post_args['one_elements_show_excerpt'])){ ?>
							<div class="one-elements-entry-content">
								<div class="one-elements-grid-post-excerpt">
									<p><?php echo one_elements_get_excerpt_by_id(get_the_ID(),$post_args['one_elements_excerpt_length']);?></p>
								</div>
							</div>
						<?php } ?>
					</div>

					<?php if(!empty( $post_args['one_elements_show_meta']) && !empty( $post_args['meta_position']) && $post_args['meta_position'] == 'meta-entry-footer'){ ?>
						<div class="one-elements-entry-footer">
							<div class="one-elements-author-avatar">
								<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 96 ); ?> </a>
							</div>
							<div class="one-elements-entry-meta">
								<div class="one-elements-posted-by"><?php the_author_posts_link(); ?></div>
								<div class="one-elements-posted-on"><time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></div>
							</div>
						</div>
					<?php } ?>
				</div>
				<?php endif; ?>

			</div>
		</article>
	<?php endif;  //$post_args['post_style'] == 'post_carousel' ?>


<?php endif; ?>