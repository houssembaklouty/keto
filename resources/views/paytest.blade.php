<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
    <h3>My Book Store</h3>
    <div>
        <img src="/sample-pdf-book.png" width="200"/>
        <p><em>A Dummy Book Title</em></p>
        <p> <strong>Buy Now :</strong> $ 0.50</p>
    </div>
    <div style="width:200px" id="paypal-button-container"></div>

    <script
    src="https://www.paypal.com/sdk/js?client-id=AdttvZgAehmurmZt53u_VZusAzNjiNY6qh3-F93Cxlhy_20Tvn8rNNHPXl4KVxOtPA5wXabv002wUKV_">
    </script>

    <script>
    paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
    
    var amountValue = 1;

      return actions.order.create({
        application_context: {
          brand_name : 'Laravel Book Store Demo Paypal App',
          user_action : 'PAY_NOW',
        },
        purchase_units: [{
          amount: {
            value: amountValue
          }
        }],
      });
    },

    onApprove: function(data, actions) {

      let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // This function captures the funds from the transaction.
      return actions.order.capture().then(function(details) {
          if(details.status == 'COMPLETED'){
            return fetch('/api/paypal-capture-payment', {
                      method: 'post',
                      headers: {
                          'content-type': 'application/json',
                          "Accept": "application/json, text-plain, */*",
                          "X-Requested-With": "XMLHttpRequest",
                          "X-CSRF-TOKEN": token
                      },
                      body: JSON.stringify({
                          orderId     : data.orderID,
                          id : details.id,
                          status: details.status,
                          payerEmail: details.payer.email_address,
                      })
                  })
                  .then(status)
                  .then(function(response){
                      // redirect to the completed page if paid
                      window.location.href = '/pay-success';
                  })
                  .catch(function(error) {
                      // redirect to failed page if internal error occurs
                      window.location.href = '/pay-failed?reason=internalFailure';
                  });
          }else{
              window.location.href = '/pay-failed?reason=failedToCapture';
          }
      });
    },

    onCancel: function (data) {
        window.location.href = '/pay-failed?reason=userCancelled';
    }



    }).render('#paypal-button-container');
    // This function displays Smart Payment Buttons on your web page.

    function status(res) {
      if (!res.ok) {
          throw new Error(res.statusText);
      }
      return res;
    } 
  </script>

</body>
</html>