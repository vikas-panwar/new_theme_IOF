<?php
   switch (DESIGN) {
        case 1 : echo $this->element('design/aaron/element/order-element-calculation'); break;
        case 4 : {
            if(!empty($finalItem)) echo $this->element('design/oldlayout/element/order-element-calculation');
            break;
        }
        default : echo $this->element('orderoverview/overview');
    }
?>