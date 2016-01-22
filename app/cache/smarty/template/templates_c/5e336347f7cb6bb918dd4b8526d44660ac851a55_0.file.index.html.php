<?php
/* Smarty version 3.1.29, created on 2016-01-12 08:25:35
  from "/data/app/www/php7/app/app/tpl/default/index/index.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5694b87fa5b365_83563971',
  'file_dependency' => 
  array (
    '5e336347f7cb6bb918dd4b8526d44660ac851a55' => 
    array (
      0 => '/data/app/www/php7/app/app/tpl/default/index/index.html',
      1 => 1452587131,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5694b87fa5b365_83563971 ($_smarty_tpl) {
echo $_smarty_tpl->tpl_vars['header']->value;?>



<!-- Content START -->
<div id="Content">

<!-- Side ContentWrapper START -->
<div id="ContentWrapper">

<!-- Side SC START -->
<div class="SC">

<div class="Post" style="padding-bottom: 50px;">

<div class="PostHead">
 <?php echo '<?php ';?>foreach($head_list as $list){
 
 
 }<?php echo '?>';?>
</div>
  
<div class="PostContent">
<p><?php echo $_smarty_tpl->tpl_vars['test']->value;?>
</p>
<label name="test">

//注释请用literal标签
以下代码仅使用kyphp自带标签解释，smarty无效
{$this->url->link('info/view' ,"&id={$value.id;}");}
smary应替换为
<kyphp>$this->url->link('info/view' ,"&id={$value.id;}")</kyphp>

<?php
$_from = $_smarty_tpl->tpl_vars['form_list']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_0_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_0_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['value']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
$__foreach_value_0_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>
  
<p><a href="<kyphp>$this->url->link('info/view' ,"&id=<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
")</kyphp>"><?php echo $_smarty_tpl->tpl_vars['value']->value['title'];?>
</a></p>
<p><?php echo $_smarty_tpl->tpl_vars['value']->value['content'];?>
</p>
<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_local_item;
}
if ($__foreach_value_0_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_item;
}
if ($__foreach_value_0_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_0_saved_key;
}
?>
<pre></pre>
 </div>
 
<ul class="PostCom">
 <li><span>Comments Off</span></li>
</ul>
<div><?php echo $_smarty_tpl->tpl_vars['pagination']->value;?>
</div>
</div>
<!-- Post END -->

<?php echo $_smarty_tpl->tpl_vars['right']->value;?>

</div>
<!-- Content END -->
 
<?php echo $_smarty_tpl->tpl_vars['footer']->value;
}
}
