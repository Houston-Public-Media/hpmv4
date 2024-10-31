<?php
/**
 * Block: RSVP
 * Attendees
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/rsvp/attendees.php
 *
 * See more documentation about our Blocks Editor templating system.
 *
 * @link https://evnt.is/1amp Help article for RSVP & Ticket template files.
 *
 * @var Tribe__Tickets__Ticket_Object $rsvp The rsvp ticket object.
 * @var string|null $step The step the views are on.
 * @var array $attendees List of attendees IDs confirmed for the RSVP.
 *
 * @since 5.7.0
 *
 * @version 5.7.0
 */

if ( empty( $attendees ) ) {
	return;
}