<?php
/* Smarty version 3.1.29, created on 2016-01-12 07:30:44
  from "/data/app/www/php7/app/app/tpl/default/public/header.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5694aba45cf2c6_92877378',
  'file_dependency' => 
  array (
    '82ae433c0c8ce2b3701848fbfa0c1f4ff1a847c8' => 
    array (
      0 => '/data/app/www/php7/app/app/tpl/default/public/header.html',
      1 => 1384061732,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5694aba45cf2c6_92877378 ($_smarty_tpl) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0022)http://hotue.blog.com/ -->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<title>Blog example</title>

<link href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/wp-layout-large.css" title="normal">

<meta name="robots" content="index,follow">

<meta property="og:type" content="blog">

<link rel="stylesheet" id="admin-bar-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/admin-bar.css" type="text/css" media="all">
<link rel="stylesheet" id="blogcom-admin-bar-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/blogcom-admin-bar.css" type="text/css" media="all">
<link rel="stylesheet" id="NextGEN-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/nggallery.css" type="text/css" media="screen">
<link rel="stylesheet" id="shutter-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/shutter-reloaded.css" type="text/css" media="screen">
<link rel="stylesheet" id="yui-cssreset-context-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/cssreset-context.css" type="text/css" media="all">
<link rel="stylesheet" id="blogcom_oauth_comments_style-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/base.css" type="text/css" media="all">
<link rel="stylesheet" id="blogcom_oauth_comments_twitter_style-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/twitter.css" type="text/css" media="all">
<link rel="stylesheet" id="blogcom_oauth_comments_google_style-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/google.css" type="text/css" media="all">
<link rel="stylesheet" id="blogcom_oauth_comments_browserid_style-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/browserid.css" type="text/css" media="all">
<link rel="stylesheet" id="blogcom_oauth_comments_facebook_style-css" href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/css/facebook.css" type="text/css" media="all">

<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
/public/js/jquery.js"><?php echo '</script'; ?>
>


<style type="text/css" media="print">#wpadminbar { display:none; }</style>
<style type="text/css">
	html { margin-top: 28px !important; }
	* html body { margin-top: 28px !important; }
</style>
			

			
	</head> 

<body class="home blog admin-bar">
<!-- bgcontain & bgcontainIn START -->
<div id="bgcontain"><div id="bgcontainIn">

<!-- Header START -->
<div class="HeaderBG">
 <div class="Header">
 <h1><a href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['sitetitle']->value;?>
</a></h1>
 <div class="TagLine"><lable name="title"></div>
 <div class="CornerLeft"></div><div class="CornerRight"></div>
 
   <div class="TopMenu">
    <ul>
      <li><a href="<?php echo $_smarty_tpl->tpl_vars['http']->value;?>
">Home</a></li>  </ul>
  </div>
 
</div> 
<!-- Header END -->

<!-- Menu START -->
<div class="Menu">
    <div class="MainMenu">
   <ul>
    <li class="page_item page-item-2"><a href="<?php echo '<?php ';?>echo $this->url->link('info/test');<?php echo '?>';?>" title="<?php echo $_smarty_tpl->tpl_vars['test']->value;?>
"><?php echo '<?php ';?>echo $test;<?php echo '?>';?></a></li>
	<li class="page_item page-item-2"><a href="<?php echo '<?php ';?>echo $this->url->link('info/test','&app=app2');<?php echo '?>';?>" title="<?php echo $_smarty_tpl->tpl_vars['test']->value;?>
">APP2 <?php echo '<?php ';?>echo $test;<?php echo '?>';?></a></li>
 
 
   </ul>
  </div>
    
  <div class="MainSyn">
  
  </div>
  
</div>
 
</div> 
<!-- Menu END -->
<?php }
}
