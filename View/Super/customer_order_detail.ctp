<!--******************Order details area start here***********************-->
<div class="row">
    <div class="col-lg-12">
        <a class="btn btn-default" href="<?php echo @$this->Session->read("ref"); ?>">Back</a>
    </div>
</div>
<div role="tabpanel" class="tab-pane" id="order">
    <?php if (!empty($orderDetail[0]['Order'])) { ?>
        <?php
        foreach ($orderDetail as $k => $data) {
            $total_amount = number_format(($data['Order']['amount'] - $data['Order']['coupon_discount']), 2);
            $arr[] = '';
            if ($data['Order']['seqment_id'] == 2) {
                $arr = array_diff($statusList, array('Ready For Delivery', 'On the way', 'Delivered', 'Confirmed'));
            }
            if ($data['Order']['seqment_id'] == 3) {
                $arr = array_diff($statusList, array('Ready for Pick up', 'On the way', 'Confirmed', 'Picked Up'));
            }
            if ($data['Order']['seqment_id'] == 1) {
                $arr = $statusList;
            }

            $devlInfo = $data['DeliveryAddress'];
            $orderInfo = $data['Order'];
            $userInfo = $data['User'];
            if ($orderInfo['user_id'] == 0) {
                $enduser_name = $devlInfo['name_on_bell'];
                $enduser_phone = $devlInfo['phone'];
            } else {
                $enduser_name = $userInfo['fname'] . ' ' . $userInfo['lname'];
                $enduser_phone = $userInfo['phone'];
            }
            $address = $devlInfo['address'] . ' ' . $devlInfo['city'];
            $pickup_time = '';
            $pickup_time = $this->Hq->storeTimeFormate($orderInfo['pickup_time'], true, $data['Order']['store_id']);
            if ($orderInfo['seqment_id'] == 2) {
                $address = "Pickup";
                $address = "</br>Address : " . $address;
                $pickup_time = "| Pickup Time:&nbsp;&nbsp;" . $pickup_time;
                $orderType = "Pickup";
            } else {
                $pickup_time = "| Delivery Time:&nbsp;&nbsp;" . $pickup_time;
                $address = "</br>Address : " . $address;
                $orderType = "Delivery";
            }
            if ($data['OrderPayment']['payment_gateway'] == 'COD') {
                if ($data['Order']['seqment_id'] == 3) {
                    $paymentStatus = "UNPAID";
                } else {
                    $paymentStatus = "UNPAID";
                }
            } else {
                $paymentStatus = "PAID";
            }
            $created_time = $this->Hq->storeTimeFormate($this->Hq->storeTimezone(null, $orderInfo['created'], null, $data['Order']['store_id']), true, $data['Order']['store_id']);
            ?>
            <div>
                <br><br>
                <table class="table table-bordered table-hover table-striped tablesorter ">
                    <thead>
                        <tr>
                            <th class="th_checkbox" colspan="5" style="text-align:left;">
                                Order Id : <?php echo $data['Order']['order_number']; ?>
                                | Cost : $<?php echo $total_amount ?>
                                <?php
                                if ($orderDetail[0]['Order']['tax_price']) {
                                    echo "| Tax :$" . $data['Order']['tax_price'];
                                }
                                ?>
                                | Status : <?php echo $this->requestAction('/super/ajaxRequest/' . $data['Order']['order_status_id'] . '') ?>
                                </br> Name : <?php echo $enduser_name; ?>
                                </br> Contact # <?php echo $enduser_phone; ?>
                                <?php echo $address; ?>
                                <br/>
                                Order Type : <?php echo $orderType . '-' . $paymentStatus; ?>
                                &nbsp;| Created :&nbsp;&nbsp;<?php echo $created_time; ?>
                                <?php echo $pickup_time; ?>
                            </th>
                        </tr>
                    </thead>
                </table>
                <table class="table table-bordered table-hover table-striped tablesorter table-net-record-modify">
                    <thead>
                        <tr>	    
                            <th  class="th_checkbox">Item</th>
                            <th  class="th_checkbox">Size</th>
                            <th  class="th_checkbox" colspan="2">Preference</th>
                            <th  class="th_checkbox">Add-ons</th>
                            <th  class="th_checkbox">Price($)</th>
        <!--                            <th  class="th_checkbox">Tax($)</th>-->
                        </tr>
                    </thead>
                    <tbody class="dyntable">
                        <?php
                        $i = 0;
                        $totalItemPrice = 0.00;
                        foreach ($data['OrderItem'] as $key => $item) {
                            $class = ($i % 2 == 0) ? ' class="active"' : '';
                            ?>
                            <tr >	    
                                <td>
                                    <span style="line-height: 30px;">
                                        <?php
                                        $Interval = "";
                                        if (isset($item['interval_id'])) {
                                            $intervalId = $item['interval_id'];
                                            $Interval = $this->Hq->getIntervalName($intervalId);
                                        }

                                        echo $item['quantity'];
                                        ?> x <?php
                                        echo $item['Item']['name'];
                                        echo ($Interval) ? "(" . $Interval . ")" : "";
                                        ?>
                                        <?php
                                        if (isset($item['OrderOffer'])) {
                                            echo "<br>";
                                            foreach ($item['OrderOffer'] as $j => $offer) {
                                                $offeroitem = "&nbsp;&nbsp;";
                                                if (isset($offer['quantity'])) {
                                                    $offeroitem.=$offer['quantity'];
                                                }
                                                if (isset($offer['Size']['size'])) {
                                                    $offeroitem.=" " . $offer['Size']['size'];
                                                }
                                                if ($offer['Item']['name']) {
                                                    $offeroitem.="x " . $offer['Item']['name'] . "<br>";
                                                }

                                                echo $offeroitem;
                                            }
                                        }
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <?php echo ($item['Size']) ? $item['Size']['size'] : "-"; ?>
                                </td>
                                <td colspan="2">
                                    <?php
                                    if (!empty($item['OrderPreference'])) {
                                        foreach ($item['OrderPreference'] as $key => $opre) {
                                            echo $opre['SubPreference']['name'] . "<br>";
                                        }
                                    } else {
                                        echo ' - ';
                                    }
                                    ?>	
                                </td>
                                <td style="width: 300px; word-wrap: break-word; word-break: break-all;">
                                    <?php
                                    $Toppings = '';
                                    if ($item['OrderTopping']) {
                                        $Toppings = array();
                                        foreach ($item['OrderTopping'] as $vkey => $toppingdetails) {
                                            if (isset($toppingdetails['Topping']['name'])) {
//                                                $addonsize = 1;
//                                                $addOnSizedetails = $this->Hq->getaddonSize($toppingdetails['addon_size_id'], $data['Order']['store_id']);
//                                                if ($addOnSizedetails) {
//                                                    $addonsize = $addOnSizedetails['AddonSize']['size'];
//                                                }
//
//                                                echo $addonsize . ' ' . $toppingdetails['Topping']['name'] . "<br>";
                                                $addonsize = ($toppingdetails['addon_size_id'] > 1) ? $toppingdetails['addon_size_id'] : '';
                                                echo $addonsize . ' ' . $toppingdetails['Topping']['name'] . "<br>";
                                            }
                                        }
                                    }
                                    ?>	
                                </td>
                                <td>
                                    <?php
                                    echo ($item['total_item_price']) ? $item['total_item_price'] : "-";
                                    if ($item['total_item_price']) {
                                        $totalItemPrice = $totalItemPrice + $item['total_item_price'];
                                    }
                                    ?>
                                </td>
            <!--                                <td>
                                <?php //echo ($item['tax_price']) ? (number_format($item['quantity'] * $item['tax_price'], 2)) : "-"; ?>
                                </td>-->

                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                        <tr>
                            <td colspan="4" rowspan="7" style="vertical-align: top !important;"><b>Special Comment: </b><?php
                                $orderComment = '';
                                if (!empty($orderDetail['Order']['order_comments'])) {
                                    $orderComment = $orderDetail['Order']['order_comments'];
                                }
                                echo $orderComment;
                                ?></td>
                            <td class="net-record-font-size">Sub-Total:</td>
                            <td class="net-record-font-size"><?php echo number_format($totalItemPrice, 2); ?></td>
                        </tr>
                        <tr>
                            <td class="net-record-font-size">Tax:</td>
                            <td class="net-record-font-size"><?php echo number_format($data['Order']['tax_price'], 2); ?></td>

                        </tr>
                        <tr>
                            <td class="net-record-font-size">Coupon Discount:</td>
                            <td class="net-record-font-size"><?php echo '-' . ($data['Order']['coupon_discount']) ? number_format($data['Order']['coupon_discount'], 2) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="net-record-font-size">Tip:<br></td>
                            <td class="net-record-font-size"><?php echo ($orderDetail[0]['Order']['tip']) ? number_format($orderDetail[0]['Order']['tip'], 2) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="net-record-font-size">Service Fee:</td>
                            <td class="net-record-font-size"><?php echo ($data['Order']['service_amount']) ? number_format($data['Order']['service_amount'], 2) : '-'; ?></td>
                        </tr>
                        <?php if ($data['Order']['seqment_id'] != 2) { ?>
                            <tr>
                                <td class="net-record-font-size">Delivery Fee:</td>
                                <td class="net-record-font-size"><?php echo ($data['Order']['delivery_amount']) ? number_format($data['Order']['delivery_amount'], 2) : '-'; ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="net-record-font-size">Total:</td>
                            <td class="net-record-font-size"><?php echo number_format($total_amount, 2); ?></td>
                        </tr>
                    </tbody>
                </table>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <br>
            <?php
        }
        echo $this->Form->input('total', array('type' => 'hidden', 'value' => $total_amount, 'id' => 'amt'));
    } else {
        echo "Record Not Found.";
    }
    ?>
</div>
<!--******************Order details area end here***********************-->