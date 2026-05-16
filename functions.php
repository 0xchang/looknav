<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 主题配置
 */
function themeConfig($form)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点 LOGO 地址'),
        _t('在这里填入一个图片 URL 地址')
    );
    $form->addInput($logoUrl->addRule('url', _t('请填写一个合法的URL地址')));

    $faviconApi = new \Typecho\Widget\Helper\Form\Element\Text(
        'faviconApi',
        null,
        null,
        _t('Favicon API 地址'),
        _t('用于自动获取网站图标，推荐 https://favicon.im/ 或 https://www.google.com/s2/favicons?domain= ，留空则直接读取目标站点 /favicon.ico')
    );
    $form->addInput($faviconApi);

    $icp = new \Typecho\Widget\Helper\Form\Element\Text(
        'icp',
        null,
        null,
        _t('ICP 备案号'),
        _t('页面底部显示的备案号')
    );
    $form->addInput($icp);

    $moeIcp = new \Typecho\Widget\Helper\Form\Element\Text(
        'moeIcp',
        null,
        null,
        _t('萌 ICP 备'),
        _t('页面底部显示的萌ICP备编号，例如：萌ICP备xxxxxxxx号')
    );
    $form->addInput($moeIcp);

    $bgUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'bgUrl',
        null,
        null,
        _t('背景图地址'),
        _t('填入图片地址可自定义页面背景，支持绝对路径（如 /usr/themes/looknav/bg.jpg）或完整 URL，留空则使用默认渐变背景')
    );
    $form->addInput($bgUrl);

    $siteBirth = new \Typecho\Widget\Helper\Form\Element\Text(
        'siteBirth',
        null,
        null,
        _t('站点创建日期'),
        _t('格式：YYYY-MM-DD，例如 2024-01-01，留空则不显示运行天数')
    );
    $form->addInput($siteBirth);
}

/**
 * 文章自定义字段：导航链接地址
 */
function themeFields($layout)
{
    $navurl = new \Typecho\Widget\Helper\Form\Element\Text(
        'navurl',
        null,
        null,
        _t('导航链接'),
        _t('在这里填入外部链接地址，留空则使用文章链接')
    );
    $layout->addItem($navurl);

    $navicon = new \Typecho\Widget\Helper\Form\Element\Text(
        'navicon',
        null,
        null,
        _t('自定义图标'),
        _t('在这里填入图标 URL 地址，留空则自动获取目标站点 favicon')
    );
    $layout->addItem($navicon->addRule('url', _t('请填写一个合法的URL地址')));
}

/**
 * 获取文章导航链接
 *
 * @param \Widget\Archive $archive
 * @return string
 */
function isSafeUrl(string $url): bool
{
    return (bool) preg_match('/^https?:\/\/.+/i', $url);
}

function getNavUrl(\Widget\Archive $archive): string
{
    $url = $archive->fields->navurl ?? '';
    return isSafeUrl($url) ? $url : $archive->permalink;
}

/**
 * 获取站点总访问量
 *
 * @return int
 */
function getVisitCount(): int
{
    $file = __DIR__ . '/visit_count.txt';
    if (!file_exists($file)) {
        file_put_contents($file, '0', LOCK_EX);
    }
    return (int) file_get_contents($file);
}

/**
 * 获取站点图标
 *
 * @param string $url
 * @return string
 */
function getFavicon(string $url): string
{
    $api = \Typecho\Widget::widget('Widget_Options')->faviconApi;
    $host = parse_url($url, PHP_URL_HOST);
    $scheme = parse_url($url, PHP_URL_SCHEME) ?: 'https';

    if (empty($host)) {
        $siteUrl = \Typecho\Widget::widget('Widget_Options')->siteUrl;
        $host = parse_url($siteUrl, PHP_URL_HOST);
        $scheme = parse_url($siteUrl, PHP_URL_SCHEME) ?: 'https';
    }

    // 未设置 API 时，直接使用站点根目录 favicon.ico
    if (empty($api)) {
        return $scheme . '://' . $host . '/favicon.ico';
    }

    return rtrim($api, '/?') . '/' . $host;
}

/**
 * 获取所有分类及其文章（用于导航展示）
 *
 * @return array
 */
function getNavCategories(): array
{
    $db = \Typecho\Db::get();
    $options = \Typecho\Widget::widget('Widget_Options');

    // 获取所有分类
    $categories = $db->fetchAll($db->select()->from('table.metas')
        ->where('type = ?', 'category')
        ->order('order', \Typecho\Db::SORT_ASC));

    $result = [];
    foreach ($categories as $category) {
        $posts = $db->fetchAll($db->select()->from('table.contents')
            ->join('table.relationships', 'table.contents.cid = table.relationships.cid')
            ->where('table.relationships.mid = ?', $category['mid'])
            ->where('table.contents.type = ?', 'post')
            ->where('table.contents.status = ?', 'publish')
            ->order('table.contents.created', \Typecho\Db::SORT_DESC));

        if (empty($posts)) {
            continue;
        }

        // 组装文章数据
        $items = [];
        foreach ($posts as $post) {
            $post['permalink'] = \Typecho\Router::url('post', $post, $options->index);
            $fields = $db->fetchAll($db->select()->from('table.fields')
                ->where('cid = ?', $post['cid']));
            $fieldData = [];
            foreach ($fields as $f) {
                $fieldData[$f['name']] = $f[$f['type'] . '_value'];
            }
            $post['fields'] = (object)$fieldData;
            $items[] = $post;
        }

        $result[] = [
            'mid'    => $category['mid'],
            'name'   => $category['name'],
            'slug'   => $category['slug'],
            'count'  => $category['count'],
            'items'  => $items
        ];
    }

    return $result;
}
