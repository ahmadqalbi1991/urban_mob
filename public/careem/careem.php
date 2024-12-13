<html>
  <head>
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <body>
    <cpay-checkout-button id="checkoutBtn"></cpay-checkout-button>
    <!--
        You can customize the button like this:
        <style>
            cpay-checkout-button {
                --cpay-checkout-button-height: 40px;
                --cpay-checkout-button-width: 200px;
                --cpay-checkout-button-border-radius: 10px;
            }
        </style>
        <cpay-checkout-button id="checkoutBtn" branding="white-solid">
        </cpay-checkout-button>
    -->
    <script
      id="careemPayScript"
      src="https://one-click-js.careem-pay.com/v2/index.es.js"
      type="module"
    ></script>
    <script>
      const doValidation = () => {
        // perform your validation here
        return true;
      };

      const button = document.getElementById("checkoutBtn");
      document
        .getElementById("careemPayScript")
        .addEventListener("load", () => {
          const careemPay = CareemPay("f10586f5-cef9-4730-a4ef-8fda63d92a2a", {
            // use your client_id
            env: "sandbox" /* or "production" */,
          });

          careemPay.attach(button);
        });

      button.addEventListener("checkout", async (paymentAttempt) => {
        if (!doValidation()) {
          // cancel the payment attempt if validation doesn't pass
          paymentAttempt.cancel();
          return;
        }

        const { id: invoiceId } = await fetch(
          // call to your backend that generates the invoice
          "https://www.urbanmop.com/careem-checkout-json",
          {
            method: "post",
          },
        )
          .then((res) => res.json())
          // mark payment attempt as failure if invoice cannot be generated
          .catch(() => paymentAttempt.fail());

        // pass the invoice ID to the begin method
        try {
          const result = await paymentAttempt.begin(invoiceId);
          // IMPORTANT: Do NOT perform any critical operation (e.g., order confirmation or customer email) here.
          // Critical operations should be triggered by the web hook. Refer to the web hook section for more info.

          if (result.status === "success") {
            // display success screen.
          } else if (result.status === "failure") {
            // display failure screen
          }
        } catch (error) {
          // if the user closes the modal without making a purchase, or if there's an error, the payment attempt will throw.
        }
      });
    </script>
  </body>
</html>