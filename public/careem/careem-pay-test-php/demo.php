<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <cpay-checkout-button id="checkoutBtn"></cpay-checkout-button>

    <h2>Generate Invoice and Checkout</h2>
    <form id="invoiceForm">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" step="0.01" required>
        <button type="submit">Generate Invoice & Checkout</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const button = document.getElementById("checkoutBtn");
            const careemPayScript = document.createElement("script");

            careemPayScript.src = "https://one-click-js.careem-pay.com/v2/index.es.js";
            careemPayScript.type = "module";

            careemPayScript.addEventListener("load", () => {
                const careemPay = CareemPay("f10586f5-cef9-4730-a4ef-8fda63d92a2a", {
                    env: "sandbox" // Change to "production" for the live environment
                });

                careemPay.attach(button);

                button.addEventListener("checkout", async (paymentAttempt) => {
                    try {
                        const invoiceAmount = document.getElementById("amount").value;
                        const invoiceResponse = await generateInvoice(invoiceAmount);

                        if (!invoiceResponse.success) {
                            // Mark payment attempt as failure if invoice cannot be generated
                            paymentAttempt.fail();
                            return;
                        }

                        const invoiceId = invoiceResponse.invoiceId;

                        // Log the retrieved invoice data
                        console.log("Generated Invoice ID:", invoiceId);

                        const result = await paymentAttempt.begin(invoiceId);

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

                // Function to generate an invoice
                async function generateInvoice(amount) {
                    try {
                        const response = await fetch("/your/invoice/generation/endpoint", {
                            method: "POST", // Assuming you have a server-side endpoint for generating invoices
                            body: JSON.stringify({
                                amount: amount
                            }),
                            headers: {
                                "Content-Type": "application/json"
                            }
                        });

                        if (!response.ok) {
                            return { success: false };
                        }

                        const data = await response.json();
                        return { success: true, invoiceId: data.invoiceId };
                    } catch (error) {
                        console.error("Error during invoice generation:", error);
                        return { success: false };
                    }
                }

                // Prevent form submission
                const invoiceForm = document.getElementById("invoiceForm");
                invoiceForm.addEventListener("submit", (e) => {
                    e.preventDefault();
                });
            });

            document.head.appendChild(careemPayScript);
        });
    </script>
</body>
</html>
