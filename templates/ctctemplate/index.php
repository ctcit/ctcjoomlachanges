<?php
use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$activePage = JFactory::getApplication()->getMenu()->getActive();
$isHome = $activePage->home;
$title = "CTC - $activePage->title";
$this->setTitle($title);
$heading = $activePage->getParams()->get('page_heading');
$pageType = Factory::getApplication()->getInput()->get('view');

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
    <link rel="icon" type="image/png" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/favicon.png" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/common.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
    <!-- <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template_prostar.css" type="text/css" /> -->
    <!-- PENDING - load this through the assets.json! -->
    <link href="/media/system/css/joomla-fontawesome.min.css?e16548e23f85cbf0a9b8262be0cb74fe" rel="stylesheet">
    <jdoc:include type="styles" />

    <?php
    // Add JavaScript Frameworks
    //JHtml::_('bootstrap.framework');
    JHtml::_('jquery.framework');
    //$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js');
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
        <main class="container ctc-home-container ctc-main" id="homepage-container">
            <div class="row">
                <!-- Mobile only - home welcome first, then calendar, then trip report -->
                <div class="d-lg-none col ctc-col pb-0 mb-0">
                    <div id="socials" class="px-2 display-flex">
                        <a href="https://facebook.com/ctcnz"><i class="fab fa-facebook px-2" style="color: #4267B2; font-size:2rem"></i></a>
                        <a href="https://instagram.com/christchurchtrampingclub"><i class="fab fa-instagram px-2" style="font-size:2rem"></i></a>
                    </div>
                    <jdoc:include type="message" />
                    <div class="home-welcome">
                        <jdoc:include type="modules" name="home-welcome" style="none" />
                    </div>
                    <div class="d-lg-none">
                        <a href="#trip-reports">
                            <span class="fa fa-arrow-down"></span> Jump to Trip Reports
                        </a>
                    </div>
                </div>
                <div class="calendar col-lg-4 col-xl-3 order-lg-12 ctc-col">
                    <div id="socials" class="d-none d-lg-flex pb-3 pt-2">
                        <a href="https://facebook.com/ctcnz"><i class="fab fa-facebook px-2" style="color: #4267B2; font-size:2rem"></i></a>
                        <a href="https://instagram.com/christchurchtrampingclub"><i class="fab fa-instagram px-2" style="font-size:2rem"></i></a>
                    </div>
                    <jdoc:include type="modules" name="upcoming-trips" style="none" />
                </div>
                <div id="homepage-main" class="col-lg order-lg-1 ctc-col">
                    <!-- Non-Mobile only - home welcome comes in here -->
                    <div class="d-none d-lg-block">
                        <jdoc:include type="message" />
                        <div class="home-welcome">
                            <jdoc:include type="modules" name="home-welcome" style="none" />
                        </div>
                    </div>
                    <div id="trip-reports">
                        <jdoc:include type="modules" name="trip-reports" style="none" />
                    </div>
                </div>
            </div>
        </main>
    <?php
    } else if ($pageType == 'wrapper') {
    ?>
        <!-- Page content for iframe wrapper pages
             PENDING - Eventually let them go fill-width -->
        <main class="container-fluid ctc-main" id="page-container">
            <div class="row justify-content-center">
                <div class="ctc-col col ctc-wrapper-col">
                    <jdoc:include type="message" />
                    <!--<h1>
                        <?php //echo $heading != "" ? $heading : $title; ?>
                    </h2>-->
                    <jdoc:include type="component" />
                </div>
            </div>
        </main>
    <?php
    } else { ?>
        <!-- Page content for 'normal' pages -->
        <main class="container ctc-main" id="page-container">
            <div class="row">
                <div class="ctc-left-menu col col-auto"><jdoc:include type="modules" name="left-menu" style="none" /></div>
                <div class="ctc-col col">
                    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                </div>
            </div>
        </main>
    <?php
    }
    ?>

    <!-- Footer -->
    <footer class="footer-container">
        <div class="footer px-5">
            <div class="row justify-content-between">
                <div class="col-auto d-flex">
                    <div class="d-none d-md-block pr-4">
                        <img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/logo.png" style="width: 140px">
                    </div>
                    <div class="pt-1 pr-4">
                        <p>
                            <a href="index.php/join-us">Join Us</a><br>
                            <a href="index.php/about/contact-us">Contact Us</a><br>
                            <a href="index.php/payment-details">Payment Details</a>
                        </p>
                        <p>Affiliated to <a href="https://fmc.org.nz">Federated Mountain Clubs</a></p>
                        <p>© 2023 Christchurch Tramping Club</p>
                    </div>
                </div>
                <div class="col-auto pt-3">
                    <p><a href="https://facebook.com/ctcnz"><i class="fab fa-facebook pr-2" style="color: #4267B2; font-size:2rem"></i> facebook.com/ctcnz</a></p>
                    <p><a href="https://instagram.com/christchurchtrampingclub"><i class="fab fa-instagram pr-2" style="font-size:2rem"></i> @christchurchtrampingclub</a></p>
                </div>
            </div>
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