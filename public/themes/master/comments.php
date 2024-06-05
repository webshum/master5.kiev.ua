<?php
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if ( have_comments() ) : ?>
        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ol',
                'short_ping' => true
            ) );
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

    <?php endif; ?>

    <?php
    if ( ! comments_open() && get_comments_number() ) :
        ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'textdomain' ); ?></p>
    <?php
    endif;

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields = [
        'author' =>
            '<p class="comment-form-author">' .
            '<label for="author">' . pll__('Name') . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',

        'email' =>
            '<p class="comment-form-email"><label for="email">' . pll__('Email') . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
            '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',

        'url' =>
            '<p class="comment-form-url"><label for="url">' . pll__('Website') . '</label>' .
            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
    ];

    $args = [
        'fields' => apply_filters( 'comment_form_default_fields', $fields ),
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . pll__('Comment') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
        'must_log_in' => '<p class="must-log-in">' . pll__('You must be logged in to post a comment.'), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) . '</p>',
        'logged_in_as' => '<p class="logged-in-as">' . pll__('Logged in as. Log out?'), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) . '</p>',
        'comment_notes_before' => '<p class="comment-notes">' . pll__('Required fields are marked *') . '</p>',
        'comment_notes_after' => '',
        'title_reply' => pll__('Leave a Reply'),
        'title_reply_to' => pll__('Leave a Reply to'),
        'cancel_reply_link' => pll__('Cancel Reply'),
        'label_submit' => pll__('Post Comment'),
    ];

    comment_form( $args );
    ?>
</div>
