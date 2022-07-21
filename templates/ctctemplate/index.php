<?php

defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$isHome = JFactory::getApplication()->getMenu()->getActive()->home;

// Defer fontawesome for increased performance. Once the page is loaded javascript changes it to a stylesheet.
$wa  = $this->getWebAssetManager();
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
?>
<!DOCTYPE html>
<html xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">

<head>
    <!-- Start Jdoc head -->
    <jdoc:include type="head" />
    <!-- End Jdoc head -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700;900&family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="logo_round_small_green_border.png" />
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
    <!-- <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template_prostar.css" type="text/css" /> -->
    <!-- PENDING - load this through the assets.json! -->
    <link href="/media/system/css/joomla-fontawesome.min.css?e16548e23f85cbf0a9b8262be0cb74fe" rel="stylesheet">
    <jdoc:include type="styles" />

    <?php
    // Add JavaScript Frameworks
    //JHtml::_('bootstrap.framework');
    JHtml::_('jquery.framework');
    $doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js');
    ?>
</head>

<body>
    <div class="masthead">
        <div class="gap">
            <div class="gap-mask">
                <div class="gap-sky">
                </div>
            </div>
            <a href="<?php echo $this->baseurl ?>">
                <div class="ctc-brand">
                    <img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/logo.png">
                    <span>Christchurch<br>Tramping Club</span>
                </div>
            </a>
            <jdoc:include type="modules" name="user-menu" style="none" />
        </div>
    </div>

    <!-- Top bar -->
    <nav class="navbar navbar-expand-lg navbar-dark ctc-navbar align-items-end" role="navigation">
        <div class="container ctc-header">

            <!--- The menu --->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <jdoc:include type="modules" name="position-1" style="none" />
            </div>

            <!--- Toggler --->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <?php if ($isHome) {
    ?>
        <!-- Homepage content -->
        <div class="container ctc-home-container" id="homepage-container">
            <div class="row">
                <div class="d-lg-none col ctc-main pb-0 mb-0">
                    <div class="jumbotron">
                        <jdoc:include type="modules" name="home-welcome" style="none" />
                    </div>
                    <div class="d-lg-none">
                        <a href="#trip-reports">
                            <span class="fa fa-arrow-down"></span> Jump to Trip Reports
                        </a>
                    </div>
                </div>
                <div class="calendar col-lg-4 col-xl-3 order-lg-12 ctc-main">
                    <jdoc:include type="modules" name="upcoming-trips" style="none" />
                </div>
                <div id="homepage-main" class="col-lg order-lg-1 ctc-main">
                    <jdoc:include type="message" />
                    <div class="jumbotron d-none d-lg-block">
                        <jdoc:include type="modules" name="home-welcome" style="none" />
                    </div>
                    <div id="trip-reports">
                        <jdoc:include type="modules" name="trip-reports" style="none" />
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
    ?>
        <!-- Page content -->
        <div class="container" id="page-container">
            <div class="row">
                <div class="ctc-main col">
                    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container py-4">
            <span class="legal">© 2022 Christchurch Tramping Club
        </div>
        <jdoc:include type="modules" name="footer" />
        </span>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>