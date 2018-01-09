<style>
    .ordr-bx-pos{ padding-right: 0px;}
    .modal-dialog .orderTypePickUp, .modal-dialog .orderTypeDelivery{width:70%;}
    .modal-dialog label {
        color: #737373;
        font-size: 16px;
        font-weight: 400;
    }
</style>


<?php //$pickUpDateTime = $this->Common->getCurrentPickUpStoreTime (); 

$PreorderAllowed=$this->Common->checkPreorder(); 
if($PreorderAllowed){
?>
<li class='orderTypeDelivery ordr-bx-pos'>
    <div style="float:left">
        <span class="title"><label>Delivery Date <em>*</em></label></span>
    
        <?php
        echo $this->Form->input('orderType.delivery_date', array('type' => 'text', 'class' => 'inbox date-select', 'placeholder' => 'Date', 'label' => false, 'div' => false, 'required' => true, 'readOnly' => true));
        echo $this->Form->error('orderType.delivery_date');
        ?>
    </div>
    
    <div id="resvTime">
        
    </div>
    
    
    
</li>    

<!--<li class='orderTypeDelivery'> 
    <span class="title"><label>Delivery Time</label></span>
    <div class="title-box"><span id="resvTime">
            <select id="PickUpPickupTimeNow" class="inbox" name="data[OrderType][delivery_time]">
                <?php
                if (!empty($finaldata['time_range'])) {
                    foreach ($finaldata['time_range'] as $key => $value) {
                        $flag = true;
                        $storeBreak = $finaldata['storeBreak'];
                        foreach ($storeBreak as $breakKey => $breakVlue) {
                            if (strtotime($storeBreak[$breakKey]['start']) <= strtotime($key) && strtotime($storeBreak[$breakKey]['end']) >= strtotime($key)) {
                                echo "<option value='$key' disabled='disabled'>$value - Break Time </option>";
                                $flag = false;
                            }
                        }
                        if ($flag) {
                            echo "<option value='$key'>$value</option>";
                        }
                    }
                } else {
                    echo "<option value=''>Store is closed for today</option>";
                }
                ?>
            </select> </span>                                  
            <?php echo $this->Form->error('orderType.delivery_time'); ?>
    </div>
</li>-->

<?php } ?>

<script>   
    
    function getTime(date, orderType, preOrder, returnspan, ortype){
        var type1 = 'Store';
        var type2 = 'pickup_time';
        var type3 = ortype;
        var storeId = '<?php echo $encrypted_storeId; ?>';
        var merchantId = '<?php echo $encrypted_merchantId; ?>';
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'getStoreTime')); ?>",
            type: "Post",
            dataType: 'html',
            data: {storeId: storeId, merchantId: merchantId, date: date, type1: type1, type2: type2, type3: type3, orderType: orderType, preOrder: preOrder},
            success: function (result) {
                $('#' + returnspan).html(result);
            }
        });
    }
    
    
    
    $('#PickUpPickupTimeNow').on('change', function () {
        var date = $(this).val();
        var orderType = 3; // 3= delivery
	var preOrder = 1;
        var type1 = 'Store';
        var type2 = 'pickup_time';
        var type3 = 'StorePickupTime';
        var storeId = '<?php echo $encrypted_storeId; ?>';
        var merchantId = '<?php echo $encrypted_merchantId; ?>';
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'getStoreTime')); ?>",
            type: "Post",
            dataType: 'html',
            data: {storeId: storeId, merchantId: merchantId, date: date, type1: type1, type2: type2, type3: type3,orderType:orderType,preOrder:preOrder},
            success: function (result) {
                $('#resvTime').html(result);
            }
        });
    });
    
</script>

<script>

//Delivery Date Scripts       
    $('#orderTypeDeliveryDate').datepicker({
        dateFormat: 'mm-dd-yy',
        minDate: '<?php echo $finaldata['delcurrentDateVar']; ?>',
        maxDate: '<?php echo $finaldata['deliverymaxdate']; ?>',
        beforeShowDay: function (date) {
            var day = date.getDay();
            var array ='<?php echo json_encode($closedDay); ?>';
            return [array.indexOf(day) == -1];
        }
    });
    $("#orderTypeDeliveryDate").datepicker("setDate", '<?php echo $finaldata['delcurrentDateVar']; ?>');
    var date = '<?php echo $finaldata['delcurrentDateVar']; ?>';
    getTime(date, 3, 1, 'resvTime', 'orderTypeDeliveryDate');


    $('#orderTypeDeliveryDate').on('change', function () {
        var date = $(this).val();
        var orderType = 3; // 3= Take-away/pick-up
        var preOrder = 1;
        getTime(date, orderType, preOrder, 'resvTime', 'orderTypeDeliveryDate');
    });
//Delivery Date Scripts

</script>