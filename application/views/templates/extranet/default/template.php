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
        <header class="container-fluid header navbar-fixed-top inner">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="header__logo">
                        <a href="<?php echo base_url(); ?>extranet/main"><img src="<?php echo base_url(); ?>assets/images/shanoc-logo-small.png" alt="" class="img-responsive" /></a>
                    </h1><!-- end header__logo -->

                    <a class="header__logout" href="<?php echo base_url(); ?>extranet/main/logout">Cerrar sessión</a>
                </div>
            </div>
        </header><!-- end header -->

        <aside class="sidebar">
            <nav class="main_menu">
                <?php $item = $this->template->getViewId(); ?>
                <ul class="main_menu__ul text-center">
                    <?php $active = ($item === 'orders') ? 'active' : ''; ?>
                    <li class="main_menu__ul__li <?php echo $active ?>"><a href="<?php echo base_url(); ?>extranet/main"><i class="icon-edit"></i>Pedidos</a></li>
                    <?php $active = ($item === 'customers') ? 'active' : ''; ?>
                    <li class="main_menu__ul__li <?php echo $active; ?>"><a href="<?php echo base_url(); ?>extranet/customers"><i class="icon-group"></i>Clientes</a></li>
                    <?php $active = ($item === 'states') ? 'active' : ''; ?>
                    <li class="main_menu__ul__li <?php echo $active; ?>"><a href="<?php echo base_url(); ?>extranet/states"><i class="icon-recycle"></i>Estados</a></li>
                    <?php $active = ($item === 'advertising') ? 'active' : ''; ?>
                    <li class="main_menu__ul__li <?php echo $active; ?>"><a href="<?php echo base_url(); ?>extranet/advertising"><i class="fa fa-buysellads"></i>Publicidad</a></li>
                </ul>
            </nav>
        </aside><!-- end sidebar -->

        <main class="main">
            <div class="container width-auto">
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
        </main><!-- end main -->
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
        <script type="text/javascript" src="http://fgnass.github.io/spin.js/spin.min.js"></script>
        <?php echo $_js; ?>
    </body>
</html>