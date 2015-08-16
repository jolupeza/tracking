<div class="widget">
    <h3 class="h4 widget__title text-uppercase">Mis datos</h3>
    <ul class="widget__info">
        <li class="widget__info__item">
            <span class="widget__info__item__header text-uppercase">Empresa:</span><!-- end widget__info__item__header -->
            <span class="widget__info__item__content"><?php echo $info->name; ?></span><!-- end widget__info__item__content -->
        </li><!-- end widget__info__item -->

        <li class="widget__info__item">
            <span class="widget__info__item__header text-uppercase">RUC:</span><!-- end widget__info__item__header -->
            <span class="widget__info__item__content"><?php echo $info->user; ?></span><!-- end widget__info__item__content -->
        </li><!-- end widget__info__item -->

        <li class="widget__info__item">
            <span class="widget__info__item__header text-uppercase">Último Pedido:</span><!-- end widget__info__item__header -->
            <span class="widget__info__item__content"><?php echo date('Y-m-d', strtotime($info->created_at)); ?></span><!-- end widget__info__item__content -->
        </li><!-- end widget__info__item -->

        <li class="widget__info__item">
            <span class="widget__info__item__header text-uppercase">N Pedido:</span><!-- end widget__info__item__header -->
            <span class="widget__info__item__content"><?php echo $total->total; ?></span><!-- end widget__info__item__content -->
        </li><!-- end widget__info__item -->
    </ul><!-- end widget__info -->
</div><!-- end widget -->