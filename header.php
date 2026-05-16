<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php $this->archiveTitle([
            'category' => _t('分类 %s 下的文章'),
            'search'   => _t('包含关键字 %s 的文章'),
            'tag'      => _t('标签 %s 下的文章'),
            'author'   => _t('%s 发布的文章')
        ], '', ' - '); ?><?php $this->options->title(); ?></title>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>?v=2">
    <script src="<?php $this->options->themeUrl('lunar.js'); ?>?v=2"></script>
    <script>
    (function() {
        var key = 'looknav_theme';
        var saved = localStorage.getItem(key);
        if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('themeToggle');
            if (!btn) return;
            btn.addEventListener('click', function() {
                var html = document.documentElement;
                var isDark = html.getAttribute('data-theme') === 'dark';
                if (isDark) {
                    html.removeAttribute('data-theme');
                    localStorage.setItem(key, 'light');
                } else {
                    html.setAttribute('data-theme', 'dark');
                    localStorage.setItem(key, 'dark');
                }
            });
        });
    })();
    </script>
    <?php if ($this->options->bgUrl): ?>
    <style>
        body {
            background: url('<?php echo $this->options->bgUrl; ?>') center/cover no-repeat fixed;
        }
        .site-header,
        .nav-card {
            backdrop-filter: blur(16px) saturate(1.2);
            -webkit-backdrop-filter: blur(16px) saturate(1.2);
        }
    </style>
    <?php endif; ?>
    <?php $this->header(); ?>
</head>
<body>
<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <a class="logo" href="<?php $this->options->siteUrl(); ?>">
                <?php if ($this->options->logoUrl): ?>
                    <img src="<?php $this->options->logoUrl() ?>" alt="<?php $this->options->title() ?>">
                <?php else: ?>
                    <span class="logo-text"><?php $this->options->title() ?></span>
                <?php endif; ?>
            </a>
            <p class="site-desc"><?php $this->options->description() ?></p>
            <div class="header-time" id="headerTimeBox">
                <span id="headerTime">--:--:--</span>
                <div class="header-time-tooltip">
                    <div class="tt-row"><span class="tt-label">农历</span><span class="tt-value" id="ttLunar">--</span></div>
                    <div class="tt-row"><span class="tt-label">干支</span><span class="tt-value" id="ttGanZhi">--</span></div>
                    <div class="tt-row"><span class="tt-label">生肖</span><span class="tt-value" id="ttAnimal">--</span></div>
                    <div class="tt-row"><span class="tt-label">节气</span><span class="tt-value" id="ttTerm">--</span></div>
                    <div class="tt-row"><span class="tt-label">节日</span><span class="tt-value" id="ttFestival">--</span></div>
                </div>
            </div>
            <button type="button" class="theme-toggle" id="themeToggle" title="<?php _e('切换主题'); ?>">
                <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path></svg>
                <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </button>
            <nav class="main-nav">
                <a<?php if ($this->is('index')): ?> class="current"<?php endif; ?> href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a>
                <?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
                <?php while ($pages->next()): ?>
                    <a<?php if ($this->is('page', $pages->slug)): ?> class="current"<?php endif; ?> href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a>
                <?php endwhile; ?>
            </nav>
        </div>
    </div>
</header>
<div class="site-body">
    <div class="container">
