<?php if (!defined('THINK_PATH')) exit();?><style>

/*报错样式*/
.czts{ width:500px; margin:80px auto 0px auto; background:#f9f9f9;box-shadow:0 5px 5px rgba(0,0,0,.1); font-family: "Microsoft YaHei";}
.czts .czts_tit{ height:50px; line-height:50px;background:#D33C4D; color:#fff; font-size:18px; font-weight:bold; text-align:center;}
.czts .cztsnr{ font-size:16px; padding:40px;}
.czts_time{ color:#D33C4D;}
</style>
            <script language="javascript">
                var secs = 3; //倒计时的秒数 
                var URL;
                function Load(url) {
                    URL = url;
                    for (var i = secs; i >= 0; i--)
                    {
                        window.setTimeout('doUpdate(' + i + ')', (secs - i) * 1000);
                    }
                }
                function doUpdate(num)
                {
                    document.getElementById('czts_time').innerHTML = num;
                    if (num == 0) {
                        window.location = URL;
                    }
                }
            </script>
        </head>

        <div class="czts">
        <div class="czts_tit">友情提示</div>
            <?php if(isset($message)): ?><div class="cztsnr"><?php echo($message); ?><p class="czts_p">页面自动 跳转中   等待时间：<span class="czts_time" id="czts_time"></span></p></div>
                <?php else: ?>
                <div class="cztsnr cztsnr_Failure"><?php echo($error); ?><p class="czts_p">页面自动 跳转中   等待时间：<span class="czts_time" id="czts_time"></span></p></div><?php endif; ?>
        </div>
        <script language="javascript">
            Load("<?php echo($jumpUrl); ?>"); //要跳转到的页面 
        </script>