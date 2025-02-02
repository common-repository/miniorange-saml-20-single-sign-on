<?php
/**
 * File Name: mo-saml-feedback-form.php
 * Description: This file will send us the feedback of the clients when they deactivate the plugin.
 *
 * @package miniorange-saml-20-single-sign-on\views
 */

/**
 * Displays the feedback form upon plugin deactivation.
 *
 * @return void
 */
function mo_saml_display_saml_feedback_form() {
	if ( isset( $_SERVER['PHP_SELF'] ) && 'plugins.php' !== basename( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) ) {
		return;
	}

	wp_enqueue_script( 'utils' );
	wp_enqueue_style( 'mo_saml_admin_plugins_page_style', esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'includes/css/style_settings.min.css' ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, false );
	?>
	<div id="mo_saml_feedback_modal" class="mo_modal" style="width:90%; margin-left:12%; margin-top:5%; text-align:center;">

		<div class="mo_modal-content" style="width:50%">
			<h3 style="margin: 2%; text-align:center;"><b><?php esc_html_e( 'Your feedback', 'miniorange-saml-20-single-sign-on' ); ?></b><span class="mo_saml_close" style="cursor: pointer">&times;</span>
			</h3>
			<hr style="width:75%;">			
			<form name="f" method="post" action="" id="mo_feedback">
				<?php wp_nonce_field( 'mo_feedback' ); ?>
				<input type="hidden" name="option" value="mo_feedback"/>
				<div>
					<p style="margin:2%">
					<h4 style="margin: 2%;  text-align:center;"><?php esc_html_e( 'Please help us to improve our plugin by giving your opinion.', 'miniorange-saml-20-single-sign-on' ); ?><br></h4>

					<div id="smi_rate" style="text-align:center">
						<input type="radio" name="rate" class="mo-saml-fb-radio" id="angry" value="1" />
						<label for="angry"><img class="sm" src="<?php echo esc_url( ( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ) . 'images/angry.webp' ); ?>" />
						</label>

						<input type="radio" name="rate" class="mo-saml-fb-radio" id="sad" value="2" />
						<label for="sad"><img class="sm" src="<?php echo esc_url( ( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ) . 'images/sad.webp' ); ?>" />
						</label>


						<input type="radio" name="rate" class="mo-saml-fb-radio" id="neutral" value="3" />
						<label for="neutral"><img class="sm" src="<?php echo esc_url( ( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ) . 'images/normal.webp' ); ?>" />
						</label>

						<input type="radio" name="rate" class="mo-saml-fb-radio" id="smile" value="4" />
						<label for="smile">
							<img class="sm" src="<?php echo esc_url( ( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ) . 'images/smile.webp' ); ?>" />
						</label>

						<input type="radio" name="rate" class="mo-saml-fb-radio" id="happy" value="5" checked />
						<label for="happy"><img class="sm" src="<?php echo esc_url( ( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ) . 'images/happy.webp' ); ?>" />
						</label>

						<div id="outer" style="visibility:visible"><span id="result"><?php esc_html_e( 'Thank you for appreciating our work', 'miniorange-saml-20-single-sign-on' ); ?></span></div>
					</div><br>
					<hr style="width:75%;">
					<?php
					$email = get_option( Mo_Saml_Customer_Constants::ADMIN_EMAIL );
					if ( empty( $email ) ) {
						$user  = wp_get_current_user();
						$email = $user->user_email;
					}
					?>
					<div class="radio-email" style="text-align:center;">

						<div class="mo_saml_feedback_email" style="display:inline-block; width:60%;">
							<input type="email" id="query_mail" name="query_mail" placeholder="<?php esc_attr_e( 'Please enter your email address', 'miniorange-saml-20-single-sign-on' ); ?>" required value="<?php echo esc_attr( $email ); ?>" readonly="readonly" />

							<input type="radio" name="edit" id="edit" onclick="editName()" value="" />
							<label for="edit"><img class="editable" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/edit-icon.webp' ); ?>" />
							</label>

						</div>
						<br><br>
						<textarea id="query_feedback" name="query_feedback" rows="4" style="width: 60%" placeholder="<?php esc_attr_e( 'Tell us what happened!', 'miniorange-saml-20-single-sign-on' ); ?>"></textarea>
						<br><br>
						<input type="checkbox" name="get_reply" value="reply" checked />
						<?php esc_html_e( 'miniOrange representative will reach out to you at the email-address entered above.', 'miniorange-saml-20-single-sign-on' ); ?>
					</div>
					<br>

					<div class="mo-modal-footer" style="text-align: center;margin-bottom: 2%">
						<input type="submit" name="miniorange_feedback_submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Send', 'miniorange-saml-20-single-sign-on' ); ?>" />
						<span width="30%">&nbsp;&nbsp;</span>
						<input type="button" name="miniorange_skip_feedback" class="button button-primary button-large" value="<?php esc_attr_e( 'Skip', 'miniorange-saml-20-single-sign-on' ); ?>" onclick="document.getElementById('mo_saml_feedback_form_close').submit();" />
					</div>
				</div>				
			</form>
			<form name="f" method="post" action="" id="mo_saml_feedback_form_close">
				<?php wp_nonce_field( 'mo_skip_feedback' ); ?>
				<input type="hidden" name="option" value="mo_skip_feedback" />
			</form>

		</div>

	</div>

	<script>
		jQuery('a[id="deactivate-miniorange-saml-20-single-sign-on"]').click(function() {

			var mo_modal = document.getElementById('mo_saml_feedback_modal');

			var span = document.getElementsByClassName("mo_saml_close")[0];

			mo_modal.style.display = "block";
			document.querySelector("#query_feedback").focus();
			span.onclick = function () {
				mo_modal.style.display = "none";
				jQuery('#mo_saml_feedback_form_close').submit();
			};

			window.onclick = function (event) {
				if (event.target === mo_modal) {
					mo_modal.style.display = "none";
				}
			};
			return false;

		});

		const INPUTS = document.querySelectorAll('#smi_rate input');
		INPUTS.forEach(el => el.addEventListener('click', (e) => updateValue(e)));


		function editName(){

			document.querySelector('#query_mail').removeAttribute('readonly');
			document.querySelector('#query_mail').focus();
			return false;

		}
		function updateValue(e) {
			document.querySelector('#outer').style.visibility="visible";
			var result = '<?php esc_html_e( 'Thank you for appreciating our work', 'miniorange-saml-20-single-sign-on' ); ?>';
			switch(e.target.value){
				case '1':	result = '<?php esc_html_e( 'Not happy with our plugin? Let us know what went wrong', 'miniorange-saml-20-single-sign-on' ); ?>';
					break;
				case '2':	result = '<?php esc_html_e( 'Found any issues? Let us know and we\'ll fix it ASAP', 'miniorange-saml-20-single-sign-on' ); ?>';
					break;
				case '3':	result = '<?php esc_html_e( 'Let us know if you need any help', 'miniorange-saml-20-single-sign-on' ); ?>';
					break;
				case '4':	result = '<?php esc_html_e( 'We\'re glad that you are happy with our plugin', 'miniorange-saml-20-single-sign-on' ); ?>';
					break;
				case '5':	result = '<?php esc_html_e( 'Thank you for appreciating our work', 'miniorange-saml-20-single-sign-on' ); ?>';
					break;
			}
			document.querySelector('#result').innerHTML = result;

		}
	</script><?php
}

?>
