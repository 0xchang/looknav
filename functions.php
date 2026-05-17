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
        '/usr/themes/looknav/back.png',
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

    $avatarApi = new \Typecho\Widget\Helper\Form\Element\Text(
        'avatarApi',
        null,
        null,
        _t('自定义头像地址'),
        _t('评论列表中显示的头像地址。支持 {mail}（邮箱）、{hash}（MD5）占位符，如 https://api.example.com/avatar?email={mail}；不需要参数直接填固定图片 URL 也可，留空则使用 Gravatar')
    );
    $form->addInput($avatarApi);
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
 * 获取主题配置中的自定义头像 API 地址
 *
 * @return string
 */
function getAvatarApi(): string
{
    $options = \Typecho\Widget::widget('Widget_Options');
    return $options->avatarApi ?? $options->gravatarUrl ?? '';
}

/**
 * 构建自定义头像 URL
 *
 * @param string $api  自定义 API 地址
 * @param string $mail 评论者邮箱
 * @param string $name 评论者名字
 * @return string
 */
function buildAvatarUrl(string $api, string $mail, string $name = ''): string
{
    if (empty($api)) {
        return \Typecho\Common::gravatarUrl($mail, 42, null, null, null);
    }

    // 如果 API 包含占位符，按占位符处理
    if (strpos($api, '{mail}') !== false || strpos($api, '{hash}') !== false || strpos($api, '{name}') !== false) {
        $map = [
            '{mail}'  => urlencode($mail),
            '{hash}'  => md5(strtolower(trim($mail))),
            '{name}'  => urlencode($name),
        ];
        return str_replace(array_keys($map), array_values($map), $api);
    }

    // 没有占位符时，在 API 地址后追加 ?name=xxx（已有参数则用 &）
    $sep = (strpos($api, '?') !== false) ? '&' : '?';
    return $api . $sep . 'name=' . urlencode($name);
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
 * 获取所有一级分类及其文章（含子分类文章归并）
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

    // 分离一级分类和子分类
    $topCategories = [];
    $subCategories = [];
    foreach ($categories as $cat) {
        if ($cat['parent'] == 0) {
            $topCategories[] = $cat;
        } else {
            $subCategories[] = $cat;
        }
    }

    $result = [];
    foreach ($topCategories as $topCat) {
        // 收集相关分类 ID（自己 + 所有子分类）
        $relatedMids = [$topCat['mid']];
        $subCatNames = []; // mid => name
        foreach ($subCategories as $subCat) {
            if ($subCat['parent'] == $topCat['mid']) {
                $relatedMids[] = $subCat['mid'];
                $subCatNames[$subCat['mid']] = $subCat['name'];
            }
        }

        // 获取所有相关文章的 cid（去重）
        $cidRows = $db->fetchAll($db->select('DISTINCT table.contents.cid')
            ->from('table.contents')
            ->join('table.relationships', 'table.contents.cid = table.relationships.cid')
            ->where('table.relationships.mid IN ?', $relatedMids)
            ->where('table.contents.type = ?', 'post')
            ->where('table.contents.status = ?', 'publish'));

        if (empty($cidRows)) {
            continue;
        }

        $cidList = array_column($cidRows, 'cid');

        // 查询文章完整信息
        $posts = $db->fetchAll($db->select()->from('table.contents')
            ->where('cid IN ?', $cidList)
            ->order('created', \Typecho\Db::SORT_DESC));

        // 查询每篇文章关联的子分类名（优先取第一个匹配的子分类）
        $cidToSubName = [];
        $relRows = $db->fetchAll($db->select('cid', 'mid')->from('table.relationships')
            ->where('cid IN ?', $cidList)
            ->where('mid IN ?', array_keys($subCatNames)));
        foreach ($relRows as $rel) {
            if (!isset($cidToSubName[$rel['cid']])) {
                $cidToSubName[$rel['cid']] = $subCatNames[$rel['mid']];
            }
        }

        // 组装文章数据
        $items = [];
        foreach ($posts as $post) {
            $post['permalink'] = \Typecho\Router::url('post', $post, $options->index);
            $post['subCategoryName'] = $cidToSubName[$post['cid']] ?? '';

            $fields = $db->fetchAll($db->select()->from('table.fields')
                ->where('cid = ?', $post['cid']));
            $fieldData = [];
            foreach ($fields as $f) {
                $fieldData[$f['name']] = $f[$f['type'] . '_value'];
            }
            $post['fields'] = (object)$fieldData;
            $items[] = $post;
        }

        // 组装子分类列表
        $subCats = [];
        foreach ($subCategories as $subCat) {
            if ($subCat['parent'] == $topCat['mid']) {
                $subCats[] = [
                    'mid'  => $subCat['mid'],
                    'name' => $subCat['name'],
                    'slug' => $subCat['slug']
                ];
            }
        }

        $result[] = [
            'mid'           => $topCat['mid'],
            'name'          => $topCat['name'],
            'slug'          => $topCat['slug'],
            'count'         => count($items),
            'items'         => $items,
            'subCategories' => $subCats
        ];
    }

    return $result;
}
