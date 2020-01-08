<?php
/* Smarty version 3.1.33, created on 2020-01-07 12:53:45
  from 'C:\xampp\htdocs\app\application\views\dashboard\uitzender.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e147149046509_30219308',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7a01e0ecac680d47aef418de37dccb0bb6b14661' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\dashboard\\uitzender.tpl',
      1 => 1578254329,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e147149046509_30219308 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9914973045e147149036b06_46580927', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17052515115e14714903a989_02997602', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17107351355e14714903e802_83112896', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2067693345e147149042688_65260665', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../layout.tpl');
}
/* {block "title"} */
class Block_9914973045e147149036b06_46580927 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_9914973045e147149036b06_46580927',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Dashboard<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_17052515115e14714903a989_02997602 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_17052515115e14714903a989_02997602',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-home2<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_17107351355e14714903e802_83112896 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_17107351355e14714903e802_83112896',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Dashboard<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_2067693345e147149042688_65260665 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_2067693345e147149042688_65260665',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>



	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">



			<div class="row">
				<!--------------------------------------------------------------------------- left ------------------------------------------------->
				<div class="col-md-3">

					<!----------------- Documenten --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Documenten Abering</span>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>

						<div class="card-body">

						</div>
					</div>

					<!----------------- Log  --------------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Laatste gebeurtenissen</span>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>
						<div class="card-body">

						</div>
					</div>


				</div>
			    <!--------------------------------------------------------------------------- /left ------------------------------------------------->


			    <!--------------------------------------------------------------------------- right ------------------------------------------------->
				<div class="col-md-9">

					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<fieldset class="mb-0 mt-0">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Omzet en marge</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-12">
									<i>Geen data beschikbaar</i>
								</div>
							</div>
						</div><!-- /card body -->
					</div><!-- /basic card -->


					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<fieldset class="mb-0 mt-0">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Gewerkte uren</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-12">
									<i>Geen data beschikbaar</i>
								</div>
							</div>
						</div><!-- /card body -->
					</div><!-- /basic card -->

				</div>
				<!-- /col -->
			</div><!-- /row -->
			<!--------------------------------------------------------------------------- /right ------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
