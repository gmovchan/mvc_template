<div class="inner">
    <h3 class="masthead-brand"><?php echo $data['title']; ?></h3>
    <nav>
        <ul class="nav masthead-nav">
            <li class="<?php if ($data['thisPage'] === 'index') echo 'active' ?>"><a href="/">Главная</a></li>
            <li class="<?php if ($data['thisPage'] === 'about') echo 'active' ?>"><a href="/about" title="О сайте"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></li>
        </ul>
    </nav>
</div>
