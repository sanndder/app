<?php
/* Smarty version 3.1.33, created on 2019-08-20 13:33:42
  from 'C:\xampp\htdocs\app\application\views\layout.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5bda9653cea7_95344043',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c7f3e672c2c0fc521f4436cd1c286f048030d758' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\layout.tpl',
      1 => 1566300821,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:_page/css.tpl' => 1,
    'file:_page/js.tpl' => 1,
    'file:_page/header.tpl' => 1,
    'file:_menu/werkgever.tpl' => 1,
  ),
),false)) {
function content_5d5bda9653cea7_95344043 (Smarty_Internal_Template $_smarty_tpl) {
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13658306175d5bda96529624_11170119', 'title');
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

	<!-- ckeditor plugin -->
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
		<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
		<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
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

	<!-- datatable -->
	<?php if (isset($_smarty_tpl->tpl_vars['datatable']->value)) {?>
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/tables/datatables/datatables.min.js" type="text/javascript"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/forms/selects/select2.min.js" type="text/javascript"><?php echo '</script'; ?>
>
	<?php }?>

	<!-- /JS plugins -->

</head>

<body class="navbar-md-md-top">

<!-- Multiple fixed navbars wrapper -->
<div class="fixed-top">

	<!-- Main navbar -->
	<?php $_smarty_tpl->_subTemplateRender('file:_page/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	<!-- /main navbar -->

	<!-- Secondary navbar -->
	<?php $_smarty_tpl->_subTemplateRender('file:_menu/werkgever.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	<!-- /secondary navbar -->

</div>
<!-- /multiple fixed navbars wrapper -->


<!-- Page header -->
<div class="page-header">
	<div class="page-header-content header-elements-md-inline">

		<div class="page-title d-flex">
			<h4>
				<i class="<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1395954295d5bda96531324_13791491', 'header-icon');
?>
 mr-2"></i>
				<span class="font-weight-semibold"><?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17923364395d5bda965351a6_87480545', 'header-title');
?>
</span>
			</h4>
		</div>

			</div>

</div>
<!-- /page header -->


<!-- Page content -->
<div id="vue-app" class="page-content pt-0">


	<!-- Content area -->
	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5645207875d5bda96539029_92675893', 'content');
?>

	<!-- /content area -->

</div>
<!-- /page content -->

<!-- vue main js -->
<!--<?php echo '<script'; ?>
 src="vue/dist/build.js"><?php echo '</script'; ?>
>-->

</body>
</html>
<?php }
/* {block 'title'} */
class Block_13658306175d5bda96529624_11170119 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_13658306175d5bda96529624_11170119',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'title'} */
/* {block 'header-icon'} */
class Block_1395954295d5bda96531324_13791491 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_1395954295d5bda96531324_13791491',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'header-icon'} */
/* {block 'header-title'} */
class Block_17923364395d5bda965351a6_87480545 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_17923364395d5bda965351a6_87480545',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'header-title'} */
/* {block 'content'} */
class Block_5645207875d5bda96539029_92675893 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_5645207875d5bda96539029_92675893',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'content'} */
}