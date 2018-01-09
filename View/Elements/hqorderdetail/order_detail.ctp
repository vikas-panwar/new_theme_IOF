
<table class="table tablesorter">
            <thead>
            <tr>
                <th class="th_checkbox" colspan="5" style="text-align:left;">
                    Order Id : <?php  echo $orderDetail[0]['Order']['order_number'] ;?>
                    | Cost : $<?php echo $total_amount?>
                    <?php if($orderDetail[0]['Order']['tax_price']){ echo "| Tax :$".$orderDetail[0]['Order']['tax_price'];} ?>
                    | Status : <?php echo $data = $this->requestAction('/hqorders/ajaxRequest/'.$orderDetail[0]['Order']['order_status_id'].'')?>
                    </br> Name : <?php echo $orderAttr['enduser_name'];?>
                    </br> Contact # <?php echo $orderAttr['enduser_phone'];?>
                    <?php echo $orderAttr['address'];?>
                    <br/>
                    Order Type : <?php  echo $orderAttr['orderType'].'-'.$orderAttr['paymentStatus']; ?>
                    &nbsp;| Created :&nbsp;&nbsp;<?php  echo $orderAttr['created_time']; ?>
                    <?php echo $orderAttr['pickup_time']?>
                    
                </th>
            </tr>
            </thead>
        </table>

        <table class="table table-bordered table-hover table-striped tablesorter table-net-record-modify">


            <thead>
            <tr>
                <th class="th_checkbox">Item</th>
                <th class="th_checkbox">Size</th>
                <th class="th_checkbox" colspan="2">Preference</th>
                <th class="th_checkbox">Add-ons</th>
                <th class="th_checkbox">Price($)</th>
<!--                <th class="th_checkbox">Tax($)</th>-->

            </tr>
            </thead>

            <tbody class="dyntable">
            <?php
		         $i = 0;
			 $totalItemPrice=0.00;
			foreach($orderDetail[0]['OrderItem'] as $key => $item){

            $class = ($i%2 == 0) ? ' class="active"' : '';

            ?>
            <tr>
                <td>
			<span style="line-height: 30px;">
		<?php 
                
                $Interval="";
                if(isset($item['interval_id'])){
                    $intervalId=$item['interval_id'];
                    $Interval=$this->Common->getIntervalName($intervalId);                    
                }
                echo $item['quantity'];?> x <?php echo $item['Item']['category']['name']."-".$item['Item']['name'];
                echo ($Interval)?"(".$Interval.")":"";
               
                ?>
                <?php
				if(isset($item['OrderOffer'])){
				    echo "<br>";
				    foreach ($item['OrderOffer'] as $j => $offer){
				       $offeroitem="&nbsp;&nbsp;";
				       if(isset($offer['quantity'])){
					  $offeroitem.=$offer['quantity']; 
				       }
				       if(isset($offer['Size']['size'])){
					  $offeroitem.="x ".$offer['Size']['size'];
				       }
				       if($offer['Item']['name']){
					  $offeroitem.=" ".$offer['Item']['name']."<br>";
				       }
				       
				     echo $offeroitem;
				    }
				}
				 ?>
				 </span>
                </td>

                <td>
                    <?php echo ($item['Size'])?$item['Size']['size']:"-";?>
                </td>
                <td colspan="2">
                    <?php
			
			if(!empty($item['OrderPreference'])) {
			    //$preference="";
			    //$prefix = '';
			    foreach($item['OrderPreference'] as $key =>$opre){
                    //$preference .= $prefix . '' .$opre['SubPreference']['name']."";
                    echo $opre['SubPreference']['name']."<br>";
                    //$prefix = ', ';
                    }
                    //echo $preference;

                    } else { echo ' - '; }
                    //echo ($item['Type'])?$item['Type']['name']:"-";


                    ?>
                </td>


                <td style="width: 300px; word-wrap: break-word; word-break: break-all;">
                    <?php
                    $Toppings='';
                    if($item['OrderTopping']){
                        $Toppings=array();
                        foreach($item['OrderTopping'] as $vkey => $toppingdetails){
                            if(isset($toppingdetails['Topping']['name'])){
                                $addonsize=1;
                                $addOnSizedetails=$this->Common->getaddonSize($toppingdetails['addon_size_id']);
                                if($addOnSizedetails){
                                    $addonsize=$addOnSizedetails['AddonSize']['size'];
                                }
                                //$Toppings[]=$addonsize.' '.$toppingdetails['Topping']['name'];
                                echo $addonsize.' '.$toppingdetails['Topping']['name']."<br>";
                            }
                        }
                    }
//                    if($Toppings){
//                    $alltoppings=implode(',',$Toppings);
//                    echo $alltoppings;//wordwrap($alltoppings,5,"<br>\n");
//                    }else{
//                    echo "-";
//                    }

                    ?>
                </td>
                <td>
                    <?php echo ($item['total_item_price'])?$item['total_item_price']:"-";
			if($item['total_item_price']){$totalItemPrice=$totalItemPrice+$item['total_item_price'];}
			
			?>
                </td>
<!--                <td>
                    <?php echo ($item['tax_price'])?(number_format($item['quantity']*$item['tax_price'],2)):"-";?>
                </td>-->

            </tr>
            <?php $i++; } ?>


            <tr class="table-net-tr">
                
                <td class="table-net-td" colspan="4">
                <?php
                $orderComment='';
                if (!empty($orderDetail[0]['Order']['order_comments'])) {
                    $orderComment= $orderDetail[0]['Order']['order_comments'];
                }
                 echo "<b>Special comment:</b>" . $orderComment;
                ?>   
                </td>
