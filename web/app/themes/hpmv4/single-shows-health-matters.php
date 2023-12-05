<?php
/*
Template Name: Health Matters
Template Post Type: shows
*/

/**
 * The template for displaying show pages
 *
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */

get_header(); ?>
	<style>
		#div-gpt-ad-1488818411584-0 {
			display: none !important;
		}
		.article-player-wrap h3 {
			display: none;
		}
		body.single-shows #station-social {
			padding: 1em;
			margin: 0 0 1em 0;
			background-color: var(--main-element-background);
			overflow: hidden;
			width: 100%;
		}
		body.single-shows #station-social h3 {
			font-size: 1.5em;
			/*font-family: var(--hpm-font-condensed);*/
			color: #3f1818;
			margin-bottom: 1rem;
		}
		#float-wrap aside {
			background-color: var(--main-element-background);
		}
		body.single-shows .podcast-badges {
			justify-content: flex-end;
		}
		#audio-nav {
			margin: 0;
			width: 100%;
			padding: 1em 0;
			position: static;
			top: auto;
			left: auto;
			right: auto;
			bottom: auto;
			transform: none;
		}
		#audio-nav nav {
			color: white;
			height: 100%;
			overflow: scroll;
			background-color: #808080;
		}
		#audio-nav .audio-playlist {
			padding: 1em;
			overflow: hidden;
			border-bottom: 1px solid white;
			background-color: rgb(0,98,136);
		}
		section#audio-playlist-player #audio-nav .audio-playlist p {
			color: white;
			font-weight: 500;
			font-size: 1.25em;
			display: block;
			width: 100%;
			padding: 0;
			margin: 0;
		}
		#ap-title {
			text-align: center;
		}
		section #audio-nav ul {
			margin: 0 0 1em 0;
			padding: 0;
			max-height: 28em;
			overflow-y: scroll;
			border-bottom: 1px solid white;
		}
		section #audio-nav ul li {
			border-bottom: 1px solid white;
			padding: 1em;
			/*font: 100 1em/1em var(--hpm-font-main);*/
			overflow: hidden;
			width: 100%;
			height: auto;
			float: none;
			text-align: left;
			color: white;
		}
		section #audio-nav ul li:nth-child(3) img {
			max-width: 100%;
		}
		#audio-nav ul li:hover {
			cursor: pointer;
		}
		#audio-nav ul li.current {
			background-color: var(--main-blue);
			color: white;
			/*font: 700 1em/1em var(--hpm-font-main);*/
		}
		#audio-nav ul li .audio-info {
			width: 100%;
			padding: 0.5em 0;
		}
		.ap-split {
			padding: 1em;
		}
		.show-content > * + * {
			margin-top: 1rem;
		}
		@media screen and (min-width: 34em) {
			body.single-shows #station-social {
				display: grid;
				grid-template-columns: 1fr 1.25fr;
				align-items: center;
			}
			body.single-shows #station-social h3 {
				margin-bottom: 0;
			}
			aside#audio-nav {
				padding: 1em;
				background-color: transparent;
				margin-bottom: 1em;
			}
			#audio-nav {
				padding: 0;
			}
			section #audio-nav ul li {
				padding: 1em;
			}
			section #audio-nav ul li:last-child {
				border-bottom: 0;
			}
			#audio-nav .audio-playlist {
				display: block;
			}
		}
		@media screen and (min-width: 52.5em) {
			body.single-shows #station-social {
				grid-template-columns: 2fr 3fr;
			}
			.ap-split {
				padding: 0;
			}
			section #audio-nav ul {
				float: none;
				width: 100%;
			}
			section #audio-nav ul li {
				padding: 1em;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php
	while ( have_posts() ) {
		the_post();
		$show_id = get_the_ID();
		$show = get_post_meta( $show_id, 'hpm_show_meta', true );
		$show_title = get_the_title();
		$show_content = get_the_content();
		$episodes = HPM_Podcasts::list_episodes( $show_id );
		echo HPM_Podcasts::show_header( $show_id );
	} ?>
            <div class="party-politics-page">
            <div class="row about-party">
                <div class="col-sm-9">
                    <h2 class="title no-bar"> <strong><span>ABOUT <?php echo $show_title; ?></span></strong> </h2>
                    <div class="show-content">
                        <?php echo apply_filters( 'the_content', $show_content ); ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="sidebar-ad">
                        <h4>Support Comes From</h4>
                        <div id="div-gpt-ad-1394579228932-1">
                            <script type='text/javascript'>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
                            </script>
                        </div>
                    </div>
                </div>
            </div>



                <div class="episodes-block">
                    <h2 class="title red-bar"> <strong><span>All Stories</span></strong> </h2>
                    <div class="row">
