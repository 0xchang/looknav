<?php
/**
 * 搜索结果页
 *
 * @package LookNav
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<div class="nav-detail">
    <div class="nav-detail-card">
        <h1 class="nav-detail-title">
            <?php $this->archiveTitle(array(
                'search'    => _t('包含关键字 %s 的站点'),
            ), '', ''); ?>
        </h1>
        <div class="nav-detail-info">
            <span class="info-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
                <?php $this->archiveTitle('', '', ''); ?>
            </span>
        </div>
    </div>

    <?php if ($this->have()): ?>
        <div class="nav-grid" style="margin-top:24px;">
            <?php while ($this->next()): ?>
                <?php
                $url = getNavUrl($this);
                $navurl = $this->fields->navurl ?? '';
                $favicon = $this->fields->navicon ?? '';
                $favicon = $favicon ?: ($navurl ? getFavicon($navurl) : '');
                $title = htmlspecialchars($this->title);
                ?>
                <div class="nav-card">
                    <a class="nav-card-main" href="<?php $this->permalink(); ?>" title="<?php echo $title; ?>">
                        <div class="nav-card-icon">
                            <img src="<?php echo $favicon; ?>" alt="" loading="lazy" onerror="this.style.display='none'">
                            <span class="nav-card-fallback"><?php echo mb_substr($title, 0, 1, 'UTF-8'); ?></span>
                        </div>
                        <div class="nav-card-body">
                            <h3 class="nav-card-title"><?php echo $title; ?></h3>
                        </div>
                    </a>
                    <a class="nav-card-jump" href="<?php echo $url; ?>" target="_blank" rel="noopener" title="<?php _e('直接跳转'); ?>" onclick="event.stopPropagation();">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7"></path><path d="M7 7h10v10"></path></svg>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
        <?php $this->pageNav('&laquo;', '&raquo;'); ?>
    <?php else: ?>
        <div class="nav-detail-content" style="text-align:center;padding:48px 24px;">
            <p style="font-size:1.125rem;color:var(--color-muted);margin-bottom:16px;"><?php _e('没有找到相关站点'); ?></p>
            <a class="btn btn-primary" href="<?php $this->options->siteUrl(); ?>"><?php _e('返回首页'); ?></a>
        </div>
    <?php endif; ?>
</div>

<?php $this->need('footer.php'); ?>
