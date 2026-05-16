    </div><!-- /.container -->
</div><!-- /.site-body -->

<footer class="site-footer">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-brand">
                <a class="footer-logo" href="<?php $this->options->siteUrl(); ?>">
                    <?php if ($this->options->logoUrl): ?>
                        <img src="<?php $this->options->logoUrl() ?>" alt="<?php $this->options->title() ?>">
                    <?php else: ?>
                        <span><?php $this->options->title(); ?></span>
                    <?php endif; ?>
                </a>
                <?php if ($this->options->description): ?>
                    <p class="footer-desc"><?php $this->options->description(); ?></p>
                <?php endif; ?>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <span class="footer-copy">&copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a></span>
                    <?php if ($this->options->siteBirth): ?>
                        <span class="footer-running">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <?php
                            $birth = strtotime($this->options->siteBirth);
                            $days = $birth ? floor((time() - $birth) / 86400) : 0;
                            echo _t('已运行') . ' ' . $days . ' ' . _t('天');
                            ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($this->options->icp || $this->options->moeIcp): ?>
                        <span class="footer-icp">
                            <?php if ($this->options->icp): ?>
                                <a href="https://beian.miit.gov.cn/" target="_blank" rel="nofollow"><?php $this->options->icp(); ?></a>
                            <?php endif; ?>
                            <?php if ($this->options->icp && $this->options->moeIcp): ?>
                                <span class="icp-divider">·</span>
                            <?php endif; ?>
                            <?php if ($this->options->moeIcp): ?>
                                <a href="https://icp.gov.moe/?keyword=<?php echo urlencode($this->options->moeIcp); ?>" target="_blank" rel="nofollow"><?php $this->options->moeIcp(); ?></a>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="footer-bottom-right">
                    <span class="footer-visit">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span id="visitCount"><?php echo getVisitCount(); ?></span>
                    </span>
                    <span class="footer-theme">Theme by <a href="https://github.com/0xchang/looknav" target="_blank" rel="noopener">LookNav</a></span>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
(function(){
    function updateHeaderTime(){
        var now=new Date();
        var h=String(now.getHours()).padStart(2,'0');
        var i=String(now.getMinutes()).padStart(2,'0');
        var s=String(now.getSeconds()).padStart(2,'0');
        var el=document.getElementById('headerTime');
        if(el)el.textContent=h+':'+i+':'+s;
        if(typeof Lunar!=='undefined'){
            var lunar=Lunar.toLunar(now);
            var ttLunar=document.getElementById('ttLunar');
            var ttGanZhi=document.getElementById('ttGanZhi');
            var ttAnimal=document.getElementById('ttAnimal');
            var ttTerm=document.getElementById('ttTerm');
            var ttFestival=document.getElementById('ttFestival');
            if(ttLunar)ttLunar.textContent=lunar.monthStr+lunar.dayStr;
            if(ttGanZhi)ttGanZhi.textContent=lunar.ganZhi;
            if(ttAnimal)ttAnimal.textContent=lunar.animal+'年';
            var term='';var festivals=[];
            var terms=['小寒','大寒','立春','雨水','惊蛰','春分','清明','谷雨','立夏','小满','芒种','夏至','小暑','大暑','立秋','处暑','白露','秋分','寒露','霜降','立冬','小雪','大雪','冬至'];
            for(var j=0;j<lunar.festivals.length;j++){
                var f=lunar.festivals[j];
                if(terms.indexOf(f)>=0){term=f;}else{festivals.push(f);}
            }
            if(ttTerm)ttTerm.textContent=term||'无';
            if(ttFestival)ttFestival.textContent=festivals.join('、')||'无';
        }
    }
    updateHeaderTime();
    setInterval(updateHeaderTime,1000);

    // 站点访问计数
    var visitKey = 'looknav_visit_' + new Date().toDateString();
    if (!sessionStorage.getItem(visitKey)) {
        sessionStorage.setItem(visitKey, '1');
        fetch('<?php $this->options->themeUrl('counter.php'); ?>')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var el = document.getElementById('visitCount');
                if (el && data.count) el.textContent = data.count;
            })
            .catch(function() {});
    }
})();
</script>
<?php $this->footer(); ?>
</body>
</html>
