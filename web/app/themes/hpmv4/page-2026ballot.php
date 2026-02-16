<?php
/*
Template Name: 2026 Primary Ballot Page (All Counties Top)
*/
get_header();

/**
 * Load statewide races
 */
$statewide_file = file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/statewide-primary-ballot.json' );
$statewide_json = json_decode( $statewide_file, true );

/**
 * Load county JSONs
 */
$harris_json	 = json_decode( file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/harris-county-primary-races.json'), true );
$fortbend_json   = json_decode( file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/fortbend-county-primary-races.json'), true );
$galveston_json  = json_decode( file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/galveston-county-primary-races.json'), true );
$montgomery_json = json_decode( file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/montgomery-county-primary-races.json'), true );

/**
 * Normalize county JSONs to unified flat structure
 */
function normalize_county_races( $county_json ) {
	$flat = [];

	// County offices
	$offices = $county_json['local_county_offices'] ?? $county_json['county_offices'] ?? [];
	foreach ( $offices as $office_name => $office_data ) {

		// Harris County style (party-separated)
		if ( is_array( $office_data ) && ( isset( $office_data['democrat'] ) || isset( $office_data['republican'] ) ) ) {
			$all_candidates = [];
			foreach ( $office_data as $party => $candidates ) {
				foreach ( $candidates as $c ) {
					$all_candidates[] = [
						'name' => $c['name'],
						'party' => $c['party'],
						'is_incumbent' => $c['is_incumbent'] ?? false
					];
				}
			}
			$flat[] = [
				'office' => ucwords( str_replace( '_', ' ', $office_name ) ),
				'division_type' => '',
				'office_division' => '',
				'candidacies' => $all_candidates
			];

			// Normal county_offices array (Fort Bend, Galveston, Montgomery)
		} elseif ( isset( $office_data['candidates'] ) ) {
			$flat[] = [
				'office' => $office_data['office'] ?? $office_name,
				'division_type' => '',
				'office_division' => '',
				'candidacies' => $office_data['candidates']
			];
			// Harris county commissioner by precinct
		} else {
			foreach ( $office_data as $precinct => $parties ) {
				$race_name = ucwords( str_replace( '_', ' ', $precinct ) );
				$all_candidates = [];
				foreach ( $parties as $party => $candidates ) {
					foreach ( $candidates as $c ) {
						$all_candidates[] = [
							'name' => $c['name'],
							'party' => $c['party'],
							'is_incumbent' => $c['is_incumbent'] ?? false
						];
					}
				}
				$flat[] = [
					'office' => ucwords( str_replace( '_', ' ', $office_name ) ),
					'division_type' => $race_name,
					'office_division' => '',
					'candidacies' => $all_candidates
				];
			}
		}
	}

	// Judicial races
	$judicial = $county_json['judicial_races'] ?? $county_json['local_judicial_races'] ?? [];
	foreach ( $judicial as $key => $races ) {
		if ( isset( $races['office'] ) ) { // array of objects
			$flat[] = [
				'office' => $races['office'],
				'division_type' => 'Judicial',
				'office_division' => '',
				'candidacies' => $races['candidates']
			];
		} else { // Harris style nested
			foreach ( $races as $race_name => $candidates ) {
				$flat[] = [
					'office' => ucwords( str_replace( '_', ' ', $race_name ) ),
					'division_type' => 'Judicial',
					'office_division' => '',
					'candidacies' => $candidates
				];
			}
		}
	}

	// Propositions
	if ( !empty( $county_json['propositions'] ) ) {
		foreach ( $county_json['propositions'] as $prop ) {
			$flat[] = [
				'office' => $prop['title'] ?: 'Proposition',
				'division_type' => 'Proposition',
				'office_division' => '',
				'candidacies' => array_map( function( $o ) {
					return [
						'name' => $o['option'],
						'party' => '',
						'is_incumbent' => false
					];
				}, $prop['options'] ?? [] )
			];
		}
	}
	return $flat;
}

/**
 * Merge all counties in order
 */
$counties = [
	'Harris County' => normalize_county_races( $harris_json ),
	'Fort Bend County' => normalize_county_races( $fortbend_json ),
	'Galveston County' => normalize_county_races( $galveston_json ),
	'Montgomery County' => normalize_county_races( $montgomery_json ),
];

/**
 * Optional: filter by precinct
 */
$user_precinct = null;
foreach ( $counties as $county_name => &$county_races ) {
	if ( $user_precinct ) {
		$county_races = array_filter( $county_races, function( $race ) use ( $user_precinct ) {
			return ( $race['precinct'] ?? null ) === $user_precinct;
		});
	}
}

/**
 * Statewide races appended at the end
 */
$combined_json = array_map( function( $races ) { return $races; }, $counties );
$combined_json['Statewide'] = $statewide_json; ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) { the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content"><?php the_content(); ?></div>
				<?php foreach ( $combined_json as $county_name => $races ) { ?>
					<section class="section county-section">
						<h2 class="county-title"><?php echo esc_html( $county_name ); ?></h2>
						<div class="row">
					<?php
						$rowCount = 0;
						foreach ( $races as $race ) {
							$democrats = [];
							$republicans = [];
							foreach ( $race['candidacies'] as $candidate ) {
								$party = $candidate['party'] ?? '';
								if ( $party === 'Democrat' ) $democrats[] = $candidate;
								elseif ( $party === 'Republican' ) $republicans[] = $candidate;
							} ?>
							<div class="col-lg-6 col-sm-12">
								<h3 class="title title-full">
									<?php echo esc_html($race['office'] . ( !empty($race['division_type'] ) ? ' - ' . $race['division_type'] . ' ' . $race['office_division'] : '' ) ); ?>
								</h3>
								<div class="row">
								<?php if ( !empty( $democrats ) ) { ?>
									<div class="col-6">
										<h4 class="party-title democrat">Democrats</h4>
										<ul class="list-group">
											<?php foreach ( $democrats as $c ) { ?>
												<li class="list-group-item DEM<?php echo !empty( $c['is_incumbent'] ) ? ' Incumbent' : ''; ?>"><?php echo esc_html($c['name']); ?></li>
											<?php } ?>
										</ul>
									</div>
								<?php
									}
									if ( !empty( $republicans ) ) { ?>
									<div class="col-6">
										<h4 class="party-title republican">Republicans</h4>
										<ul class="list-group">
											<?php foreach ( $republicans as $c ) { ?>
												<li class="list-group-item REP<?php echo !empty( $c['is_incumbent'] ) ? ' Incumbent' : ''; ?>"><?php echo esc_html( $c['name'] ); ?></li>
											<?php } ?>
										</ul>
									</div>
								<?php
									}
									if ( empty( $democrats ) && empty( $republicans ) ) { ?>
									<ul class="list-group">
										<?php foreach ( $race['candidacies'] as $c ) { ?>
											<li class="list-group-item"><?php echo esc_html( $c['name'] ); ?></li>
										<?php } ?>
									</ul>
								<?php } ?>
									</div>
								</div>
							<?php
								$rowCount++;
								if ( $rowCount % 2 === 0 ){ echo '</div><div class="row">'; }
						}
							?>
						</div>
					</section>
				<?php } ?>

			</article>
		<?php } ?>
	</main>
</div>
<?php get_footer(); ?>
