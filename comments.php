<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;

if (!function_exists('threadedComments')) :
/**
 * 自定义评论列表回调（Typecho 会自动检测此函数）
 */
function threadedComments($comments, $options)
{
    $avatarApi = getAvatarApi();

    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }
?>
<li id="<?php $comments->theId(); ?>" class="comment-body<?php echo $commentClass; ?>">
    <div class="comment-author">
        <img class="avatar" src="<?php echo buildAvatarUrl($avatarApi, $comments->mail, $comments->author); ?>" alt="<?php echo htmlspecialchars($comments->author); ?>" width="42" height="42">
        <cite class="fn"><?php $comments->author(); ?></cite>
    </div>
    <div class="comment-meta">
        <a href="<?php $comments->permalink(); ?>"><?php $comments->date($options->dateFormat); ?></a>
        <span class="comment-reply"><?php $comments->reply($options->replyWord); ?></span>
    </div>
    <div class="comment-content">
        <?php $comments->content(); ?>
    </div>
    <?php if ($comments->children) { ?>
        <div class="comment-children">
            <?php $comments->threadedComments(); ?>
        </div>
    <?php } ?>
</li>
<?php
}
endif;
?>

<div id="comments" class="nav-comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
        <h3><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></h3>
        <?php $comments->listComments(array(
            'dateFormat' => 'Y年m月d日 H:i',
            'replyWord'  => _t('回复'),
            'avatarSize' => 42
        )); ?>
        <?php $comments->pageNav('&laquo;', '&raquo;'); ?>
    <?php endif; ?>

    <?php if ($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="respond">
            <div class="cancel-comment-reply">
                <?php $comments->cancelReply(); ?>
            </div>
            <h3 id="response"><?php _e('添加新评论'); ?></h3>
            <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
                <?php if ($this->user->hasLogin()): ?>
                    <p><?php _e('登录身份'); ?>: <a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>. <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a></p>
                <?php else: ?>
                    <p>
                        <label for="author" class="sr-only"><?php _e('称呼'); ?></label>
                        <input type="text" name="author" id="author" class="text" placeholder="<?php _e('称呼'); ?>" value="<?php $this->remember('author'); ?>" required>
                    </p>
                    <p>
                        <label for="mail" class="sr-only"><?php _e('邮箱'); ?></label>
                        <input type="email" name="mail" id="mail" class="text" placeholder="<?php _e('邮箱'); ?>" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
                    </p>
                    <p>
                        <label for="url" class="sr-only"><?php _e('网站'); ?></label>
                        <input type="url" name="url" id="url" class="text" placeholder="<?php _e('网站'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?>>
                    </p>
                <?php endif; ?>
                <p>
                    <label for="textarea" class="sr-only"><?php _e('内容'); ?></label>
                    <textarea rows="4" cols="50" name="text" id="textarea" class="textarea" placeholder="<?php _e('在这里输入评论内容...'); ?>" required><?php $this->remember('text'); ?></textarea>
                </p>
                <p>
                    <button type="submit" class="btn btn-primary"><?php _e('提交评论'); ?></button>
                </p>
            </form>
        </div>
    <?php else: ?>
        <h3><?php _e('评论已关闭'); ?></h3>
    <?php endif; ?>
</div>
