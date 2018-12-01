<?php if (!defined('THINK_PATH')) exit();?><div class="theme-poptit"><span>添加菜单</span> <a href="javascript:;" title="关闭" class="close">×</a> </div>
<div class="theme_popover_table">
<form  target="zhuchuang_frm" action="<?php echo U('menu/action',array('parent_id'=>$parent_id));?>" method="post">
  <table class="table_data daemon_data" cellpadding="0" cellspacing="0">
    <tr>
      <th> 菜单名称 </th>
      <th> 控制器 </th>
      <th> 排序 </th>
      <th> 是否显示 </th>
      <th class="text w-100"> 操作 </th>
    </tr>
    <tbody id="jq_action_list">
      <?php if(is_array($datas)): foreach($datas as $key=>$var): if(($var["parent_id"]) == $parent_id): ?><tr>
            <td><input type="text" name="data[<?php echo ($var["menu_id"]); ?>][menu_name]" value="<?php echo ($var["menu_name"]); ?>"  class="text w-100"/></td>
            <td><input type="text" name="data[<?php echo ($var["menu_id"]); ?>][menu_action]" value="<?php echo ($var["menu_action"]); ?>" class="text w-100" /></td>
            <td><input type="text" name="data[<?php echo ($var["menu_id"]); ?>][orderby]"    value="<?php echo ($var["orderby"]); ?>" class="text w-80" /></td>
            <td><select name="data[<?php echo ($var["menu_id"]); ?>][is_show]" class="text w-80">
                <option value="0" 
                <?php if(($var["is_show"]) == "0"): ?>selected="selected"<?php endif; ?>
                >隐藏
                </option>
                <option value="1" 
                <?php if(($var["is_show"]) == "1"): ?>selected="selected"<?php endif; ?>
                >显示
                </option>
              </select></td>
            <td><?php echo ROLE('menu/delete',array("menu_id"=>$var['menu_id']),'删除','act','pub_btn grayBtn');?> </td>
          </tr><?php endif; endforeach; endif; ?>
    </tbody>
    <tr>
      <td colspan="5"><input type="submit" value="确认添加" class="pub_btn" />
        <a href="javascript:void(0);" id="jq_action_add" class="pub_btn" >新增一行</a></td>
    </tr>
  </table>
  </div>
</form>
<script>
    $(document).ready(function (e) {
        var action_num = 0;
        $("#jq_action_add").click(function () {
            action_num++;
            var html = '<tr id="menu_action_' + action_num + '"> <td><input type="text" name="new[' + action_num + '][menu_name]" value=""  class="text w-100"/></td>   <td><input type="text" name="new[' + action_num + '][menu_action]" value="" class="text w-100" /></td> <td><input type="text" name="new[' + action_num + '][orderby]"    value="100" class="text w-80" /></td><td > <select name="new[' + action_num + '][is_show]" class="text w-80"> <option value="0">隐藏</option>  <option value="1">显示</option></select></td><td><a href="javascript:void(0);" onclick="$(\'#menu_action_' + action_num + '\').remove();" class="pub_btn grayBtn" >移除</a></td> </tr>';
            $("#jq_action_list").append(html);
        });
    });
</script>