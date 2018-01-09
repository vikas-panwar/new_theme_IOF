<div class="flexslider">
    <ul class="slides">
        <?php
        if (!empty($photo)) {
            foreach ($photo as $photos) {
                if (!empty($photos['MerchantGallery']['image']) && file_exists(WWW_ROOT . '/merchantSliderImages/' . $photos['MerchantGallery']['image'])) {
                    ?>
                    <li> <?php echo $this->Html->image('/merchantSliderImages/' . $photos['MerchantGallery']['image'], array('alt' => 'Image')) ?>
                        <?php if (!empty($photos['MerchantGallery']['description'])) { ?>
                            <p class="flex-caption">
                                <?php echo $photos['MerchantGallery']['description']; ?>
                            </p>
                        <?php } ?>
                    </li>
                <?php } else {
                    ?>
                    <li><?php echo $this->Html->image('img/hq/showcase-01.jpg', array('alt' => 'Image')) ?></li>
                    <?php
                }
            }
        }
        ?>
    </ul>
</div>