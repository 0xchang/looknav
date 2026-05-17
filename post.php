<?php
/**
 * 导航详情页
 *
 * @package LookNav
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');

$url = getNavUrl($this);
$favicon = $this->fields->navicon ?? '';
$favicon = $favicon ?: ($url ? getFavicon($url) : '');
$title = htmlspecialchars($this->title);
?>

<div class="nav-detail">
    <div class="nav-detail-card">
        <div class="nav-detail-icon">
            <img src="<?php echo $favicon; ?>" alt="" onerror="this.style.display='none'">
            <span class="nav-detail-fallback"><?php echo mb_substr($title, 0, 1, 'UTF-8'); ?></span>
        </div>
        <h1 class="nav-detail-title"><?php echo $title; ?></h1>
        <div class="nav-detail-info">
            <?php if ($this->categories): ?>
                <span class="info-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    <?php _e('分类'); ?>：<?php $this->category(','); ?>
                </span>
            <?php endif; ?>
            <?php if ($this->tags): ?>
                <span class="info-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    <?php _e('标签'); ?>：<?php $this->tags(','); ?>
                </span>
            <?php endif; ?>
            <span class="info-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <?php _e('时间'); ?>：<?php $this->date('Y-m-d'); ?>
            </span>
        </div>
        <?php if ($url !== $this->permalink): ?>
            <p class="nav-detail-url"><?php echo htmlspecialchars($url); ?></p>
        <?php endif; ?>
        <div class="nav-detail-actions">
            <a class="btn btn-primary" href="<?php echo $url; ?>" target="_blank" rel="noopener">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7"></path><path d="M7 7h10v10"></path></svg>
                <?php _e('访问网站'); ?>
            </a>
            <a class="btn btn-secondary" href="<?php $this->options->siteUrl(); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"></path><path d="M12 19l-7-7 7-7"></path></svg>
                <?php _e('返回首页'); ?>
            </a>
        </div>
    </div>

    <?php if ($this->content): ?>
        <div class="nav-detail-content">
            <h2><?php _e('站点介绍'); ?></h2>
            <div class="content-body">
                <?php $this->content(); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="nav-detail-meta">
        <div class="meta-row">
            <span class="meta-label"><?php _e('本文作者'); ?>：</span>
            <span class="meta-value"><?php $this->author(); ?></span>
        </div>
        <div class="meta-row">
            <span class="meta-label"><?php _e('原文链接'); ?>：</span>
            <a class="meta-link" href="<?php echo htmlspecialchars($this->permalink); ?>" target="_blank" rel="noopener">
                <?php echo $title; ?>
            </a>
        </div>
        <div class="meta-notice meta-notice--warning">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <div>
                <strong><?php _e('免责声明'); ?>：</strong>
                <?php _e('文中如涉及第三方资源，均来自互联网，仅供学习研究，禁止商业使用，如有侵权，联系我们24小时内删除！'); ?>
            </div>
        </div>
        <div class="meta-notice meta-notice--info">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <div>
                <strong><?php _e('安全声明'); ?>：</strong>
                <?php _e('鉴于网络服务的特殊性，本站难以保证所收录网址的正确性或可靠性，请仔细识别你所访问的网站，注意您的个人隐私和财产安全。'); ?>
            </div>
        </div>
    </div>

    <?php $this->need('comments.php'); ?>
</div>

<?php $this->need('footer.php'); ?>
