{extends file='page.tpl'}
{block name="page_content"}
<h1>{l s='Payment result' mod='pg24pay'}</h1>

{if $PAY24_TEMP.Result eq 'OK'}
    
    <h2>{l s='Order was successfully created' mod='pg24pay'}</h2>
    <p>{l s='Thank you. Your payment was successful.' mod='pg24pay'}</p>
    
    <table class='table'>
        <tr>
            <th>{l s='Payment method:' mod='pg24pay'}</th><td>24-pay</td> 
        </tr>
        {if $PAY24_ORDER neq null}
        <tr>
            <th>{l s='Order number:' mod='pg24pay'}</th>
            <td>{$PAY24_ORDER}</td>
        </tr>
        {/if}
        
        {if $PAY24_ORDER_REF neq null}
        <tr>
            <th>{l s='Order reference:' mod='pg24pay'}</th>
            <td>{$PAY24_ORDER_REF}</td>
        </tr>
        {/if}
        <tr>
            <th>{l s='Payment number:' mod='pg24pay'}</th>
            <td>{$PAY24_TEMP.MsTxnId}</td>
        </tr>

        <tr>
            <th>{l s='Amount:' mod='pg24pay'}</th> 
            <td>{$PAY24_TEMP.Amount} {$PAY24_TEMP.CurrCode}</td>
        </tr>

        <tr>
            <th>{l s='Payment status:' mod='pg24pay'}</th> 
            <td>{l s='Paid' mod='pg24pay'}</td>
        </tr>
    </table>
{else if $PAY24_TEMP.Result eq 'FAIL'}
    {if $PAY24_REPAY eq 1}
    <h2>{l s='Your payment was rejected.' mod='pg24pay'}</h2>
    <table class='table'>
        <tr>
            <th>{l s='Payment method:' mod='pg24pay'}</th><td>24-pay</td> 
        </tr>
        {if $PAY24_ORDER neq null}
        <tr>
            <th>{l s='Order number:' mod='pg24pay'}</th>
            <td>{$PAY24_ORDER}</td>
        </tr>
        {/if}
        {if $PAY24_ORDER_REF neq null}
        <tr>
            <th>{l s='Order reference:' mod='pg24pay'}</th>
            <td>{$PAY24_ORDER_REF}</td>
        </tr>
        {/if}
        <tr>
            <th>{l s='Payment number:' mod='pg24pay'}</th>
            <td>{$PAY24_TEMP.MsTxnId}</td>
        </tr>

        <tr>
            <th>{l s='Amount:' mod='pg24pay'}</th> 
            <td>{$PAY24_TEMP.Amount} {$PAY24_TEMP.CurrCode}</td>
        </tr>

        <tr>
            <th>{l s='Payment status:' mod='pg24pay'}</th> 
            <td>{l s='Unpaid' mod='pg24pay'}</td>
        </tr>
    </table>
        {if $PAY24_ORDER neq null}
            <a class="btn btn-info btn-block" href="{$link->getModuleLink('pg24pay', 'payment', ['content_only'=>'1','from_order'=>$PAY24_ORDER], true)|escape:'html'}" title="{l s='Pay again' mod='pg24pay'}">{l s='Pay again' mod='pg24pay'}</a>
        {/if}
    {else}
        
    <h2>{l s='Your payment was rejected.' mod='pg24pay'}</h2>
    <table class='table'>
        <tr>
            <th>{l s='Payment method:' mod='pg24pay'}</th><td>24-pay</td> 
        </tr>
        {if $PAY24_ORDER neq null}
        <tr>
            <th>{l s='Order number:' mod='pg24pay'}</th>
            <td>{$PAY24_ORDER}</td>
        </tr>
        {/if}
        <tr>
            <th>{l s='Payment number:' mod='pg24pay'}</th>
            <td>{$PAY24_TEMP.MsTxnId}</td>
        </tr>

        <tr>
            <th>{l s='Amount:' mod='pg24pay'}</th> 
            <td>{$PAY24_TEMP.Amount} {$PAY24_TEMP.CurrCode}</td>
        </tr>

        <tr>
            <th>{l s='Payment status:' mod='pg24pay'}</th> 
            <td>{l s='Unpaid' mod='pg24pay'}</td>
        </tr>
    </table>
        
        <a class="btn btn-info btn-block" href="{$link->getModuleLink('pg24pay', 'payment', ['content_only'=>'1'], true)|escape:'html'}" title="{l s='Pay again' mod='pg24pay'}">{l s='Pay again' mod='pg24pay'}</a>
    {/if}
    
{else if $PAY24_TEMP.Result eq 'PENDING'}
    <h2>{l s='Order was successfully created' mod='pg24pay'}</h2>
    <p>{l s='Thank you. Your payment was not processed at this moment, it is now in pending state. We will inform you about payment result later' mod='pg24pay'}</p>
    
    <table class='table'>
        <tr>
            <th>{l s='Payment method:' mod='pg24pay'}</th><td>24-pay</td> 
        </tr>
        {if $PAY24_ORDER neq null}
        <tr>
            <th>{l s='Order number:' mod='pg24pay'}</th>
            <td>{$PAY24_ORDER}</td>
        </tr>
        {/if}
        {if $PAY24_ORDER_REF neq null}
        <tr>
            <th>{l s='Order reference:' mod='pg24pay'}</th>
            <td>{$PAY24_ORDER_REF}</td>
        </tr>
        {/if}
        <tr>
            <th>{l s='Payment number:' mod='pg24pay'}</th>
            <td>{$PAY24_TEMP.MsTxnId}</td>
        </tr>

        <tr>
            <th>{l s='Amount:' mod='pg24pay'}</th> 
            <td>{$PAY24_TEMP.Amount} {$PAY24_TEMP.CurrCode}</td>
        </tr>

        <tr>
            <th>{l s='Payment status:' mod='pg24pay'}</th> 
            <td>{l s='Pending' mod='pg24pay'}</td>
        </tr>
    </table>
{else}
    {l s='THERE WAS AN ERROR ON PAYMENT RETURN. IF YOU SEE THIS TEXT PLEASE CONTACT MERCHANT.' mod='pg24pay'}
{/if}
{/block}