<?php wp_nonce_field( 'quote-customer', '_wpnonce_quote_customer' ); ?>
<p class="form-field form-field-wide form-required qb-customer-field">
	<label for="qb-customer-id"><?php echo __( 'Customer' ) ?></label>
	<select name="qb_customer_id" id="qb-customer-id" type="text" aria-required="true">
		<option><?php echo __( 'None'); ?></option>
		<?php
		$users            = get_users();
		$customer_user_id = Quote_Builder_CPT_Quote::get_instance()->get_customer_user_id( $post->ID );
		foreach( $users as $user ) :
			$display_name = empty( $user->display_name ) ? $user->first_name . ' ' . $user->last_name : $user->display_name;
			?>
				<option name="<?php echo esc_attr( $user->ID ); ?>" value="<?php echo $user->ID ?>"<?php echo $user->ID === $customer_user_id ? ' selected="selected"' : ''; ?>><?php echo esc_html( $display_name ) ?></option>
			<?php
		endforeach;
		?>
	</select>
</p>