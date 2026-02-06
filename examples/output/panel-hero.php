<section class="panel-hero">

    <div class="container">

        <h2 class="heading heading-2">
            <?php include('components/heading.php'); ?>
        </h2>

        <?php include('components/content.php'); ?>

        <?php include('components/image.php'); ?>

        <?php $link = get_sub_field('button'); ?>
        <?php if ($link): ?>
            <a href="<?php echo esc_url($link['url']); ?>" target="<?php echo esc_attr($link['target']); ?>"><?php echo esc_html($link['title']); ?></a>
        <?php endif; ?>

    </div>

</section>
