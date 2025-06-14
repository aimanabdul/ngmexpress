<?php
defined('ABSPATH') || exit;

if (!class_exists('Transmax_Walker_Comment')) {
    /**
     * Transmax Theme Helper
     *
     *
     * @category Class
     * @package transmax\core\class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Transmax_Walker_Comment extends Walker_Comment {
        public function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
            $depth++;
            $GLOBALS['comment_depth'] = $depth;
            $GLOBALS['comment']       = $comment;
            if ( ! empty( $args['callback'] ) ) {
                ob_start();
                call_user_func( $args['callback'], $comment, $args, $depth );
                $output .= ob_get_clean();
                return;
            }
            if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
                ob_start();
                $this->ping( $comment, $depth, $args );
                $output .= ob_get_clean();
            } else {
                ob_start();
                $this->comment( $comment, $depth, $args );
                $output .= ob_get_clean();
            }
        }


        protected function ping( $comment, $depth, $args ) {
            $tag = ( 'div' == $args['style'] ) ? 'div' : 'li';
        ?>
            <<?php echo WGL_Framework::render_html($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( '', $comment ); ?>>
                <div class="comment-body stand_comment">
                    <?php esc_html_e( 'Pingback:', 'transmax' ); ?> <?php comment_author_link( $comment ); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'transmax' ), '<span class="edit-link">', '</span>' ); ?>
                </div>
        <?php
        }

        protected function comment($comment, $depth, $args)
        {
            $max_depth_comment = $args['max_depth'] > 4 ? 4 : $args['max_depth'];

            $GLOBALS['comment'] = $comment; ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <div id="comment-<?php comment_ID(); ?>" class="stand_comment">
                <div class="thiscommentbody">
                    <div class="commentava">
                        <?php echo get_avatar($comment->comment_author_email, 120); ?>
                    </div>
                    <div class="comment_info">
                        <div class="comment_author_says"><?php echo esc_html__('by ', 'transmax'); printf('%s', get_comment_author_link()) ?></div>
                        <div class="meta-data">
                            <span><?php printf('%1$s', get_comment_date()) ?></span>
                            <?php edit_comment_link('<span>('.esc_html__('Edit', 'transmax').')</span>', '  ', '') ?>
                        </div>
                    </div>
                    <div class="comment_content">
                        <?php if ($comment->comment_approved == '0') : ?>
                            <p><?php esc_html_e('Your comment is awaiting moderation.', 'transmax'); ?></p>
                        <?php endif; ?>
                        <?php comment_text() ?>
                    </div>
                    <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'before' => '<span class="comment-reply-wrapper">', 'after' => '</span>', 'reply_text' => '' . esc_html__('Reply', 'transmax'), 'max_depth' => $max_depth_comment))) ?>
                </div>
            </div>
            <?php

        }
    }
}