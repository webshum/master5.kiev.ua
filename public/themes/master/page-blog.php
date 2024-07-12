<?php
/* Template Name: Blog */

get_header();
?>

<main class="main-blog">
    <div class="center">
        <div class="content-blog">
            <h1 class="text-center"><?php the_title(); ?></h1>

            <?php
                $args = [
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    'lang' => pll_current_language()
                ];

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) :
                        $query->the_post();
                        ?>
                        <article class="block mb-[30px] overflow-hidden">
                            <h2 class="mt-[20px] !mt-[0px] hidden"><?php the_title(); ?></h2>

                            <div class="thumb float-left mr-[20px] mb-[10px]">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail(); ?>
                                <?php else : ?>
                                    <img src="<?= bloginfo('template_url'); ?>/img/no-image.png" alt="">
                                <?php endif; ?>
                            </div>

                            <h2 class="mt-[0px]"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p><?= wp_trim_words(get_the_excerpt(), 40); ?></p>
                            <a href="<?php the_permalink(); ?>" class="button mt-[15px] inline-block"><?php pll_e('More') ?></a>
                        </article>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
            ?>
        </div>
        <aside class="sidebar">
            <h3><?php pll_e('Fresh comments') ?></h3>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5, // Кількість записів для виведення
                    'post_status' => 'publish'
                ));
                foreach ($recent_posts as $post) :
                    ?>
                    <li><a href="<?php echo get_permalink($post['ID']); ?>"><?php echo $post['post_title']; ?></a></li>
                <?php endforeach; ?>
            </ul>

            <h3><?php pll_e('Fresh comments') ?></h3>
            <?php
            $comments = get_comments([
                'number' => 5,
                'status' => 'approve'
            ]);

            echo '<ul>';
            foreach ($comments as $comment) :
                echo '<li><a href="' . get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '"><strong>' . $comment->comment_author . '</strong>: ' . $comment->comment_content . '</a></li>';
            endforeach;
            echo '</ul>';
            ?>
        </aside>
    </div>
</main>

<?php get_footer(); ?>
