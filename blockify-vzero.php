<?php

require("Braintree.php");

$block->open();

Braintree_Configuration::environment($block->document['environment']);
Braintree_Configuration::merchantId($block->document['merchantId']);
Braintree_Configuration::publicKey($block->document['publicKey']);
Braintree_Configuration::privateKey($block->document['privateKey']);

$token = Braintree_ClientToken::generate(array());

if($_POST['payment_method_nonce']){

    $result = Braintree_Transaction::sale(array(
      'amount' => $_POST['amount'],
      'paymentMethodNonce' => $_POST['payment_method_nonce']
    ));

    if($result->success==1){
        $block->document->tag('h1', 'success');
    }
    else{
        $block->document->tag('h1', 'error');
    }

}else{

?>
<form method="post">
    <div class="row">
        <div class="col-md-6">
        <h2 class="text-center">Payment Methods</h1>
            <div id="checkout"></div>
        </div>
        <div class="col-md-6">
        <h2 class="text-center">Review and Pay</h1>
            <div class="col-md-6">
                <div class="input-group">
                    <?php
                        if($block->document['amt']) $amt = $block->document['amt'];
                        elseif($block->document['environment']=="sandbox") $amt = $block->document['testsale'];
                        else $amt = 0;
                    ?>
                    <input type="text" class="form-control text-right" name="amount" placeholder="Amount" value="<?php echo $amt;?>" disabled>
                    <input type="hidden" name="amount" value="<?php echo number_format($amt, 2, '.', '')?>">
                    <span class="input-group-addon">.00</span>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
            <div class="col-md-12 dotblock">
                <?php $block->document->tag('p', 'desc'); ?>
            </div>
        </div>
    </div>
</form>
    <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
    <script>
        braintree.setup("<?php echo $token;?>", 'dropin', {
          container: 'checkout',

        });
    </script>

<?php

}



$block->close();
