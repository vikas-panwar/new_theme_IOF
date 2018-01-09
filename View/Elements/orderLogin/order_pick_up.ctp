<style>
    .ordr-bx-pos{ padding-right: 0px;}    
    .modal-dialog .orderTypePickUp, .modal-dialog .orderTypeDelivery{width:70%;}
    .modal-dialog label {
        color: #737373;
        font-size: 16px;
        font-weight: 400;
    }
</style>

<?php //$pickUpDateTime = $this->Common->getCurrentPickUpStoreTime();
//pr($pickUpDateTime); exit; 
$PreorderAllowed=$this->Common->checkPreorder(); 
if($PreorderAllowed){

?>
<li class='orderTypePickUp ordr-bx-pos'>

    <div style="float:left">
    <span class="title"><label>Pick Up Date <em>*</em></label></span>    
        <?php
        echo $this->Form->input('orderType.pick_up_date', array('type' => 'text', 'class' => 'inbox date-select', 'placeholder' => 'Date', 'label' => false, 'div' => false, 'required' => true, 'readOnly' => true));
        echo $this->Form->error('orderType.pick_up_date');
        ?>
    </div>
    
    <div id="resvTime">
        
    </div>
    
</li>    

<!--<li class='orderTypePickUp'>
    <span class="title"><label>Pick Up Time</label></span>
    <div class="title-box"><span id="resvTime">
            <select id="PickUpPickupTimeNow" class="inbox" name="data[OrderType][pick_up_time]">
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
            </select>                                   
            <?php echo $this->Form->error('orderType.pick_up_time'); ?>
        </span></div>
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
    
    
        $('#orderTypePickUpDate').on('change', function () {
        var date = $(this).val();
        var orderType = 2; // 3= Take-away/pick-up
	var preOrder = '1';
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

//Pickup Date Scripts  -start   
    $('#orderTypePickUpDate').datepicker({
        dateFormat: 'mm-dd-yy',
        minDate: '<?php echo $finaldata['pickcurrentDateVar']; ?>',
        maxDate: '<?php echo $finaldata['pickupmaxdate']; ?>',
        beforeShowDay: function (date) {
            var day = date.getDay();
            var array ='<?php echo json_encode($closedDay); ?>';
            return [array.indexOf(day) == -1];
        }
    });
    $("#orderTypePickUpDate").datepicker("setDate", '<?php echo $finaldata['pickcurrentDateVar']; ?>');
    var date = '<?php echo $finaldata['pickcurrentDateVar']; ?>';
    getTime(date, 2, 1, 'resvTime', 'orderTypePickUpDate', true);


    $('#orderTypePickUpDate').on('change', function () {
        var date = $(this).val();
        var orderType = 2; // 3= Take-away/pick-up
        var preOrder = 1;
        getTime(date, orderType, preOrder, 'resvTime', 'orderTypePickUpDate');
    });
//Pickup Date Scripts  end

</script>