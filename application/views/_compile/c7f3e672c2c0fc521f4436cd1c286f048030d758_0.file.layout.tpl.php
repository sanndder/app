<?php
/* Smarty version 3.1.33, created on 2020-01-02 19:14:20
  from 'C:\xampp\htdocs\app\application\views\layout.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e0e32fc1865a1_43076147',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c7f3e672c2c0fc521f4436cd1c286f048030d758' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\layout.tpl',
      1 => 1577988858,
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
function content_5e0e32fc1865a1_43076147 (Smarty_Internal_Template $_smarty_tpl) {
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
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7249668125e0e32fc1671a8_98910753', 'title');
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

	<!-- datatable -->
    <?php if (isset($_smarty_tpl->tpl_vars['datatable']->value)) {?>
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/tables/datatables/datatables.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php }?>

	<!-- select2 -->
    <?php if (isset($_smarty_tpl->tpl_vars['select2']->value) || isset($_smarty_tpl->tpl_vars['datatable']->value)) {?>
		<?php echo '<script'; ?>
 src="template/global_assets/js/plugins/forms/selects/select2.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php }?>

	<!-- /JS plugins -->

</head>

<body>

<!-- Div wrapper to push page to the left -->
<div class="page-wrapper">
	<!-- Multiple fixed navbars wrapper -->

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

	<!-- /multiple fixed navbars wrapper -->


	<!-- Page header -->
	<div class="page-header">
		<div class="page-header-content header-elements-md-inline">

			<div class="page-title d-flex">
				<h4>
					<i class="<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20976212475e0e32fc17aa25_79969166', 'header-icon');
?>
 mr-2"></i>
					<span class="font-weight-semibold"><?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_213344475e0e32fc17e8a8_76362463', 'header-title');
?>
</span>
				</h4>
			</div>

            		</div>

	</div>
	<!-- /page header -->


	<!-- Page content -->
	<div class="page-content pt-0">

		<!-- Content area -->
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19158208625e0e32fc182721_85306663', 'content');
?>

		<!-- /content area -->

	</div>
	<!-- /page content -->

</div>

<div class="sidebar-right">
	<div class="row ml-2">
		<div class="col-md-12 pl-2 pt-2">
			NL96SNSB0821159593
		</div><!-- /col -->
	</div><!-- /row -->
	<div class="row ml-2">
		<div class="col-md-12 pl-2 pt-2">
			NL854569182B01
		</div><!-- /col -->
	</div><!-- /row -->
	<div class="row ml-2">
		<div class="col-md-12 pl-2 pt-2">
			hsmeijering@home.nl
		</div><!-- /col -->
	</div><!-- /row -->
</div>


</body>
</html>
<?php }
/* {block 'title'} */
class Block_7249668125e0e32fc1671a8_98910753 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_7249668125e0e32fc1671a8_98910753',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'title'} */
/* {block 'header-icon'} */
class Block_20976212475e0e32fc17aa25_79969166 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_20976212475e0e32fc17aa25_79969166',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'header-icon'} */
/* {block 'header-title'} */
class Block_213344475e0e32fc17e8a8_76362463 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_213344475e0e32fc17e8a8_76362463',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'header-title'} */
/* {block 'content'} */
class Block_19158208625e0e32fc182721_85306663 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_19158208625e0e32fc182721_85306663',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'content'} */
}
