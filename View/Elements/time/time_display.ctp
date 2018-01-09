<?php
$deliveryTime = $this->Session->read('Order.store_pickup_time');
$pickUpHour = '';
$pickUpMin = '';
$storeId = $this->Session->read('store_id');
$timeFormate = $this->Common->storeTimeFormateValue($storeId);
if(isset($deliveryTime) && !empty($deliveryTime)){
    
    $deliveryTime = explode(' ',$deliveryTime);
    
    $deliveryTimeOnly = explode(':',$deliveryTime[0]);
    $pickUpHour = $deliveryTimeOnly[0];
    
    if($timeFormate == 1 && $pickUpHour <= 12)
    {
        $pickUpHour += 12; 
    }
    
    if($pickUpHour >= 0 && $pickUpHour <= 9)
    {
        $pickUpHour = '0'. $pickUpHour;
    }
    $pickUpMin = $deliveryTimeOnly[1];
    if($pickUpMin == 'null')
    {
        $pickUpMin = '00';
    }
}
?>
<input class="timeavail" type="hidden" value='<?php echo json_encode($TimeArr); ?>'>
<div class="time-setting">
    <label>Hour <em>*</em></label>

    <?php
    if (empty($TimeArr)) {
        echo $this->Form->input('Store.pickup_time', array('type' => 'select', 'class' => 'inbox', 'empty' => 'Store is closed on this day', 'options' => $TimeArr, 'label' => false, 'div' => false));
    } else {
        ?>
        <select id="StorePickuphour" class="inbox user-detail" name="data[Store][pickup_hour]">
            <?php
            foreach ($TimeArr as $key => $Hours) {
                if (!empty($Hours)) {
                    if (count($AMPM) > 1) {
                        $vkey = $key;
                        if ($vkey >= 12) {
                            if ($vkey == 12) {
                                $hrval = $vkey . " pm";
                            } else {
                                $hrval = ($vkey - 12) . " pm";
                            }
                        } else {
                            $vkey = $key;
                            if ($vkey == '00') {
                                $vkey = '12';
                            }
                            $hrval = $vkey . " am";
                        }
                    } else {
                        $vkey = $key;
                        if ($vkey == '00') {
                            $vkey = '12';
                        }
                        $hrval = $vkey;
                    }
                    $selected= '';
                    if($pickUpHour == $key)
                    {
                        $selected= 'selected';
                    }
                    echo "<option value='$key' " . $selected . ">$hrval</option>";
                }
            }
        }
        ?>
    </select>
</div>

<div class="time-setting">
    <label>Minute <em>*</em></label>

    <select id="StorePickupmin" class="inbox user-detail" name="data[Store][pickup_minute]">
        <?php
        $i = 1;
        if($pickUpHour != '')
        {
            foreach ($TimeArr[$pickUpHour] as $hkey => $Hour) {
                $selected= '';
                if($pickUpMin == $Hour)
                {
                    $selected= 'selected';
                }
                echo "<option value='$Hour' " . $selected . ">$Hour</option>";
            }
        }
        else 
        {
            foreach ($TimeArr as $key => $Hours) {
                if ($i == 1) {
                    foreach ($Hours as $hkey => $Hour) {
                        $selected= '';
                        if($pickUpMin == $Hour)
                        {
                            $selected= 'selected';
                        }
                        echo "<option value='$Hour' " . $selected . ">$Hour</option>";
                    }
                }
                $i++;
            }
	}
        ?>
    </select>
</div>

<?php //pr($TimeArr); ?>



<script>
    $(document).ready(function () {
        $("#StorePickuphour").on('change', function () { // To Show
            //alert(1);
            var el = $(this);
            var selectedHour = el.val();
            //var Hourdata = $(".timeavail").val();
            var Hourdata = el.parent().prevAll("input[type=hidden]").val();
            var parsedData = JSON.parse(Hourdata);
            //console.log(parsedData);
            $.each(parsedData, function (key, value) {
                var str = '';
                if (key == selectedHour) {
                    $.each(value, function (Minutekey, Minutevalue) {
                        str += '<option value=' + Minutevalue + '>' + Minutevalue + '</option>';
                    });
                    //console.log(str);
                    el.parent().next().find('select').html(str);
                    //$("#StorePickupmin").html(str);
                }
            });
        });
    });
</script>
