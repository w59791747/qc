<?php if (!defined('THINK_PATH')) exit(); if(is_array($comment)): foreach($comment as $key=>$val): ?><div class="comment_list">
            <div class="comment_tx">
              <?php if($memberlist[$val[uid]][from] == member): ?><a href="<?php echo U('user/detail',array('uid'=>$val[uid]));?>"> <img src="__ATTACHS__/<?php echo (($memberlist[$val[uid]][face])?($memberlist[$val[uid]][face]):$CONFIG['attachs'][member_face]); ?>"> </a>
                <?php else: ?>
                <img src="__ATTACHS__/<?php echo (($memberlist[$val[uid]][face])?($memberlist[$val[uid]][face]):'default.jpg'); ?>"><?php endif; ?>
            </div>
            <div class="comment_cont">
              <?php if($memberlist[$val[uid]][from] == member): ?><a class="name" href="<?php echo U('user/detail',array('uid'=>$val[uid]));?>"> <?php echo ($memberlist[$val[uid]][name]); ?> </a>
                <?php else: ?>
                <span class="name"><?php echo ($memberlist[$val[uid]][name]); ?></span><?php endif; ?>
              <em class="plxing grayXing"><em w="<?php echo (ceil($val[score]/5*100)); ?>" class="xing"></em></em>
              <p class="pjcont"><?php echo ($val['content']); ?></p>
              <div class="bot black9"> <span ><?php echo (date("Y-m-d H:i:s",$val["dateline"])); ?></span></div>
            </div>
          </div><?php endforeach; endif; ?>