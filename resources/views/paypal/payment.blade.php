<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="frmTransaction" id="frmTransaction">
    <input type="hidden" name="business" value="FK4DN9VXX8LCC"> 
    <input type="hidden" name="cmd" value="_xclick"> 
    <input type="hidden" name="item_name" value="iPhone"> 
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" name="amount" value="100">   
    <input type="hidden" name="currency_code" value="USD">   
    <input type="hidden" name="cancel_return" value="https://vinecork.com/web/urbanmop/public/payment-cancel"> 
    <input type="hidden" name="return" value="https://vinecork.com/web/urbanmop/public/payment-status">
</form>
<SCRIPT>document.frmTransaction.submit();</SCRIPT> 