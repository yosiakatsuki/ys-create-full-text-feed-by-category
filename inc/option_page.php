<?php
	if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
?>

<div class="wrap">
<h2>Ys Create Full text feed by category</h2>
	<div id="poststuff">
		<form method="post" action="options.php">

		<?php
			settings_fields( 'ys_cftfbc_settings' );
			do_settings_sections( 'ys_cftfbc_settings' );
		?>

		<div class="postbox">
			<h2 class="hndle">Select category to create full text feed</h2>
			<div class="inside">
				<table class="form-table">
					<?php
						$option = get_site_option( 'ys_cftfbc_create_full_text_cat' );
						$categories = get_categories();
						foreach($categories as $category):
					?>
					<tr valign="top">
						<td scope="row">
							<input type="checkbox" name="ys_cftfbc_create_full_text_cat[]" value="<?php echo $category->cat_ID; ?>" <?php checked(in_array($category->cat_ID,$option)); ?> />
							<?php echo $category->cat_name; ?>
						</td>
					</tr>
					<?php
						endforeach;
					?>
				</table>
			</div>
		</div>
		<?php submit_button(); ?>
		</form>
	</div>
</div><!-- /.warp -->