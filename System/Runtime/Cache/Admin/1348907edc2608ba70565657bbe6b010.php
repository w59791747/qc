<?php if (!defined('THINK_PATH')) exit();?><div class="theme-poptit" id="theme-pop"><span>添加分类</span> <a href="javascript:;" title="关闭" class="close">×</a> <div id="coor"></div>

 </div>
						<div class="theme_popover_table">
                         <form  target="zhuchuang_frm" action="<?php echo U('menu/create');?>" method="post">
							<table class="table_data" cellpadding="0" cellspacing="0">
                          <tr>
                            <th class="alginRt w-100"> <font class="pointcl">*</font> 上级菜单 </th>
                            <td>
                            	<select name="data[parent_id]" class="text w-200">
                                    <option value="0">一级菜单</option>
                                    <?php if(is_array($datas)): foreach($datas as $key=>$var): if(($var["parent_id"] == 0)): ?><option value="<?php echo ($var["menu_id"]); ?>"   <?php if(($var["menu_id"]) == $parent_id): ?>selected="selected"<?php endif; ?> ><?php echo ($var["menu_name"]); ?></option><?php endif; endforeach; endif; ?>       
                                </select>
                            </td>
                          </tr>
                          <tr>
                            <th class="alginRt w-100"> <font class="pointcl">*</font> 模块名称 </th>
                            <td>
                            <input name="data[menu_name]" type="text" class="text w-200"  />
                            </td>
                          </tr>
                           
                          <tr>
                            <th class="alginRt w-100"> </th>
                            <td>
                            <input type="submit" value="确认添加" class="pub_btn" />
                            </td>
                          </tr>
                          </table>
						</div>