<!--            <td class="table-net-td">&nbsp;</td>
            <td class="table-net-td">&nbsp;</td>
            <td class="table-net-td">&nbsp;</td>-->
                
<!--                <td class="net-record-font-size"><?php echo number_format($totalItemPrice,2);?></td>
                <td class="net-record-font-size"><?php echo number_format($orderDetail[0]['Order']['tax_price'],2);?></td>-->
                
            </tr>
            <tr class="table-net-tr">
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="net-record-font-size">Sub-Total:</td>
                <td class="net-record-font-size"><?php echo number_format($totalItemPrice,2);?></td>
            </tr>
            <tr class="table-net-tr">
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="net-record-font-size" style="width: 15%">Tax:</td>
            <td class="net-record-font-size" style="width: 15%"><?php echo number_format($orderDetail[0]['Order']['tax_price'], 2); ?></td>
            </tr>
            
            <?php
             if(!empty($orderDetail[0]['OrderItemFree'])){
                 foreach($orderDetail[0]['OrderItemFree'] as $fkey => $itemfree){
            ?>     
                <tr>
                    <td><?php echo $itemfree['free_quantity'].' '. $itemfree['Item']['name'];?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="net-record-font-size">Discount:</td>
                    <td class="net-record-font-size" style="color:#ff1a1a;">
                        <?php echo "-".number_format($itemfree['free_quantity']*$itemfree['price'],2);?>
                        
                    </td>
                </tr>
            <?php
                 }
             } 
            ?>
            
            
            
            <tr class="table-net-tr">
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="net-record-font-size">Coupon Discount:<br><?php echo ($orderDetail[0]['Order']['coupon_code'])?"(".$orderDetail[0]['Order']['coupon_code'].")":'';?></td>
                <td class="net-record-font-size"
                    style="color:#ff1a1a;"><?php echo ($orderDetail[0]['Order']['coupon_discount'])?"-".number_format($orderDetail[0]['Order']['coupon_discount'],2):'-';?></td>
            </tr>

            <tr class="table-net-tr">
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="net-record-font-size">Service Fee:</td>
                <td class="net-record-font-size"><?php echo ($orderDetail[0]['Order']['service_amount'])?number_format($orderDetail[0]['Order']['service_amount'],2):'-';?></td>
            </tr>
            <?php if($orderDetail[0]['Segment']['id'] != 2){ ?>
            <tr class="table-net-tr">
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="net-record-font-size">Delivery Fee:</td>
                <td class="net-record-font-size"><?php echo ($orderDetail[0]['Order']['delivery_amount'])?number_format($orderDetail[0]['Order']['delivery_amount'],2):'-';?></td>
            </tr>
            <?php } ?>
            
            
            <tr class="table-net-tr">
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="net-record-font-size">Tip:</td>
                <td class="net-record-font-size"><?php echo ($orderDetail[0]['Order']['tip'])?number_format($orderDetail[0]['Order']['tip'],2):'-';?></td>
            </tr>
            
            <tr class="table-net-tr">
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="table-net-td">&nbsp;</td>
                <td class="net-record-font-size">Total:</td>
                <td class="net-record-font-size"><?php echo number_format($total_amount,2);?></td>
            </tr>

            </tbody>
        </table>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
