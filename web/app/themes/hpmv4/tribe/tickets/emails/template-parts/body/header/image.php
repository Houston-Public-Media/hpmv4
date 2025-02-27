<?php
/**
 * Event Tickets Emails: Main template > Body > Header > Image.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/emails/template-parts/body/header/image.php
 *
 * See more documentation about our views templating system.
 *
 * @link https://evnt.is/tickets-emails-tpl Help article for Tickets Emails template files.
 *
 * @version 5.5.9
 *
 * @since 5.5.9
 *
 * @var Tribe__Template                    $this             Current template object.
 * @var \TEC\Tickets\Emails\Email_Abstract $email            The email object.
 * @var bool                               $preview          Whether the email is in preview mode or not.
 * @var bool                               $is_tec_active    Whether `The Events Calendar` is active or not.
 * @var string                             $header_image_url URL of header image.
 * @var WP_Post|null                       $event            The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

if ( empty( $header_image_url ) ) {
	return;
}
?>
<img
	style="max-height:75px;max-width:100%;margin:5px 0 0 5px;display:inline-block"
	src="<?php echo esc_url( $header_image_url ); ?>"
/>
