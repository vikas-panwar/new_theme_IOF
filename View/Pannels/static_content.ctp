<?php if (DESIGN == 4) { ?>
    <div class="content single-frame">
        <div class="wrap">
            <div class="clearfix">
                <section class="form-layout sign-in" style='min-height: 200px'>
                    <h2><?php echo $content['StoreContent']['name']; ?></h2>
                    <?php echo $content['StoreContent']['content']; ?>
                </section>
            </div>
        </div>
    </div>

<?php } else {
    if ((DESIGN == 1) && ($store_data_app['Store']['store_theme_id']==11)) { ?>

      <div class="ext-menu"><div class="ext-menu-title"><h4><?php echo $content['StoreContent']['name']; ?></h4></div></div>
   <div class="layout-container pages">
       <div class="layout-content pages hc vc">
                <?php echo $content['StoreContent']['content']; ?>
       </div>
   </div>         
            <?php                                                                                                      } else {?>
    <div class="title-bar"><?php echo $content['StoreContent']['name']; ?></div>
    <div class="main-container">
        <div class="inner-wrap profile no-border">
<!--            <div class="common-title">
                <h2><?php echo $content['StoreContent']['name']; ?></h2>
            </div>-->
            <?php echo $content['StoreContent']['content']; ?>
        </div>
    </div>
    <?php } ?>

    <?php
}?>
