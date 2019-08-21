<?php
/* Smarty version 3.1.33, created on 2019-08-20 13:32:45
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\av.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5bda5d50d068_08418500',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '55a863746920c9c758fe534b0760b3af9b4ce232' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\av.tpl',
      1 => 1566300696,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d5bda5d50d068_08418500 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11932740145d5bda5d4fd650_79864071', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14864852755d5bda5d5014d0_89508205', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15397113105d5bda5d505350_85992916', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('ckeditor', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7301415275d5bda5d5091d5_26350398', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_11932740145d5bda5d4fd650_79864071 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_11932740145d5bda5d4fd650_79864071',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_14864852755d5bda5d5014d0_89508205 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_14864852755d5bda5d5014d0_89508205',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_15397113105d5bda5d505350_85992916 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_15397113105d5bda5d505350_85992916',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_7301415275d5bda5d5091d5_26350398 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_7301415275d5bda5d5091d5_26350398',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php echo '<script'; ?>
>
		


		
	<?php echo '</script'; ?>
>

	<?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'av'), 0, false);
?>

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">
				<div class="card-body">

					<div class="row">
						<div class="col-lg-6">

							<textarea name="editor" id="editor"></textarea>

						</div><!-- /col -->
						<div class="col-lg-2">

							<h6 class="mb-0 font-weight-semibold">
								<em class="icon-pencil6 mr-2"></em>Variabelen invoegen</h6>
							<div class="dropdown-divider mb-2"></div>
							<span class="text-muted ml-1">Werkgever</span>
							<ul data-var-categorie="werkgever" class="list list-unstyled mb-0 list-hover ckeditor-vars mt-1 ml-2">
								<li data-var="bedrijfsnaam">Bedrijfsnaam</li>
								<li data-var="straatnaam">Straatnaam</li>
								<li data-var="huisnummer">Huisnummer</li>
								<li data-var="postcode">Postcode</li>
								<li data-var="kvknr">KvK nr</li>
								<li data-var="btwnr">BTW nummer</li>
								<li data-var="handtekening">Handtekening</li>
							</ul>


						</div><!-- /col -->
					</div><!-- /row -->
				</div><!-- /card body -->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
