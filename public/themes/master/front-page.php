<?php
/* Template Name: Home */

get_header();

$home = get_field('home');
$services = get_field('services');
$reviews = get_field('reviews');
$content = get_field('content');

?>

<main class="home-page">
    <div class="center">
        <?php if (!empty($home)) : ?>
            <div class="home">
                <?= $home ?>
            </div>
        <?php endif; ?>

        <?php if (!isArrayEmpty($services)) : ?>
            <div class="services">
                <?php if (!empty($services['title'])) : ?>
                    <h2 class="text-center"><?= $services['title']; ?></h2>
                <?php endif; ?>

                <div class="grid">
                    <?php foreach ($services['service'] as $service) : ?>
                    <div class="service">
                        <?php if (!empty($service['title'])) : ?>
                            <h3><?= $service['title']; ?></h3>
                        <?php endif; ?>

                        <?php if (!empty($service['image']['url'])) : ?>
                            <div class="image">
                                <img src="<?= $service['image']['url']; ?>" alt="<?= $service['image']['alt']; ?>">
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($service['text'])) : ?>
                            <div class="desc"><?= $service['text']; ?></div>
                        <?php endif; ?>

                        <?php if (!empty($service['link'])) : ?>
                            <?php $target = (!empty($service['link']['target'])) ? "target='_blank'" : '' ?>
                            <a href="<?= $service['link']['url'] ?>" class="button" <?= $target ?>>
                                <span><?= $service['link']['title'] ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!isArrayEmpty($reviews)) : ?>
            <div class="reviews">
                <?php if (!empty($reviews['title'])) : ?>
                    <h2 class="text-center"><?= $reviews['title']; ?></h2>
                <?php endif; ?>

                <div class="grid">
                    <?php foreach ($reviews['review'] as $review) : ?>
                        <div class="review">
                            <?php if (!empty($review['text'])) : ?>
                                <div class="desc"><?= $review['text']; ?></div>
                            <?php endif; ?>

                            <div class="flex items-center justify-between">
                                <?php if (!empty($review['title'])) : ?>
                                    <h4><?= $review['title']; ?></h4>
                                <?php endif; ?>

                                <?php if (!empty($review['image']['url'])) : ?>
                                    <div class="image">
                                        <img src="<?= $review['image']['url']; ?>" alt="<?= $review['image']['alt']; ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($content)) : ?>
            <div class="content">
                <?= $content ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
