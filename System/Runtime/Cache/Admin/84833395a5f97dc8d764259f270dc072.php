<?php if (!defined('THINK_PATH')) exit();?><div class="theme-poptit"><span>添加分享链接</span> <a href="javascript:;" title="关闭" class="close">×</a> </div>
<div class="theme_popover_table">
 <form  target="zhuchuang_frm" action="<?php echo U('fx/create');?>" method="post">
    <table class="table_data" cellpadding="0" cellspacing="0">
  
  <tr>
    <th class="alginRt w-100"> <font class="pointcl">*</font> 名称 </th>
    <td>
    <input name="data[name]" type="text" class="text w-200"  />
    </td>
  </tr>
  
  <tr>
    <th class="alginRt w-100"> <font class="pointcl">*</font> 电话 </th>
    <td>
    <input name="data[mobile]" value="<?php echo ($detail["mobile"]); ?>" type="text" class="text w-200"  />
    </td>
  </tr>
  

 
   <tr>
    <th class="alginRt w-100"> </th>
    <td>
    <input type="submit" value="添加分享链接" class="pub_btn" />
    </td>
  </tr>
  </table>
</div>