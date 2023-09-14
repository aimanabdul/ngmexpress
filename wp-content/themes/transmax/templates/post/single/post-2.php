<?php

$postID = get_the_ID();

$single = Transmax_Single_Post::getInstance();
$single->set_post_data();
$single->set_image_data();
$single->set_post_views($postID);

$hide_featured = WGL_Framework::get_mb_option('post_hide_featured_image', 'mb_post_hide_featured_image', true);

$hide_all_meta = WGL_Framework::get_option('single_meta');
$use_author_info = WGL_Framework::get_option('single_author_info');
$use_tags = WGL_Framework::get_option('single_meta_tags') && has_tag();
$use_shares = WGL_Framework::get_option('single_share') && function_exists('wgl_extensions_social');
$use_likes = WGL_Framework::get_option('single_likes') && function_exists('wgl_simple_likes');
$use_views = WGL_Framework::get_option('single_views');

$has_media = $single->meta_info_render;

$meta_cats_data = $meta_data = [];
if (!$hide_all_meta) {
	$meta_data['comments'] = !WGL_Framework::get_option('single_meta_comments');
	$meta_data['date'] = !WGL_Framework::get_option('single_meta_date');
    $meta_data['author'] = !WGL_Framework::get_option('single_meta_author');
    $meta_cats_data['category'] = !WGL_Framework::get_option('single_meta_categories');
}

$use_likes = WGL_Framework::get_option('single_likes') && function_exists('wgl_simple_likes');
$use_views = WGL_Framework::get_option('single_views');
$meta_cats = WGL_Framework::get_option('single_meta_categories');

// Render?>
<article class="blog-post blog-post-single-item format-<?php echo esc_attr( $single->get_pf() ); ?>">
	<div <?php post_class( 'single_meta' ); ?>>
		<div class="item_wrapper">
			<div class="blog-post_content"><?php

			    // Media
			    $single->render_featured();

				// Categories
				if (!$meta_cats) {
					$single->render_post_meta($meta_cats_data, false);
				}

                // Title
				?><h1 class="blog-post_title"><?php echo get_the_title(); ?></h1><?php

				// Meta Data ?>
				<div class="meta_wrapper"><?php

					if (!$hide_all_meta) $single->render_post_meta($meta_data);

					if ( $use_views || $use_likes) { ?>
						<div class="meta-data"><?php

						// Views
						echo ( (bool)$use_views ? $single->get_post_views(get_the_ID()) : '' );

						// Likes
						if ($use_likes) {
							wgl_simple_likes()->likes_button(get_the_ID(), 0);
						}?>
						</div><?php
					}?>
				</div><?php

			    // Content
			    the_content();

				WGL_Framework::link_pages();

				if ( $use_tags || $use_shares ) {
				    ?><div class="clear"></div><div class="single_post_info"><?php

                        // Socials
                        if ($use_shares) {
							echo '<div class="share_post-container">';
								echo '<div class="share_post-title">'.esc_html__('SHARE ARTICLE','transmax').'</div>';
								wgl_extensions_social()->render_post_share();
							echo '</div>';
						}

                        // Tags
                        if ((bool)$use_tags) {
                            the_tags('<div class="tagcloud-wrapper"><div class="tagcloud">', ' ', '</div></div>');
                        }

                        ?>
                    </div><?php
				}

			    // Author Info
			    if ($use_author_info) {
			        $single->render_author_info();
			    }?>
                <div class="clear"></div>
			</div><!--blog-post_content-->
		</div><!--item_wrapper-->
	</div>
</article>
