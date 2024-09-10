<?php
/*
Template Name: General Ballot Page
*/
	get_header();

$file = file_get_contents('https://cdn.houstonpublicmedia.org/projects/elections/results_FLAT_for_ballot_2024.json');
$json = json_decode($file);
$offices = $races = [];
foreach ($json as $j) {
    if (!in_array($j->office, $offices)) {
        $offices[] = $j->office;
    }
    $office_name = $j->division_type;
    if (!empty($j->office_division)) {
        $office_name .= ' ' . $j->office_division;
    }
    if ($j->office_slug === 'us-house-district-18' && !empty($races[$j->office][$office_name])) {
        $races[$j->office][$office_name]['special'] = $j->candidacies;
    } else {
        $races[$j->office][$office_name] = $j->candidacies;
    }
}

?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?PHP
	while ( have_posts() ) {
		the_post();
		echo hpm_head_banners( get_the_ID(), 'page' ); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php echo hpm_head_banners( get_the_ID(), 'entry' ); ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>

                <section class="section">
                    <div class="row">
                        <div class="col-sm-12 col-lg-8 news-list-left">
                            <div class="row">
                                <?php
                                $rowCount = 0;
                                foreach ( $offices as $office ) {
                                    foreach( $races[ $office ] as $k => $v ) { ?>
                                        <div class="col-sm-4">
                                            <h3 class="title title-full"><?php echo $office . ' ' . ucwords( $k ); ?></h3>
                                            <ul class="list-group">
                                                <?php
                                                foreach ( $v as $kk => $candidate ) {
                                                    if ( $kk !== 'special' ) { ?>
                                                        <li class="list-group-item <?php echo $candidate->party . ( $candidate->is_incumbent ? ' Incumbent' : '' ); ?>"><?php echo $candidate->name . " [" . $candidate->party[0] . "]"; ?></li>
                                                        <?php
                                                    }
                                                } ?>
                                            </ul>
                                            <?php
                                            if ( !empty( $v['special'] ) ) { ?>
                                                <h3 style="padding: 10px;">Special Election</h3>
                                                <ul class="list-group">
                                                    <?php
                                                    foreach ( $v['special'] as $candidate ) { ?>
                                                        <li class="list-group-item <?php echo $candidate->party . ( $candidate->is_incumbent ? ' Incumbent' : '' ); ?>"><?php echo $candidate->name . " [" . $candidate->party[0] . "]"; ?></li>
                                                        <?php
                                                    } ?>
                                                </ul>
                                                <?php
                                            } ?>
                                        </div>
                                        <?php
                                        $rowCount++;
                                        if ( $rowCount % 3 == 0 ) {
                                            echo '</div><div class="row">';
                                        }
                                    }
                                }?>
                            </div>
                        </div>
                </section>
				<footer class="entry-footer">
<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>
<?php
	} ?>
		</main>
	</div>
<?php get_footer(); ?>