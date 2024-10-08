<?php
/**
 * Block: RSVP
 * Messages Success for Going
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/rsvp/messages/success/going.php
 *
 * See more documentation about our Blocks Editor templating system.
 *
 * @link https://evnt.is/1amp Help article for RSVP & Ticket template files.
 *
 * @var Tribe__Tickets__Ticket_Object $rsvp     The rsvp ticket object.
 * @var null|bool                     $is_going Whether the user confirmed for going or not-going.
 *
 * @since 5.0.0
 *
 * @version 5.0.0
 */

if ( empty( $is_going ) ) {
	return;
}
?>

<span class="tribe-tickets__rsvp-message-text">
	<strong>
		<?php echo esc_html( 'Your request has been received!' ); ?>
	</strong>

	<?php echo esc_html('Check your email for request confirmation.' ); ?>
</span>
