<?php
/*
Template Name: General Elections 2026 Ballot Page
*/
get_header();

$statewide_file = file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/statewide-2026-general.json' );
$statewide_json = json_decode( $statewide_file, true );

$harris_json	 = json_decode( file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/harris-county-2026-general.json'), true );
$fortbend_json   = json_decode( file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/fort-bend-county-2026-general.json'), true );
$galveston_json  = json_decode( file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/galveston-county-2026-general.json'), true );
$montgomery_json = json_decode( file_get_contents( 'https://cdn.houstonpublicmedia.org/projects/elections/2026/montgomery-county-2026-general.json'), true );

function normalize_county_races( $county_json ) {
    $flat = [];
    $normalize_candidates = function( $candidates, $fallback_party = '' ) {
        $normalized = [];
        if ( ! is_array( $candidates ) ) {
            return $normalized;
        }
        foreach ( $candidates as $candidate ) {
            if ( ! is_array( $candidate ) ) {
                continue;
            }
            // Skip things that clearly aren't candidate records.
            if ( ! isset( $candidate['name'] ) ) {
                continue;
            }
            $normalized[] = [
                'name'         => $candidate['name'] ?? '',
                'party'        => $candidate['party'] ?? $fallback_party,
                'is_incumbent' => $candidate['is_incumbent'] ?? false,
            ];
        }
        return $normalized;
    };
    $flatten_candidates = function( $data ) use ( $normalize_candidates ) {
        $all_candidates = [];
        if ( ! is_array( $data ) ) {
            return $all_candidates;
        }
        foreach ( $data as $key => $value ) {
            if ( ! is_array( $value ) ) {
                continue;
            }
            if (
                isset( $value[0] ) &&
                is_array( $value[0] )
            ) {
                $all_candidates = array_merge(
                    $all_candidates,
                    $normalize_candidates( $value )
                );
                continue;
            }
            if (
                strtolower( $key ) === 'democrat' ||
                strtolower( $key ) === 'republican' ||
                strtolower( $key ) === 'libertarian' ||
                strtolower( $key ) === 'green' ||
                strtolower( $key ) === 'independent'
            ) {
                $all_candidates = array_merge(
                    $all_candidates,
                    $normalize_candidates( $value, $key )
                );
                continue;
            }
            if ( isset( $value['name'] ) ) {

                $all_candidates = array_merge(
                    $all_candidates,
                    $normalize_candidates( [ $value ] )
                );
            }
        }
        return $all_candidates;
    };
    $add_race = function( $race_name, $candidates ) use ( &$flat ) {
        if ( empty( $race_name ) ) {
            return;
        }
        if ( ! is_array( $candidates ) ) {
            $candidates = [];
        }
        $flat[] = [
            'race'       => $race_name,
            'candidates' => array_values( $candidates ),
        ];
    };
    $offices = $county_json['local_county_offices']
        ?? $county_json['county_offices']
        ?? [];

    foreach ( $offices as $office_name => $office_data ) {
        if ( ! is_array( $office_data ) ) {
            continue;
        }
        if (
            isset( $office_data['democrat'] ) ||
            isset( $office_data['republican'] )
        ) {
            $add_race(
                ucwords( str_replace( '_', ' ', $office_name ) ),
                $flatten_candidates( $office_data )
            );
            continue;
        }
        if ( isset( $office_data['candidates'] ) ) {
            $add_race(
                $office_data['office'] ?? $office_name,
                $normalize_candidates( $office_data['candidates'] )
            );
            continue;
        }
        foreach ( $office_data as $precinct => $precinct_data ) {
            if ( ! is_array( $precinct_data ) ) {
                continue;
            }
            $candidates = $flatten_candidates( $precinct_data );
            if ( empty( $candidates ) ) {
                continue;
            }
            $add_race(
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $office_name . ' ' . $precinct
                    )
                ),
                $candidates
            );
        }
    }
    $judicial = $county_json['judicial_races']
        ?? $county_json['local_judicial_races']
        ?? [];

    foreach ( $judicial as $key => $races ) {
        if ( ! is_array( $races ) ) {
            continue;
        }
        if ( isset( $races['office'] ) ) {
            $add_race(
                $races['office'],
                $normalize_candidates(
                    $races['candidates'] ?? []
                )
            );
            continue;
        }
        foreach ( $races as $race_name => $race_data ) {
            if ( ! is_array( $race_data ) ) {
                continue;
            }
            $candidates = $flatten_candidates( $race_data );
            if ( empty( $candidates ) ) {
                continue;
            }
            $add_race(
                ucwords(
                    str_replace( '_', ' ', $race_name )
                ),
                $candidates
            );
        }
    }

    $constables = $county_json['constables'] ?? [];
    foreach ( $constables as $key => $races ) {
        if ( ! is_array( $races ) ) {
            continue;
        }
        if ( isset( $races['office'] ) ) {
            $add_race(
                $races['office'],
                $normalize_candidates(
                    $races['candidates'] ?? []
                )
            );
            continue;
        }
        foreach ( $races as $race_name => $race_data ) {
            if ( ! is_array( $race_data ) ) {
                continue;
            }
            $candidates = $flatten_candidates( $race_data );

            if ( empty( $candidates ) ) {
                continue;
            }
            $add_race(
                ucwords(
                    str_replace( '_', ' ', $race_name )
                ),
                $candidates
            );
        }
    }
    foreach ( $county_json['propositions'] ?? [] as $prop ) {
        $options = [];
        foreach ( $prop['options'] ?? [] as $option ) {
            $options[] = [
                'name'         => $option['option'] ?? '',
                'party'        => '',
                'is_incumbent' => false,
            ];
        }
        $add_race(
            $prop['title'] ?? 'Proposition',
            $options
        );
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
//$combined_json['Statewide'] = $statewide_json;
$combined_json['Statewide'] = array_map(
    function ( $race ) {
        return [
            'race'       => $race['office'] ?? '',
            'candidates' => $race['candidacies'] ?? [],
        ];
    },
    $statewide_json
);
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php while ( have_posts() ) { the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php echo hpm_head_banners( get_the_ID(), 'entry' ); ?>
                <div class="entry-content"><?php the_content(); ?></div>
                <?php foreach ( $combined_json as $county_name => $races ) { ?>
                    <details class="section county-section">
                        <summary class="county-title">
                            <?php echo esc_html( $county_name ); ?>
                        </summary>
                        <div class="row">
                            <?php
                            $row_count = 0;
                            foreach ( $races as $race ) {
                                //print_r($races);
                                $race_name  = $race['race'] ?? '';
                                $candidates = $race['candidates'] ?? [];
                                $row_count++;
                                ?>
                                <div class="col-lg-6 col-sm-12">
                                    <h3 class="title title-full">
                                        <?php echo esc_html( $race_name ); ?>
                                    </h3>
                                    <ul class="list-group">
                                        <?php foreach ( $candidates as $candidate ) {
                                            //print_r($candidates);
                                            //$party = strtolower( trim( $candidate['party'] ?? '' ) );

                                            //$classes = [ 'list-group-item' ];
                                            $classes = [ 'list-group-item' ];
                                            /*if ( $party === 'democratic' ) {
                                                $classes[] = 'democrat';
                                            } elseif ( $party === 'republican' ) {
                                                $classes[] = 'republican';
                                            }

                                            if ( ! empty( $candidate['is_incumbent'] ) ) {
                                                $classes[] = 'Incumbent';
                                            }*/

                                            $incumbent = $candidate['is_incumbent'] ?? false;
                                            ?>
                                            <li class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
                                                <?php echo esc_html( $candidate['name'] ?? '' ); echo " - <span style='font-size: 12px;'><i>".$candidate['party'] . ( $incumbent ? ' Incumbent' : '' )."</i></span>"; ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php
                                if (
                                    $row_count % 2 === 0 &&
                                    $row_count < count( $races )
                                ) {
                                    echo '</div><div class="row">';
                                }
                            }
                            ?>
                        </div>
                    </details>
                <?php } ?>
			</article>
		<?php } ?>
	</main>
</div>
<?php get_footer(); ?>