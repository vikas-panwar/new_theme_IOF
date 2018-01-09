<?php
   switch (DESIGN) {
       case 1 :
           echo '<div class="ext-menu"><div class="ext-menu-title"><h4>&nbsp;</h4></div></div>';
           echo $this->element('design/aaron/common/success');
           break;
       case 4 : echo $this->element('design/oldlayout/innerpage/success'); break;
       default : echo $this->element('design/common/success');
   }
?>