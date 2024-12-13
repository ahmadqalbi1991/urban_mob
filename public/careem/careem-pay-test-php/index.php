<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <cpay-checkout-button id="checkoutBtn"></cpay-checkout-button>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const doValidation = () => {
                // Perform your validation here
                return true;
            };

            const button = document.getElementById("checkoutBtn");
            const careemPayScript = document.createElement("script");

            careemPayScript.src = "https://one-click-js.careem-pay.com/v2/index.es.js";
            careemPayScript.type = "module";

            careemPayScript.addEventListener("load", () => {
                const careemPay = CareemPay("f10586f5-cef9-4730-a4ef-8fda63d92a2a", {
                    env: "sandbox" // Change to "production" for live environment
                });

                careemPay.attach(button);

                    button.addEventListener("checkout", async (paymentAttempt) => {
                    if (!doValidation()) {
                    // Cancel the payment attempt if validation doesn't pass
                    paymentAttempt.cancel();
                    return;
                    }

                    try {
                    const response = await fetch("/careem/careem-pay-test-php/careem-checkout-json.json", {
                    method: "GET"
                    });

                    if (!response.ok) {
                    // Mark payment attempt as failure if invoice cannot be generated
                    paymentAttempt.fail();
                    return;
                    }

                    const data = await response.json();

                    // Log the retrieved data
                    console.log("Data from JSON:", data);

                    const result = await paymentAttempt.begin(data.invoiceId);

                    if (result.status === "success") {
                    // Handle a successful payment
                    console.log("Payment successful");
                    } else if (result.status === "failure") {
                    // Handle a failed payment
                    console.error("Payment failed");
                    }
                    } catch (error) {
                    // Handle errors
                    console.error("Error during checkout:", error);
                    }
                    });

            });

            document.head.appendChild(careemPayScript);
        });
    </script>
</body>
</html>
