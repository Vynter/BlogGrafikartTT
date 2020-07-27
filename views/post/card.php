<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
        <p class="text-muted">
            <?= $post->getCreatedAt()->format('d F Y') ?>::
            <?php foreach ($post->getCategories() as $k => $category) : ?>
            <?php if ($k > 0) : ?><?php echo ', '; ?><?php endif ?>
            <a
                href="<?= $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]); ?>"><?= e($category->getName()) ?></a>
            <?php endforeach ?>
        </p>
        <p><?= /*nl2br(htmlentities(Text::excerpt($post->content)))*/ $post->getExcerpt(); ?></p>
        <p>
            <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]) ?>"
                class="btn btn-primary">Voir plus</a>
        </p>
    </div>
</div>