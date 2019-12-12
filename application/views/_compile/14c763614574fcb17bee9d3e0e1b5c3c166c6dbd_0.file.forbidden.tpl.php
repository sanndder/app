<?php
/* Smarty version 3.1.33, created on 2019-12-12 10:26:27
  from 'C:\xampp\htdocs\app\application\views\forbidden.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5df207c3d80199_54559579',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '14c763614574fcb17bee9d3e0e1b5c3c166c6dbd' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\forbidden.tpl',
      1 => 1576142786,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:_page/css.tpl' => 1,
    'file:_page/js.tpl' => 1,
    'file:_page/header.tpl' => 1,
    'file:_menu/werkgever.tpl' => 1,
    'file:_menu/uitzender.tpl' => 1,
    'file:_menu/inlener.tpl' => 1,
    'file:_menu/werknemer.tpl' => 1,
  ),
),false)) {
function content_5df207c3d80199_54559579 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
 - <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4524450145df207c3d51396_82053397', 'title');
?>
</title>

	<base href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/"/>

	<!-- Global stylesheets -->
	<?php $_smarty_tpl->_subTemplateRender('file:_page/css.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	<!-- /global stylesheets -->

	<!-- JS files -->
	<?php $_smarty_tpl->_subTemplateRender('file:_page/js.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	<!-- /JS files -->

	<!-- js plugins -->
	<?php if (isset($_smarty_tpl->tpl_vars['ckeditor']->value)) {?>
		<?php echo '<script'; ?>
 src="recources/plugins/ckeditor/ckeditor.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="recources/js/ckeditor.js"><?php echo '</script'; ?>
>
	<?php }?>

	<!-- file upload -->
	<?php if (isset($_smarty_tpl->tpl_vars['uploader']->value)) {?>
		<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
		<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
		<link href="template/global_assets/js/plugins/fileinput/themes/explorer-fa/theme.css" rel="stylesheet">
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/fileinput/js/plugins/piexif.min.js" type="text/javascript"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/fileinput/js/fileinput.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/fileinput/themes/fa/theme.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/fileinput/js/locales/nl.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="recources/js/uploader.js"><?php echo '</script'; ?>
>
	<?php }?>

	<!-- /JS plugins -->

</head>

<body class="">

<!-- Multiple fixed navbars wrapper -->
<div class="fixed-top">

	<!-- Main navbar -->
	<?php $_smarty_tpl->_subTemplateRender('file:_page/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	<!-- /main navbar -->

	<!-- Secondary navbar -->
    <?php if (!isset($_smarty_tpl->tpl_vars['hide_menu']->value)) {?>
        <?php if ($_SESSION['logindata']['user_type'] == 'werkgever') {
$_smarty_tpl->_subTemplateRender('file:_menu/werkgever.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>
        <?php if ($_SESSION['logindata']['user_type'] == 'uitzender') {
$_smarty_tpl->_subTemplateRender('file:_menu/uitzender.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>
        <?php if ($_SESSION['logindata']['user_type'] == 'inlener') {
$_smarty_tpl->_subTemplateRender('file:_menu/inlener.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>
        <?php if ($_SESSION['logindata']['user_type'] == 'werknemer') {
$_smarty_tpl->_subTemplateRender('file:_menu/werknemer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}?>
    <?php }?>
	<!-- /secondary navbar -->

</div>
<!-- /multiple fixed navbars wrapper -->


<!-- Page content -->
<div class="page-content">
	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">
			<div class="row">
				<div class="col-md-12">

					<div class="alert alert-danger alert-styled-left">
						U heeft onvoldoende rechten om deze pagina te bezoeken.
					</div>


				</div><!-- /col -->
			</div><!-- /row -->
		</div>
	</div>

</div>
<!-- /page content -->


</body>
</html>
<?php }
/* {block 'title'} */
class Block_4524450145df207c3d51396_82053397 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_4524450145df207c3d51396_82053397',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'title'} */
}