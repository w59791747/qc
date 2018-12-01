<?php if (!defined('THINK_PATH')) exit();?><div class="zcjiluTit">
    <table>
        <tr><th class="xuhao">序号</th><th>参与人</th><th>众筹金额</th><th>众筹时间</th><th>回款金额</th><th>众筹状态</th></tr>
        <?php if(is_array($lists_select)): foreach($lists_select as $key=>$val): ?><tr><td class="xuhao"><?php echo ($val[list_id]); ?></td><td><?php echo zhu_name($memberlistcount[$val[uid]][mobile]);?></td><td>￥<?php echo zhu_int($val[price]/100);?></td><td><?php echo (date("Y-m-d",$val["dateline"])); ?></td><td>￥<?php echo zhu_int($val[shouyi]/100);?></td><td><?php echo ($status_list[$project[status]]); ?></td></tr><?php endforeach; endif; ?>  
    </table>
  </div>