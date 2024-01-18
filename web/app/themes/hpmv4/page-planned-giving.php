<?php
/*
Template Name: Planned Giving Page
*/
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?PHP
	while ( have_posts() ) {
		the_post();
		echo hpm_head_banners( get_the_ID(), 'page' ); ?>
			<div class="houston-matters-page planned-giving">
				<?php
					echo hpm_head_banners( get_the_ID(), 'entry' );
					the_content(); ?>
					<h2>New Designed block</h2>
					<div class="about-houston-block">
						<div class="houston-content d-flex">
							<div class="image-wrapper">
								<h4> <strong>Mary Ann Marucci</strong> </h4>
								<p>Senior Director, Advancement</p>
								<p>Phone: (713) 743-7714</p>
								<p>Email: mmarucci@houstonpublicmedia.org</p>
								<div class="contact-block">
									<h2 class="title no-bar">Contact Us</h2>
									<p>Our planned giving team would be happy tospeak with you in confidence, with no obligation.<p>
									<button class="btn btn-primary">Contact me</button>
								</div>
							</div>
							<div class="content-wrapper">
								<div class="content-box">
									<div class="wpforms-container wpforms-container-full" id="wpforms-450855"><form id="wpforms-form-450855" class="wpforms-validate wpforms-form wpforms-ajax-form" data-formid="450855" method="post" enctype="multipart/form-data" action="/planned-giving/" data-token="ed7935bbc193f7fda7f0e5aea0c7e838" novalidate="novalidate"><div class="wpforms-head-container"><div class="wpforms-description">Have a question or comment? Please use this form to let us know.</div></div><noscript class="wpforms-error-noscript">Please enable JavaScript in your browser to complete this form.</noscript><div class="wpforms-field-container"><div id="wpforms-450855-field_1-container" class="wpforms-field wpforms-field-text" data-field-id="1"><label class="wpforms-field-label" for="wpforms-450855-field_1">FIRST NAME</label><input type="text" id="wpforms-450855-field_1" class="wpforms-field-medium" name="wpforms[fields][1]"></div><div id="wpforms-450855-field_2-container" class="wpforms-field wpforms-field-text" data-field-id="2"><label class="wpforms-field-label" for="wpforms-450855-field_2">LAST NAME</label><input type="text" id="wpforms-450855-field_2" class="wpforms-field-medium" name="wpforms[fields][2]"></div><div id="wpforms-450855-field_4-container" class="wpforms-field wpforms-field-email" data-field-id="4"><label class="wpforms-field-label" for="wpforms-450855-field_4">Email <span class="wpforms-required-label">*</span></label><input type="email" id="wpforms-450855-field_4" class="wpforms-field-medium wpforms-field-required" name="wpforms[fields][4]" required=""></div><div id="wpforms-450855-field_3-container" class="wpforms-field wpforms-field-text" data-field-id="3"><label class="wpforms-field-label" for="wpforms-450855-field_3">PHONE (OPTIONAL)</label><input type="text" id="wpforms-450855-field_3" class="wpforms-field-medium" name="wpforms[fields][3]"></div><div id="wpforms-450855-field_5-container" class="wpforms-field wpforms-field-textarea" data-field-id="5"><label class="wpforms-field-label" for="wpforms-450855-field_5">COMMENTS</label><textarea id="wpforms-450855-field_5" class="wpforms-field-medium" name="wpforms[fields][5]"></textarea></div></div><div class="wpforms-submit-container"><input type="hidden" name="wpforms[id]" value="450855"><input type="hidden" name="wpforms[author]" value="131"><input type="hidden" name="wpforms[post_id]" value="450831"><button type="submit" name="wpforms[submit]" id="wpforms-submit-450855" class="wpforms-submit" data-alt-text="Sending..." data-submit-text="Submit" aria-live="assertive" value="wpforms-submit">Submit</button><img src="http://localhost/app/plugins/wpforms/assets/images/submit-spin.svg" class="wpforms-submit-spinner" style="display: none;" width="26" height="26" alt="Loading"></div></form></div>  <!-- .wpforms-container -->
									<p>&nbsp;</p>
								</div>
							</div>
						</div>
					</div>
				</div>
<?php
	} ?>
		</main>
	</div>
<?php get_footer(); ?>