<?php
	$cat_no = get_post_meta( get_the_ID(), 'hpm_shows_cat', true );
	$top =  get_post_meta( get_the_ID(), 'hpm_shows_top', true );
	$terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
	$term = reset( $terms );
	$cat_args = [
		'cat' => $cat_no,
		'orderby' => 'date',
		'order'   => 'DESC',
		'posts_per_page' => 15,
		'ignore_sticky_posts' => 1
	];
	global $ka;
	$ka = 0;
	if ( !empty( $top ) && $top !== 'None' ) {
		$top_art = new WP_Query( [ 'p' => $top ] );
		$cat_args['posts_per_page'] = 14;
		$cat_args['post__not_in'] = [ $top ];
		if ( $top_art->have_posts() ) {
			while ( $top_art->have_posts() ) {
				$top_art->the_post();
				get_template_part( 'content', 'shows' );
				$ka += 2;
			}
			$post_num = 14;
		}
		wp_reset_query();
	}
	$cat = new WP_Query( $cat_args );
	if ( $cat->have_posts() ) {
		while ( $cat->have_posts() ) {
			$cat->the_post();

			get_template_part( 'content', 'shows' );
			$ka += 2;
		}
	} ?>
				</div>
			</div>
<?php
	if ( $cat->found_posts > 15 ) {
        //wp_pagenavi( array( 'query' => $cat ) );
        echo hpm_custom_pagination($cat->max_num_pages, 4, "/topics/health-matters/page/");

        ?>

			<!--<div class="readmore">
				<a href="/topics/<?php /*echo $term->slug; */?>/page/2">View More <?php /*echo $term->name; */?></a>
			</div>-->
<?php
	}
	$atts = [
		[ 'id' => '372956', 'title' => 'Episode 76: COVID-19 Testing (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/28165247/UHCM_Ep076_COVID_COVIDtesting_DrReed.mp3' ],
		[ 'id' => '372955', 'title' => 'Episode 75: Common Mask Mistakes (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/28165241/UHCM_Ep075_COVID_WearingMasks_DrBush.mp3' ],
		[ 'id' => '372954', 'title' => 'Episode 74: The New Normal (Dr. Camille Leugers)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/28165238/UHCM_Ep074_COVID_OutInPublic_DrLeugers.mp3' ],
		[ 'id' => '372953', 'title' => 'Episode 73: Contact Tracing (Dr. Bettina Beech)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/28165231/UHCM_Ep073_COVID_ContactTracing_DrBeech.mp3' ],
		[ 'id' => '366060', 'title' => 'Episode 72: Mental Health and COVID-19 (Professor William Elder, Ph.D.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/03132552/UHCM_Ep072_COVID_Mentalhealth_Elder.mp3' ],
		[ 'id' => '366059', 'title' => 'Episode 71: Social Distancing (Dr. David Buck)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/03132548/UHCM_Ep071_COVID_SocialDistancing_Buck.mp3' ],
		[ 'id' => '366002', 'title' => 'Episode 70: Caring for the Elderly (Dr. LeChauncey Woodard)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/03091957/UHCM_Ep070_COVID_Eldercare_Woodard01.mp3' ],
		[ 'id' => '366001', 'title' => 'Episode 69: Can COVID-19 Affect My Pregnancy (Dr. Pilkinton)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/03091946/UHCM_Ep069_COVID_Pregnency_Pilkinton01.mp3' ],
		[ 'id' => '366000', 'title' => 'Episode 68: Telehealth Expansion (Dr. Winston Liaw)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/03091937/UHCM_Ep068_COVID_Telehealth_Liaw01.mp3' ],
		[ 'id' => '365999', 'title' => 'Episode 67: Coronavirus Transmission (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/03091932/UHCM_Ep067_COVID-Transmissions_Reed01.mp3' ],
		[ 'id' => '365998', 'title' => 'Episode 66: Medical Masks (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/03091927/UHCM_Ep066_COVID_Masks_DrBush01.mp3' ],
		[ 'id' => '365997', 'title' => 'Episode 65: At-Home Exercise (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/03091922/UHCM_Ep065_COVID_Exercise_DrBush01.mp3' ],
		[ 'id' => '362609', 'title' => 'Episode 64: Novel Coronavirus (Dr. Omar Matuk)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04095331/UHCM_Ep064_NovelCorornavirus_DrMatuk01.mp3' ],
		[ 'id' => '362608', 'title' => 'Episode 63: Preventative Medicine (Dr. Kenya Steele)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04095329/UHCM_Ep063_PreventativeMedicine_DrSteele01.mp3' ],
		[ 'id' => '362607', 'title' => 'Episode 62: Brand Name and Generic Drugs (Dr. Don Briscoe)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04095327/UHCM_Ep062_BrandNameadnGenericDrugs_DrBriscoe01.mp3' ],
		[ 'id' => '362606', 'title' => 'Episode 61: Good Fats vs. Bad Fats (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04095325/UHCM_Ep061_GoodFatsvsBadFats_DrReed01.mp3' ],
		[ 'id' => '362605', 'title' => 'Episode 60: Adult Vaccinations (Dr. Camille Leugers)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04095323/UHCM_Ep060_AdultVaccinations_DrLeugers01.mp3' ],
		[ 'id' => '362604', 'title' => 'Episode 59: HPV Vaccine (Dr. Joel Blumberg)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04095320/UHCM_Ep059_HPVVaccine_DrBlumberg01.mp3' ],
		[ 'id' => '362603', 'title' => 'Episode 58: Finding the Right Primary Care Physician (Dr. Winston Liaw)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04095318/UHCM_Ep058_FindingTheRightPrimaryCarePhysician_DrLiaw01.mp3' ],
		[ 'id' => '362602', 'title' => 'Episode 57: Infertility (Dr. Kimberly Pilkinton)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04095316/UHCM_Ep057_Infertility_DrPilkinton02.mp3' ],
		[ 'id' => '357018', 'title' => 'Episode 56: Is Weight Loss Surgery Right for You? (Dr. Stephen Spann)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/10133728/UHCM_Ep056_IsWeightLossSurgeryRightForYou_DrSpann01.mp3' ],
		[ 'id' => '355610', 'title' => 'Episode 55: Early Flu Season (Dr. LeChauncey Woodard)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/06101050/UHCM_Ep055_EarlyFluSeason_DrWoodard01.mp3' ],
		[ 'id' => '355609', 'title' => 'Episode 54: Flesh-Eating Bacteria 101 (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/06101046/UHCM_Ep054_FleshEatingBacteria_DrBush01.mp3' ],
		[ 'id' => '355608', 'title' => 'Episode 53: Breast Cancer Screening (Dr. Don Briscoe)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/06101042/UHCM_Ep053_BrestCancerScreening_DrBriscoe01.mp3' ],
		[ 'id' => '355607', 'title' => 'Episode 52: Child Suicide on the Rise (Dr. Kristen Kassaw)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/06101039/UHCM_Ep052_ChildSuicideOnTheRise_DrKassaw01.mp3' ],
		[ 'id' => '355606', 'title' => 'Episode 51: Cell Phone Related Injuries on the Rise (Dr. Joel Blumberg)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/06101036/UHCM_Ep051_CellPhoneRelatedInjuriesOnTheRise_DrBlumberg01.mp3' ],
		[ 'id' => '355605', 'title' => 'Episode 50: HIV Prevention Program (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/06101033/UHCM_Ep050_HIVPreventionProgram_DrReed01.mp3' ],
		[ 'id' => '355604', 'title' => 'Episode 49: What Is Shingles? (Dr. Kenya Steele)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/06101030/UHCM_Ep049_WhatIsShingles_DrSteele01.mp3' ],
		[ 'id' => '350758', 'title' => 'Episode 48: Helping a Loved One with Dementia (Dr. LeChauncey Woodard)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04113716/UHCM_Ep048_HelpingALovedOneWithDementia_DrWoodard01.mp3' ],
		[ 'id' => '350757', 'title' => 'Episode 47: Vaping and your Lungs (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04113713/UHCM_Ep047_VapingAndYourLungs_DrReed01.mp3' ],
		[ 'id' => '350756', 'title' => 'Episode 46: STDs on the Rise (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04113710/UHCM_Ep046_STDsOnTheRise_DrReed01.mp3' ],
		[ 'id' => '350755', 'title' => 'Episode 45: Excessive Sweating (Dr. Stephen Spann)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04113708/UHCM_Ep045_ExcessiveSweating_DrSpann01.mp3' ],
		[ 'id' => '350754', 'title' => 'Episode 44: Pregnancy and Flu Shots (Dr. Don Briscoe)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04113704/UHCM_Ep044_PregnancyAndFluShots_DrBriscoe01.mp3' ],
		[ 'id' => '350753', 'title' => 'Episode 43: Genetic Testing (Dr. Omar Matuk)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04113702/UHCM_Ep043_GeneticTesting_DrMatuk01.mp3' ],
		[ 'id' => '350752', 'title' => 'Episode 42: Breast Cancer in Males (Dr. Kenya Steele)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04113659/UHCM_Ep042_BreastCancerInMen_DrSteele01.mp3' ],
		[ 'id' => '350751', 'title' => 'Episode 41: Understanding Endometriosis (Dr. Kenya Steele)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/04113656/UHCM_Ep041_UnderstandingEndometriosis_DrSteele01.mp3' ],
		[ 'id' => '345350', 'title' => 'Episode 40: Signs of Opioid Addiction (Professor William Elder, Ph.D.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/09094702/UHCM_Ep040_SignsOfOpioidAddiction.mp3' ],
		[ 'id' => '345349', 'title' => 'Episode 39: Medical-Legal Partnerships (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/09094701/UHCM_Ep039_MedicalLegalPartnerships_DrBush01.mp3' ],
		[ 'id' => '345348', 'title' => 'Episode 38: Noise-Induced Hearing Loss (Dr. Don Briscoe)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/09094659/UHCM_Ep038_NoiseinducedHearingLoss_DrBriscoe02.mp3' ],
		[ 'id' => '345347', 'title' => 'Episode 37: Understanding Food Insecurity (Dr. David Buck)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/09094658/UHCM_Ep037_UnderstandingFoodInsecurity_DrBuck01.mp3' ],
		[ 'id' => '345346', 'title' => 'Episode 36: Loneliness and Social Isolation (Dr. LeChauncy Woodard)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/09094656/UHCM_Ep036_LonlinessAndSocialIsolation_DrWoodard01.mp3' ],
		[ 'id' => '345345', 'title' => 'Episode 35: Value-Based Health Care and You (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/09094655/UHCM_Ep035_ValueBasedHealthCareAndYou_DrReed02.mp3' ],
		[ 'id' => '345344', 'title' => 'Episode 34: Rise of Population Health (Dr. Kenya Steele)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/09094654/UHCM_Ep034_RiseOfPopulationHealth_DrSteele01.mp3' ],
		[ 'id' => '345343', 'title' => 'Episode 33: Maternal Mortality (Dr. Omar Matuk)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/09094652/UHCM_Ep033_MaternalMortality_DrMatuk02.mp3' ],
		[ 'id' => '340034', 'title' => 'Episode 32: Head Scrather: Protecting Kids From Lice (Dr. David Buck)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/18140828/UHCM_Ep032_Protecting-Kids-From-Lice-Dr-Buck01.mp3' ],
		[ 'id' => '340033', 'title' => 'Episode 31: Signs of Infection (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/18140827/UHCM_Ep031_Signs-of-Infection-Dr-Bush01.mp3' ],
		[ 'id' => '340032', 'title' => 'Episode 30: Telemedicine (Dr. Winston Liaw)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/18140825/UHCM_Ep030_Telemedicine-Dr-Liaw01.mp3' ],
		[ 'id' => '340031', 'title' => 'Episode 29: Depression and Anxiety in Kids (Professor William Elder, Ph.D.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/18140824/UHCM_Ep029_Depression-and-Anxiety-in-Kids-Dr-Elder01.mp3' ],
		[ 'id' => '340030', 'title' => 'Episode 28: Alcohol: How Much is Too Much? (Dr. Kathryn Horn)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/18140823/UHCM_Ep028_Alcohol-How-Much-is-Too-Much-Dr-Horn01.mp3' ],
		[ 'id' => '340029', 'title' => 'Episode 27: Does Fasting Work? (Dr. Don Briscoe)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/18140821/UHCM_Ep027_Does-Fasting-Work-Dr-Briscoe01.mp3' ],
		[ 'id' => '340028', 'title' => 'Episode 26: Health Effects of Poor Air Quality (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/18140819/UHCM_Ep026_Pollution-Dr-Reed01.mp3' ],
		[ 'id' => '340026', 'title' => 'Episode 25: Prediabetes: Now What? (Dr. Stephen J. Spann, M.B.A.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/18140817/UHCM_Ep025_Prediabetes-Now-What-Dr-Spann01.mp3' ],
		[ 'id' => '333126', 'title' => 'Episode 24: Emotional Challenges of Diabetes (Professor William Elder, Ph.D.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/14134626/UHCM_Ep024_EmotionalChallengesOfDiabetes01.mp3' ],
		[ 'id' => '333125', 'title' => 'Episode 23: ADHD or Just High Energy? (Professor William Elder, Ph.D.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/14134622/UHCM_Ep023_ADHD01.mp3' ],
		[ 'id' => '333124', 'title' => 'Episode 22: The Power of Water (Dr. Winston Liaw)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/14134619/UHCM_Ep022_ThePowerOfWater03.mp3' ],
		[ 'id' => '333123', 'title' => 'Episode 21: Food Poisoning Peaks in Summer (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/14134615/UHCM_Ep021_FoodPoisoning01.mp3' ],
		[ 'id' => '333122', 'title' => 'Episode 20: Drowning: CPR Basics (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/14134609/UHCM_Ep020_Drowning01.mp3' ],
		[ 'id' => '333121', 'title' => 'Episode 19: Basic Signs of Heatstroke (Dr. David Buck)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/14134602/UHCM_Ep019_SignsOfHeatstroke01.mp3' ],
		[ 'id' => '333120', 'title' => 'Episode 18: Frequent Urination: How Much Is Too Much? (Dr. Stephen J. Spann, M.B.A.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/14134557/UHCM_Ep018_FrequentUrination01.mp3' ],
		[ 'id' => '333119', 'title' => 'Episode 17: Misuse of Asthma Inhalers (Dr. Kathryn Horn)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/14134551/UHCM_Ep017_MisuseOfAsthmaInhalers01.mp3' ],
		[ 'id' => '326714', 'title' => 'Episode 16: Colorectal Cancer Screening at 50 (Dr. Stephen J. Spann, M.B.A.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/27092400/UHCM_Ep016_ColonCancer_DrSpann01.mp3' ],
		[ 'id' => '326713', 'title' => 'Episode 15: Fall Asleep Without Medication (Professor William Elder, Ph.D.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/27092356/UHCM_Ep015_SleepIssues_DrElder01.mp3' ],
		[ 'id' => '326712', 'title' => 'Episode 14: Baby Boomers and Hepatitis C (Dr. Winston Liaw)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/27092351/UHCM_Ep014_HepatitisC_DrLiaw01.mp3' ],
		[ 'id' => '326711', 'title' => 'Episode 13: Measles Vaccination (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/27092347/UHCM_Ep013_Measles_DrReed01.mp3' ],
		[ 'id' => '326710', 'title' => 'Episode 12:  E-Cigarette Dangers (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/27092341/UHCM_Ep012_Ecigarettes_DrReed01.mp3' ],
		[ 'id' => '326709', 'title' => 'Episode 11: The ABCDE’s of Melanoma (Dr. David Buck)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/27092335/UHCM_Ep011_SkinCancer_DrBuck01.mp3' ],
		[ 'id' => '326708', 'title' => 'Episode 10: Healthy Legs: Treating Poor Circulation (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/27092331/UHCM_Ep010_Circulation_DrBush01.mp3' ],
		[ 'id' => '326707', 'title' => 'Episode 9: Women and Cardiovascular Disease (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/27092327/UHCM_Ep009_CardiovascularDisease_DrBush01.mp3' ],
		[ 'id' => '315985', 'title' => 'Episode 8: Medication Adherence (Dr. David Buck)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/19113514/UHCM_Ep008_MedicationAdherance01.mp3' ],
		[ 'id' => '315984', 'title' => 'Episode 7: Dr. Google? (Dr. Ruth Bush)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/19113510/UHCM_Ep007_InternetHealthcareAdvice01.mp3' ],
		[ 'id' => '315983', 'title' => 'Episode 6: Controlling Pain (Dr. Winston Liaw)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/19113507/UHCM_Ep006_Pain01.mp3' ],
		[ 'id' => '315982', 'title' => 'Episode 5: Seasonal Sickness (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/19113504/UHCM_Ep005_ColdAndFlu01.mp3' ],
		[ 'id' => '315981', 'title' => 'Episode 4: Understanding Flu Vaccines (Dr. Brian Reed)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/19113501/UHCM_Ep004_FluVaccineOptions01.mp3' ],
		[ 'id' => '315980', 'title' => 'Episode 3: Managing Migraines (Dr. Stephen J. Spann, M.B.A.)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/19113459/UHCM_Ep003_Migranes01.mp3' ],
		[ 'id' => '315979', 'title' => 'Episode 2: Combatting High Blood Pressure (Dr. Kathryn Horn)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/19113456/UHCM_Ep002_HighBloodPressure01.mp3' ],
		[ 'id' => '315978', 'title' => 'Episode 1: Sadness vs. Depression (Dr. Kathryn Horn)', 'url' => 'https://cdn.houstonpublicmedia.org/wp-content/uploads/2018/12/19113453/UHCM_Ep001_MentalHealth01.mp3' ],
	]; ?>
			<section id="audio-playlist-player" class="column-left">
				<div class="ap-split">
					<?php echo do_shortcode( '[audio mp3="'.$atts[0]['url'].'"][/audio]' ); ?>
					<h3 id="ap-title" data-next-id="hm<?php echo $atts[1]['id']; ?>"><?php echo $atts[0]['title']; ?></h3>
				</div>
				<aside id="audio-nav">
					<nav id="audio">
						<div class="audio-playlist">
							<p>Archived Episodes</p>
						</div>
						<ul>
						<?php
							foreach ( $atts as $a ) { ?>
								<li <?php echo ( $a['id'] == $atts[0]['id'] ? 'class="current" ' : '' ); ?>id="hm<?php
								echo $a['id']; ?>" data-ytid="<?php echo $a['url']; ?>" data-yttitle="<?php echo $a['title']; ?>">
									<div class="audio-info"><?php echo $a['title']; ?></div>
								</li>
						<?php
							} ?>
						</ul>
					</nav>
				</aside>
			</section>
            </div>
		</main>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			let navs = document.querySelectorAll('#audio-nav ul li');
			Array.from(navs).forEach((nav) => {
				nav.addEventListener('click', () => {
					let ytid = nav.getAttribute('data-ytid');
					let yttitle = nav.getAttribute('data-yttitle');
					let stTitle = document.querySelector('#ap-title');
					let next = document.querySelector('#audio > ul li:first-child').getAttribute('id');
					if (ytid === null) {
						return false;
					} else {
						stTitle.innerHTML = yttitle;
						if (nav.nextElementSibling !== null) {
							next = nav.nextElementSibling.getAttribute('id');
						}
						hpm.players[0].pause();
						hpm.players[0].source = {
							'type': 'audio',
							'title': yttitle,
							'sources': [{
								'src': ytid + '?source=plyr-article',
								'type': 'audio/mpeg'
							}]
						};
						hpm.players[0].play();
						stTitle.setAttribute('data-next-id', next);
						Array.from(navs).forEach((nas) => {
							nas.classList.remove('current');
						});
						nav.classList.add('current');
					}
				});
			});
			setTimeout(() => {
				hpm.players[0].on('ended', (event) => {
					let stTitle = document.querySelector('#ap-title');
					let nextId = stTitle.getAttribute('data-next-id');
					let nextEp = document.querySelector('#' + nextId);
					let ytid = nextEp.getAttribute('data-ytid');
					let next = document.querySelector('#audio > ul li:first-child').getAttribute('id');
					if (ytid === null) {
						return false;
					} else {
						let yttitle = nextEp.getAttribute('data-yttitle');
						if (nextEp.nextElementSibling !== null) {
							next = nextEp.nextElementSibling.getAttribute('id');
						}
						stTitle.innerHTML = yttitle;
						hpm.players[0].source = {
							'type': 'audio',
							'title': yttitle,
							'sources': [{
								'src': ytid + '?source=plyr-article',
								'type': 'audio/mpeg'
							}]
						};
						hpm.players[0].play();
						stTitle.setAttribute('data-next-id', next);
						Array.from(navs).forEach((nas) => {
							nas.classList.remove('current');
						});
						nextEp.classList.add('current');
					}
				});
			}, 500);
		});
	</script>
<?php get_footer(); ?>