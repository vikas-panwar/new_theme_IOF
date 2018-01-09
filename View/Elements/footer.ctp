<?php
    switch (DESIGN) {
        case 1 : echo $this->element('design/aaron/common/footer'); break;
        case 4 : echo $this->element('design/oldlayout/footer'); break;
        default : echo $this->element('design/common/footer');
    }
?>