<center>
<h1>GENERATING PAYMENT</h1>
Please do not refresh or close this page
</center>

<div {if $PAY24_MODULE_DEBUG neq 1}style="display:none;"{else}style="display:inline-block;background-color: #acacac;padding: 15px;"{/if}>
    <form method="post" action="{$PAY24_ACTION}" id="veintequatro-form">
        <label>Mid</label><br/>
        <input type="text" name="Mid" value="{$PAY24_MID}" /><br/>
        <label>EshopId</label><br/>
        <input type="text" name="EshopId" value="{$PAY24_ESHOPID}" /><br/>
        <label>MsTxnId</label><br/>
        <input type="text" name="MsTxnId" value="{$PAY24_MSTXNID}" /><br/>
        <label>Amount</label><br/>
        <input type="text" name="Amount" value="{$PAY24_AMOUNT}" /><br/>
        <label>CurrAlphaCode</label><br/>
        <input type="text" name="CurrAlphaCode" value="{$PAY24_CURRALPHACODE}" /><br/>
        <label>Country</label><br/>
        <input type="text" name="LangCode" value="{$PAY24_LANGUAGE}" /><br/>
        <label>ClientId</label><br/>
        <input type="text" name="ClientId" value="{$PAY24_CLIENTID}" /><br/>
        <label>FirstName</label><br/>
        <input type="text" name="FirstName" value="{$PAY24_FIRSTNAME}" /><br/>
        <label>FamilyName</label><br/>
        <input type="text" name="FamilyName" value="{$PAY24_FAMILYNAME}" /><br/>
        <label>Email</label><br/>
        <input type="text" name="Email" value="{$PAY24_EMAIL}" /><br/>
        <label>Country</label><br/>
        <input type="text" name="Country" value="{$PAY24_COUNTRY}" /><br/>
        <label>NURL</label><br/>
        <input type="text" name="NURL" value="{$PAY24_NURL}" /><br/>
        <label>RURL</label><br/>
        <input type="text" name="RURL" value="{$PAY24_RURL}" /><br/>
        <label>Timestamp</label><br/>
        <input type="text" name="Timestamp" value="{$PAY24_TIMESTAMP}" /><br/>
        <label>Sign</label><br/>
        <input type="text" name="Sign" value="{$PAY24_SIGN}" /><br/>
        {if $PAY24_MODULE_DEBUG eq 1}
        <label>Debug</label><br/>
        <input type="text" name="Debug" value="true" /><br/>
        {/if}
        <br/>
        <input type="submit" value="Odoslať" />
    </form>
</div>

{if $PAY24_MODULE_DEBUG neq 1}
<script>
    document.getElementById("veintequatro-form").submit();
</script>
{/if}