<?php echo doctype('html5'); ?>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="<?php echo $this->config->item('cms_site_desc'); ?>" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ico/favicon.png" />

        <title><?php echo (isset($_title)) ? $_title . ' | ' : ''; ?><?php echo $this->config->item('cms_site_name'); ?></title>

        <!-- Font awesome -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />

        <!-- Bootstrap core CSS -->
        <?php echo $_css; ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="spin"></div><!-- end #spin -->

    <?php if ($this->user->is_logged_in()) : ?>
        <div class="container">
            <header class="header">
                <div class="row">
                    <div class="col-xs-3">
                        <h1 class="header__logo">
                            <a href="<?php echo $this->config->item('web-shanoc'); ?>">
                                <img src="<?php echo base_url(); ?>assets/images/shanoc-logo.png" class="img-responsive" alt="Shanoc" />
                            </a>
                        </h1>
                    </div><!-- end col-xs-3 -->
                    <div class="col-xs-9" id="wrapper-menu">
                        <div class="header__top-menu text-right">
                            <ul class="mnu-top list-inline">
                                <li class="active"><a href="<?php echo base_url(); ?>main">Extranet</a></li>
                                <li><a href="<?php echo base_url(); ?>main/logout">Salir</a></li>
                            </ul>

                            <ul class="list-inline mnu-social">
                                <li>
                                    <a href="http://www.facebook.com/" target="_blank" title="Ir a facebook" class="facebook text-hide">Facebook</a>
                                </li>

                                <li>
                                    <a href="http://www.twitter.com/" target="_blank" title="Ir a twitter" class="twitter text-hide">Twitter</a>
                                </li>
                            </ul>
                        </div> <!-- end of topMenu -->

                        <nav class="main-menu">
                            <ul>
                                <li><a href="<?php echo $this->config->item('web-shanoc'); ?>/#slider-page">Inicio</a></li>
                                <li><a href="<?php echo $this->config->item('web-shanoc'); ?>/#conocenos">Conócenos</a></li>
                                <li><a href="<?php echo $this->config->item('web-shanoc'); ?>/#nuestros-productos">Nuestros Productos</a></li>
                                <li><a href="<?php echo $this->config->item('web-shanoc'); ?>/#novedades">Novedades</a></li>
                                <li><a href="<?php echo $this->config->item('web-shanoc'); ?>/#escribenos">Escríbenos</a></li>
                            </ul>
                        </nav><!-- end main-menu -->
                    </div><!-- end col-xs-9 -->
                </div><!-- end row -->
            </header><!-- end of header -->

            <div class="row">
                <div class="col-xs-3">
                    <aside class="sidebar">
                        <?php if (isset($widgets['sidebar'])) : ?>
                            <?php foreach ($widgets['sidebar'] as $wds) : ?>
                                <?php echo $wds; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </aside><!-- end sidebar -->
                </div><!-- end col-xs-3 -->

                <div class="col-xs-9">
                    <main class="main">
                        <div class="row">
                            <div class="col-xs-12">
                                <?php if ($_warning) : ?>
                                <div class="alert alert-warning">
                                    <?php foreach($_warning as $_msg): ?>
                                        <p><?php echo $_msg; ?></p>
                                    <?php endforeach;?>
                                </div>
                                <?php endif; ?>

                                <?php if ($_success) : ?>
                                <div class="alert alert-success">
                                    <?php foreach($_success as $_msg): ?>
                                        <p><?php echo $_msg; ?></p>
                                    <?php endforeach;?>
                                </div>
                                <?php endif; ?>

                                <?php if ($_error) : ?>
                                <div class="alert alert-danger">
                                    <?php foreach($_error as $_msg): ?>
                                        <p><?php echo $_msg; ?></p>
                                    <?php endforeach;?>
                                </div>
                                <?php endif; ?>

                                <?php if ($_info) : ?>
                                <div class="alert alert-info">
                                    <?php foreach($_info as $_msg): ?>
                                        <p><?php echo $_msg; ?></p>
                                    <?php endforeach;?>
                                </div>
                                <?php endif; ?>
                            </div><!-- end col-xs-12 -->
                        </div><!-- end row -->

                        <?php foreach($_content as $_view): ?>
                            <?php include $_view;?>
                        <?php endforeach; ?>
                    </main><!-- end main -->
                </div><!-- end col-xs-9 -->
            </div><!-- end row -->
        </div><!-- end container -->
    <?php else : ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php if ($_warning) : ?>
                    <div class="alert alert-warning">
                        <?php foreach($_warning as $_msg): ?>
                            <p><?php echo $_msg; ?></p>
                        <?php endforeach;?>
                    </div>
                    <?php endif; ?>

                    <?php if ($_success) : ?>
                    <div class="alert alert-success">
                        <?php foreach($_success as $_msg): ?>
                            <p><?php echo $_msg; ?></p>
                        <?php endforeach;?>
                    </div>
                    <?php endif; ?>

                    <?php if ($_error) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($_error as $_msg): ?>
                            <p><?php echo $_msg; ?></p>
                        <?php endforeach;?>
                    </div>
                    <?php endif; ?>

                    <?php if ($_info) : ?>
                    <div class="alert alert-info">
                        <?php foreach($_info as $_msg): ?>
                            <p><?php echo $_msg; ?></p>
                        <?php endforeach;?>
                    </div>
                    <?php endif; ?>

                </div><!-- end col-xs-12 -->
            </div><!-- end row -->

            <?php foreach($_content as $_view): ?>
                <?php include $_view;?>
            <?php endforeach; ?>
        </div><!-- end container -->
    <?php endif; ?>

        <!-- Load Javascript -->
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/scripts/libraries/jquery/jquery-1.11.0.min.js"><\/script>')</script>
        <script> var _root_ = '<?php echo base_url(); ?>'</script>
        <?php echo $_js; ?>
    </body>
</html>