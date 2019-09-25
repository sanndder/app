<?php
/* Smarty version 3.1.33, created on 2019-09-11 10:59:45
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\av.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d78b781c69a50_73886840',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '55a863746920c9c758fe534b0760b3af9b4ce232' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\av.tpl',
      1 => 1568192385,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
    'file:instellingen/werkgever/_topbar.tpl' => 1,
  ),
),false)) {
function content_5d78b781c69a50_73886840 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3099353495d78b781c5a058_64578066', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8441398535d78b781c5ded7_14440381', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3524286935d78b781c61d50_34078334', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('ckeditor', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8422572195d78b781c65bd5_09677861', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_3099353495d78b781c5a058_64578066 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_3099353495d78b781c5a058_64578066',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_8441398535d78b781c5ded7_14440381 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_8441398535d78b781c5ded7_14440381',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_3524286935d78b781c61d50_34078334 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_3524286935d78b781c61d50_34078334',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_8422572195d78b781c65bd5_09677861 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_8422572195d78b781c65bd5_09677861',
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

            <?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_topbar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

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
