<?php
/* Smarty version 3.1.33, created on 2020-01-07 23:02:39
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\urentypes.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e14ffffcfaf10_00020250',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b57f051cb38b22436c02f34b78f8f46d10f8a0be' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\urentypes.tpl',
      1 => 1575471170,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5e14ffffcfaf10_00020250 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13438003235e14ffffccff99_46586669', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_450167845e14ffffcd3e10_85781128', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19245958845e14ffffcd7c90_73888408', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3078141675e14ffffcdbb11_48623614', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_13438003235e14ffffccff99_46586669 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_13438003235e14ffffccff99_46586669',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_450167845e14ffffcd3e10_85781128 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_450167845e14ffffcd3e10_85781128',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_19245958845e14ffffcd7c90_73888408 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_19245958845e14ffffcd7c90_73888408',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_3078141675e14ffffcdbb11_48623614 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_3078141675e14ffffcdbb11_48623614',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


    <?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'urentypes'), 0, false);
?>

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Toevoegen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Urentype toevoegen</h5>
				</div>

				<div class="card-body">
					<form method="post" action="">

                        <?php if (isset($_smarty_tpl->tpl_vars['errors']->value)) {?>
							<div class="row">
								<div class="col-md-12">
									<div class="alert alert-warning alert-styled-left alert-arrow-left alert-dismissible" role="alert">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['errors']->value, 'arr');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['arr']->value) {
?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr']->value, 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
?>
                                                <?php echo $_smarty_tpl->tpl_vars['e']->value;?>

                                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</div><!-- /col -->
								</div><!-- /col -->
							</div>
							<!-- /row -->
                        <?php }?>


						<table>
							<tr>
								<th>Type</th>
								<th>Percentage</th>
								<th>Naam</th>
								<th></th>
							</tr>
							<tr>
								<td style="width: 200px;" class="pr-2">
									<select name="urentype_categorie_id" class="form-control make-naam" required>
										<option value=""></option>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['urentypes_categorien']->value, 'c');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
?>
											<option value="<?php echo $_smarty_tpl->tpl_vars['c']->value['urentype_categorie_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['c']->value['label'];?>
</option>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</select>
								</td>
								<td style="width:100px;" class="pr-2">
									<input name="percentage" value="" type="text" class="form-control text-right make-naam" autocomplete="off" required/>
								</td>
																<td style="width:400px;" class="pr-2">
									<input name="naam" value="" type="text" class="form-control urentype-naam" autocomplete="off" required/>
								</td>
								<td>
									<button type="submit" name="set" class="btn btn-success">
										<i class="icon-add mr-1"></i>Toevoegen
									</button>
								</td>
							</tr>
						</table>

						<!-------------- javascript for naam ------------------>
                        
							<?php echo '<script'; ?>
>
                                $( '.make-naam' ).on( 'change keyup', function() {
                                    $( '[name="naam"]' ).val( $( '[name="urentype_categorie_id"] option:selected' ).text() + ' ' + $( '[name="percentage"]' ).val() + '%' );

                                } );
							<?php echo '</script'; ?>
>
                        

					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| msg
			-------------------------------------------------------------------------------------------------------------------------------------------------->
            <?php if (isset($_smarty_tpl->tpl_vars['msg']->value)) {?>
				<div class="row">
					<div class="col-md-12">
                        <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

					</div><!-- /col -->
				</div>
				<!-- /row -->
            <?php }?>

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Toevoegen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Urentypes overzicht</h5>
				</div>

				<div class="card-body">

                    <?php if (!empty($_smarty_tpl->tpl_vars['urentypes_array']->value)) {?>
	                    <form method="post" action="">
						<table class="table">
							<thead>
								<tr>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['urentypes_array']->value, 'header');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['header']->key => $_smarty_tpl->tpl_vars['header']->value) {
$__foreach_header_3_saved = $_smarty_tpl->tpl_vars['header'];
?>
										<th class="pl-0"><?php echo $_smarty_tpl->tpl_vars['header']->key;?>
</th>
										<th style="border: 0"></th>
                                    <?php
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_3_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</tr>
							</thead>
							<tbody>

								<tr>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['urentypes_array']->value, 'array');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['array']->value) {
?>
										<td class="p-0" style="vertical-align: text-top">

											<table class="table table-striped">
												<tr>
													<th style="width: 20px;"></th>
													<th class="pl-1">Naam</th>
													<th class="text-right" style="width: 110px">Percentage</th>
																									</tr>
                                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['array']->value, 'u');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
?>
													<tr>
														<td class="pl-2 pr-0">
															<?php if ($_smarty_tpl->tpl_vars['u']->value['urentype_id'] != 1) {?>
															<button type="button" class="sweet-confirm p-0 btn" data-id="<?php echo $_smarty_tpl->tpl_vars['u']->value['urentype_id'];?>
" data-popup="tooltip" data-placement="top" data-title="Urentype verwijderen">
																<i class="icon-trash text-danger"></i>
															</button>
                                                            <?php }?>
														</td>
														<td class="pl-1"><?php echo $_smarty_tpl->tpl_vars['u']->value['naam'];?>
</td>
														<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['u']->value['percentage'];?>
</td>
                                                        													</tr>
                                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											</table>

										</td>
										<td style="border: 0"></td>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</tr>

							</tbody>
						</table>
	                    </form>
                    <?php }?>

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
