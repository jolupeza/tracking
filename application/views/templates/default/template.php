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
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="logo">Aquí irá el header</h1>
                    </div><!-- end col-xs-12 -->
                </div><!-- end row -->
            </div><!-- end container -->
        </header><!-- end header -->

        <div class="container">
            <div class="row">
            <?php if ($this->user->is_logged_in()) : ?>
                <div class="col-xs-3">
                    <aside class="sidebar">
                        <div class="widget">
                            <h3 class="widget__title text-uppercase">Mis datos</h3><!-- end widget__title -->

                            <ul class="widget__list">
                                <li><span class="widget__list__title text-uppercase">Empresa:</span> </li>
                                <li><span class="widget__list__title text-uppercase">RUC:</span> </li>
                                <li><span class="widget__list__title text-uppercase">Sector:</span> </li>
                                <li><span class="widget__list__title text-uppercase">Ciudad:</span> </li>
                                <li><span class="widget__list__title text-uppercase">Último pedido:</span> </li>
                                <li><span class="widget__list__title text-uppercase">N pedidos:</span> </li>
                            </ul>
                        </div><!-- end widget -->
                    </aside><!-- end sidebar -->
                </div><!-- end col-xs-3 -->
                <div class="col-xs-9">
                    <main class="main">
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

                        <?php foreach($_content as $_view): ?>
                            <?php include $_view;?>
                        <?php endforeach; ?>
                    </main><!-- end main -->
                </div><!-- end col-xs-9 -->

                <?php /*if (isset($widgets['sidebar'])) : ?>
                <aside class="sidebar-right visible">
                    <button type="button" id="toggle-menu">
                        <i class="fa fa-calendar fa-lg"></i>
                    </button>

                    <?php foreach ($widgets['sidebar'] as $wds) : ?>
                        <?php echo $wds; ?>
                    <?php endforeach; ?>

                    <?php $disabled = (isset($_disabled_add) && $_disabled_add) ? 'disabled' : ''; ?>
                    <?php if ($this->user->has_permission('edit_posts')) : ?>
                        <p class="text-center"><a href="<?php echo base_url(); ?>reminders/add" class="btn btn-blue <?php echo $disabled; ?>"><?php echo $this->lang->line('cms_general_title_add_reminder'); ?></a></p>
                    <?php endif; ?>
                </aside>
                <?php endif; */ ?>
            <?php else : ?>
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

                    <?php foreach($_content as $_view): ?>
                        <?php include $_view;?>
                    <?php endforeach; ?>
                </div><!-- end col-xs-12 -->
            <?php endif; ?>
            </div><!-- end row -->
        </div><!-- end container -->

        <!-- Load Javascript -->
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/scripts/libraries/jquery/jquery-1.11.0.min.js"><\/script>')</script>
		<script> var _root_ = '<?php echo base_url(); ?>'</script>
        <script type="text/javascript" src="http://fgnass.github.io/spin.js/spin.min.js"></script>
        <?php echo $_js; ?>
    </body>
</html>