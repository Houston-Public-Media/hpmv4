<?php
/*
Template Name: 2026 Primary Ballot Page
*/
get_header();

/**
 * Load ballot JSON
 */
$file = file_get_contents('https://cdn.houstonpublicmedia.org/projects/elections/results_FLAT_for_ballot_2026.json');
$json = json_decode($file);

/**
 * Build race structure:
 * $races[office][race_name][] = candidate
 */
$offices = [];
$races   = [];

foreach ($json as $j) {

    if (!in_array($j->office, $offices, true)) {
        $offices[] = $j->office;
    }

    $race_name = $j->division_type;
    if (!empty($j->office_division)) {
        $race_name .= ' ' . $j->office_division;
    }

    if (!isset($races[$j->office][$race_name])) {
        $races[$j->office][$race_name] = [];
    }

    foreach ($j->candidacies as $candidate) {
        $races[$j->office][$race_name][] = $candidate;
    }
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php while (have_posts()) : the_post(); ?>
            <?php echo hpm_head_banners(get_the_ID(), 'page'); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php echo hpm_head_banners(get_the_ID(), 'entry'); ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <section class="section">
                    <div class="row">

                        <?php
                        $rowCount = 0;

                        foreach ($offices as $office) :
                            foreach ($races[$office] as $race_name => $candidates) :

                                $democrats   = [];
                                $republicans = [];

                                foreach ($candidates as $candidate) {
                                    $party = $candidate->party;

                                    if ($party === 'Democrat') {

                                        $democrats[] = $candidate;
                                    } elseif ($party === 'Republican') {
                                        $republicans[] = $candidate;
                                    }
                                }
                                ?>

                                <div class="col-lg-6 col-sm-12">
                                    <h3 class="title title-full">
                                        <?php
                                        echo ($office === 'U.S. Senate')
                                            ? esc_html($office)
                                            : esc_html($office . ' ' . ucwords($race_name));
                                        ?>
                                    </h3>

                                    <div class="row">
                                        <!-- Democrats -->
                                        <div class="col-6">
                                            <h4 class="party-title democrat">Democrats</h4>
                                            <ul class="list-group">
                                                <?php foreach ($democrats as $c) : ?>
                                                    <li class="list-group-item DEM<?php echo $c->is_incumbent ? ' Incumbent' : ''; ?>">
                                                        <?php echo esc_html($c->name); ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>

                                        <!-- Republicans -->
                                        <div class="col-6">
                                            <h4 class="party-title republican">Republicans</h4>
                                            <ul class="list-group">
                                                <?php foreach ($republicans as $c) : ?>
                                                    <li class="list-group-item REP<?php echo $c->is_incumbent ? ' Incumbent' : ''; ?>">
                                                        <?php echo esc_html($c->name); ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                $rowCount++;
                                if ($rowCount % 2 === 0) {
                                    echo '</div><div class="row">';
                                }
                            endforeach;
                        endforeach;
                        ?>

                    </div>
                </section>

                <footer class="entry-footer">
                    <?php edit_post_link(__('Edit', 'hpmv4'), '<span class="edit-link">', '</span>'); ?>
                </footer>

            </article>
        <?php endwhile; ?>

    </main>
</div>

<?php get_footer(); ?>
