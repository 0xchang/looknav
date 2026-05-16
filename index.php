<?php
/**
 * LookNav - 简约网址导航主题
 *
 * @package LookNav
 * @author Typecho
 * @version 1.0
 * @link https://typecho.org
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<div class="nav-page">
    <?php $categories = getNavCategories(); ?>
    <?php if (!empty($categories)): ?>
        <aside class="nav-sidebar">
            <div class="nav-sidebar-inner">
                <h3 class="nav-sidebar-title"><?php _e('分类目录'); ?></h3>
                <nav class="nav-sidebar-menu">
                    <a href="javascript:void(0)" class="nav-sidebar-link" id="sidebarFav" style="display:none;">
                        <span><?php _e('我的收藏'); ?></span>
                        <span class="nav-sidebar-count" id="sidebarFavCount">0</span>
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="#cat-<?php echo $category['slug']; ?>" class="nav-sidebar-link" data-show-all="1">
                            <span><?php echo htmlspecialchars($category['name']); ?></span>
                            <span class="nav-sidebar-count"><?php echo $category['count']; ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </aside>

        <div class="nav-main">
            <div class="nav-time" id="navTime">
                <div class="nav-time-clock" id="navTimeClock">--:--:--</div>
                <div class="nav-time-date" id="navTimeDate">----年--月--日 星期-</div>
            </div>

            <div class="nav-search">
                <div class="nav-search-engines">
                    <button type="button" class="nav-search-engine active" data-engine="baidu">百度</button>
                    <button type="button" class="nav-search-engine" data-engine="bing">Bing</button>
                    <button type="button" class="nav-search-engine" data-engine="google">Google</button>
                </div>
                <div class="nav-search-box">
                    <input type="text" id="searchInput" placeholder="输入关键词搜索..." autocomplete="off">
                    <button type="button" id="searchBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
                    </button>
                </div>
            </div>

            <div id="favContainer" style="display:none;">
                <section class="nav-section">
                    <h2 class="nav-section-title">
                        <span class="nav-section-icon" style="background:linear-gradient(135deg,#f59e0b,#ef4444);">★</span>
                        <?php _e('我的收藏'); ?>
                        <span class="nav-section-count" id="favCount">0</span>
                    </h2>
                    <div class="nav-grid" id="favGrid"></div>
                </section>
            </div>

            <div id="categoriesContainer">
                <?php foreach ($categories as $category): ?>
                    <section class="nav-section" id="cat-<?php echo $category['slug']; ?>">
                        <h2 class="nav-section-title">
                            <span class="nav-section-icon">#</span>
                            <?php echo htmlspecialchars($category['name']); ?>
                            <span class="nav-section-count"><?php echo $category['count']; ?></span>
                        </h2>
                        <div class="nav-grid">
                            <?php foreach ($category['items'] as $post): ?>
                                <?php
                                $navurl = $post['fields']->navurl ?? '';
                                $url = $navurl ?: $post['permalink'];
                                $favicon = $post['fields']->navicon ?? '';
                                $favicon = $favicon ?: ($navurl ? getFavicon($navurl) : '');
                                $title = htmlspecialchars($post['title']);
                                $excerpt = strip_tags($post['text']);
                                $excerpt = mb_strlen($excerpt, 'UTF-8') > 60 ? mb_substr($excerpt, 0, 60, 'UTF-8') . '…' : $excerpt;
                                ?>
                                <div class="nav-card">
                                    <a class="nav-card-main" href="<?php echo $post['permalink']; ?>" title="<?php echo $title; ?>">
                                        <div class="nav-card-icon">
                                            <img src="<?php echo $favicon; ?>" alt="" loading="lazy" onerror="this.style.display='none'">
                                            <span class="nav-card-fallback"><?php echo mb_substr($title, 0, 1, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="nav-card-body">
                                            <h3 class="nav-card-title"><?php echo $title; ?></h3>
                                            <?php if ($excerpt): ?>
                                                <p class="nav-card-desc"><?php echo htmlspecialchars($excerpt); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <a class="nav-card-jump" href="<?php echo $url; ?>" target="_blank" rel="noopener" title="<?php _e('直接跳转'); ?>" onclick="event.stopPropagation();">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7"></path><path d="M7 7h10v10"></path></svg>
                                    </a>
                                    <button type="button" class="nav-fav-btn"
                                        data-cid="<?php echo $post['cid']; ?>"
                                        data-title="<?php echo str_replace('"', '&quot;', $title); ?>"
                                        data-url="<?php echo $url; ?>"
                                        data-favicon="<?php echo $favicon; ?>"
                                        data-permalink="<?php echo $post['permalink']; ?>"
                                        title="<?php _e('收藏'); ?>">
                                        <svg class="fav-icon-empty" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                        <svg class="fav-icon-filled" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>

            <script>
            (function() {
                function updateTime() {
                    var now = new Date();
                    var days = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
                    var y = now.getFullYear();
                    var m = String(now.getMonth() + 1).padStart(2, '0');
                    var d = String(now.getDate()).padStart(2, '0');
                    var h = String(now.getHours()).padStart(2, '0');
                    var i = String(now.getMinutes()).padStart(2, '0');
                    var s = String(now.getSeconds()).padStart(2, '0');
                    document.getElementById('navTimeClock').textContent = h + ':' + i + ':' + s;
                    document.getElementById('navTimeDate').textContent = y + '年' + m + '月' + d + '日 ' + days[now.getDay()];
                }
                updateTime();
                setInterval(updateTime, 1000);

                // Search
                var currentEngine = 'baidu';
                var engines = document.querySelectorAll('.nav-search-engine');
                var input = document.getElementById('searchInput');
                var btn = document.getElementById('searchBtn');
                var urls = {
                    baidu: 'https://www.baidu.com/s?wd=',
                    bing: 'https://www.bing.com/search?q=',
                    google: 'https://www.google.com/search?q='
                };

                engines.forEach(function(el) {
                    el.addEventListener('click', function() {
                        engines.forEach(function(e) { e.classList.remove('active'); });
                        this.classList.add('active');
                        currentEngine = this.dataset.engine;
                        input.focus();
                    });
                });

                function doSearch() {
                    var kw = input.value.trim();
                    if (!kw) { input.focus(); return; }
                    window.open(urls[currentEngine] + encodeURIComponent(kw), '_blank');
                }

                btn.addEventListener('click', doSearch);
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') doSearch();
                });

                // Favorites
                document.addEventListener('DOMContentLoaded', function() {
                    var FAV_KEY = 'looknav_favorites';
                    function getFavs() { try { return JSON.parse(localStorage.getItem(FAV_KEY)) || []; } catch(e) { return []; } }
                    function saveFavs(list) { localStorage.setItem(FAV_KEY, JSON.stringify(list)); }

                    var favContainer = document.getElementById('favContainer');
                    var categoriesContainer = document.getElementById('categoriesContainer');
                    var sidebarFav = document.getElementById('sidebarFav');
                    var sidebarFavCount = document.getElementById('sidebarFavCount');

                    function renderFavs() {
                        var favs = getFavs();
                        var grid = document.getElementById('favGrid');
                        var count = document.getElementById('favCount');
                        if (sidebarFav && sidebarFavCount) {
                            if (favs.length > 0) {
                                sidebarFav.style.display = 'flex';
                                sidebarFavCount.textContent = favs.length;
                            } else {
                                sidebarFav.style.display = 'none';
                            }
                        }
                        if (!grid || !count) return;
                        count.textContent = favs.length;
                        grid.innerHTML = favs.map(function(f) {
                            return '<div class="nav-card">' +
                                '<a class="nav-card-main" href="' + f.permalink + '" title="' + f.title + '">' +
                                    '<div class="nav-card-icon">' +
                                        '<img src="' + f.favicon + '" alt="' + f.title + '" loading="lazy" onerror="this.style.display=\'none\'">' +
                                        '<span class="nav-card-fallback">' + f.title.charAt(0) + '</span>' +
                                    '</div>' +
                                    '<div class="nav-card-body">' +
                                        '<h3 class="nav-card-title">' + f.title + '</h3>' +
                                    '</div>' +
                                '</a>' +
                                '<a class="nav-card-jump" href="' + f.url + '" target="_blank" rel="noopener" title="直接跳转" onclick="event.stopPropagation();">' +
                                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7"></path><path d="M7 7h10v10"></path></svg>' +
                                '</a>' +
                                '<button type="button" class="nav-fav-btn active" data-cid="' + f.cid + '" data-title="' + f.title.replace(/"/g, '&quot;') + '" data-url="' + f.url + '" data-favicon="' + f.favicon + '" data-permalink="' + f.permalink + '" title="取消收藏">' +
                                    '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>' +
                                '</button>' +
                            '</div>';
                        }).join('');
                    }

                    function updateFavBtns() {
                        var favs = getFavs();
                        var cids = favs.map(function(f) { return f.cid; });
                        document.querySelectorAll('.nav-fav-btn').forEach(function(btn) {
                            var isActive = cids.indexOf(btn.dataset.cid) >= 0;
                            btn.classList.toggle('active', isActive);
                            btn.title = isActive ? '取消收藏' : '收藏';
                        });
                    }

                    function toggleFav(btn) {
                        var cid = btn.dataset.cid;
                        var favs = getFavs();
                        var idx = favs.findIndex(function(f) { return f.cid == cid; });
                        if (idx >= 0) {
                            favs.splice(idx, 1);
                        } else {
                            favs.push({
                                cid: cid,
                                title: btn.dataset.title,
                                url: btn.dataset.url,
                                favicon: btn.dataset.favicon,
                                permalink: btn.dataset.permalink
                            });
                        }
                        saveFavs(favs);
                        renderFavs();
                        updateFavBtns();
                    }

                    // 事件委托
                    document.querySelector('.nav-main').addEventListener('click', function(e) {
                        var btn = e.target.closest('.nav-fav-btn');
                        if (btn) {
                            e.stopPropagation();
                            e.preventDefault();
                            toggleFav(btn);
                        }
                    });

                    function showFavorites() {
                        if (favContainer) favContainer.style.display = 'block';
                        if (categoriesContainer) categoriesContainer.style.display = 'none';
                        renderFavs();
                        document.querySelectorAll('.nav-sidebar-link').forEach(function(a) { a.classList.remove('current'); });
                        if (sidebarFav) sidebarFav.classList.add('current');
                    }

                    function showAll() {
                        if (favContainer) favContainer.style.display = 'none';
                        if (categoriesContainer) categoriesContainer.style.display = 'block';
                        document.querySelectorAll('.nav-sidebar-link').forEach(function(a) { a.classList.remove('current'); });
                    }

                    if (sidebarFav) {
                        sidebarFav.addEventListener('click', function(e) {
                            e.preventDefault();
                            showFavorites();
                        });
                    }

                    document.querySelectorAll('.nav-sidebar-link[data-show-all]').forEach(function(link) {
                        link.addEventListener('click', function() {
                            showAll();
                        });
                    });

                    renderFavs();
                    updateFavBtns();
                });
            })();
            </script>
        </div>
    <?php else: ?>
        <div class="nav-empty">
            <p><?php _e('暂无导航内容，请在后台创建分类并添加文章。'); ?></p>
            <p class="nav-empty-tip"><?php _e('提示：在文章编辑页的“自定义字段”中填写“导航链接”，即可将该文章设为外部网址导航。'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php $this->need('footer.php'); ?>
