<div class="row-fluid">
    <div class="col-md-12">
        <div class="alert alert-info">
          
            <div style="height:59px;background-color:#FFF;display:inline-block;vertical-align: middle;border-radius: 4px;padding-left: 5px;padding-right: 5px;float:left;margin-right: 15px;">
                <img src="https://www.24-pay.sk/wp-content/themes/24pay/images/logo.gif" style="margin-top: 8px;"/>
            </div>
            
            <p><strong>{l s='This module allows you to accept payments with 24-pay payment gateway' mod='pg24pay'}</strong></p>
            <p>{l s='Payment gateway 24-Pay allows customers to pay with card or through internet banking.' mod='pg24pay'}</p>
            <p><i>{l s='High level of security, smooth online payment processing, supports 3D Secure security standards.' mod='pg24pay'}</i></p>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="col-md-12" style="padding-bottom: 15px;">
    
        <a href="{$PAY24_CONFIG_LINK}"  class="btn btn-large btn-default">
            <i class="icon-home" style="font-size:28px;width:30px;height:30px;"></i><br/> {l s='Home' mod='pg24pay'}
        </a>
            
        <a href="{$PAY24_CONFIG_LINK}&action=test"  class="btn btn-large btn-default" style="padding-left:10px;padding-right:10px;">
            <i class="icon-gear" style="font-size:28px;width:30px;height:30px;"></i><br/> {l s='Test' mod='pg24pay'}
        </a>
    </div>
</div>

<div class="row-fluid">
    <div class="col-md-6">
        <div class="panel" id="veinte-wrapper">
            <div class="panel-heading">
                <i class="icon-key"></i>&nbsp;&nbsp;&nbsp; {l s='24-pay Settings' mod='pg24pay'}
            </div>
            
            <form method="post" action="{$PAY24_CONFIG_LINK}" id="pg24pay_configuration">
                <div class="form-wrapper">

                        
                    <div class="form-group">			
                        <label>{l s='TEST ENVIROMENT' mod='pg24pay'}</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="PAY24_DEBUG" id="PAY24_DEBUG_on" value="1" {if $PAY24_DEBUG eq '1'}checked="checked"{/if}/>
                            <label for="PAY24_DEBUG_on">{l s='Yes' mod='pg24pay'}</label>
                            <input type="radio" name="PAY24_DEBUG" id="PAY24_DEBUG_off" value="0" {if $PAY24_DEBUG eq '0'}checked="checked"{/if}/>
                            <label for="PAY24_DEBUG_off">{l s='No' mod='pg24pay'}</label>
                            <a class="slide-button btn"></a>
                        </span>														
                    </div>
                    
                        <div class="form-group">
                            <label>{l s='MID' mod='pg24pay'}</label>
                            <input type="text" name="PAY24_MID" value="{$PAY24_MID}"/>
                        </div>
                        
                        <div class="form-group">
                            <label>{l s='E-ESHOP ID' mod='pg24pay'}</label>
                            <input type="text" name="PAY24_ESHOP_ID" value="{$PAY24_ESHOP_ID}"/>
                        </div>
                        
                        <div class="form-group">
                            <label>{l s='KEY' mod='pg24pay'}</label>
                            <input type="text" name="PAY24_KEY" value="{$PAY24_KEY}"/>
                        </div>
                        <legend>{l s='ADVANCE SETTING' mod='pg24pay'}</legend>
                        
                        <div class="form-group">			
                        <label>{l s='DEBUG MODULE PAYMENT' mod='pg24pay'} <br/>
                            <i>({l s='Last step will show all data and require confirmation' mod='pg24pay'})</i></label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="PAY24_MODULE_DEBUG" id="PAY24_MODULE_DEBUG_on" value="1" {if $PAY24_MODULE_DEBUG eq '1'}checked="checked"{/if}/>
                            <label for="PAY24_MODULE_DEBUG_on">{l s='Yes' mod='pg24pay'}</label>
                            <input type="radio" name="PAY24_MODULE_DEBUG" id="PAY24_MODULE_DEBUG_off" value="0" {if $PAY24_MODULE_DEBUG eq '0'}checked="checked"{/if}/>
                            <label for="PAY24_MODULE_DEBUG_off">{l s='No' mod='pg24pay'}</label>
                            <a class="slide-button btn"></a>
                        </span>														
                        </div>
                            
                        {if $PAY24_REPAY neq 1}
                            <div class="alert alert-danger">
                                {l s='We strongly recommend you to set parameter "CREATE ORDER ON FAIL PAYMENT" to'} <b>{l s='Yes' mod='pg24pay'}</b>.<br/> 
                                {l s='In case you set the value to' mod='pg24pay'}  <b>{l s='No' mod='pg24pay'}</b> {l s='responsibility for the problems caused by this setting is yours.' mod='pg24pay'}</div>
                        {/if}
                        <div class="form-group">			
                        <label>{l s='CREATE ORDER ON FAIL PAYMENT' mod='pg24pay'} <br/><i>({l s='Yes' mod='pg24pay'} - {l s='Recommended' mod='pg24pay'})</i></label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="PAY24_REPAY" id="PAY24_REPAY_on" value="1" {if $PAY24_REPAY eq '1'}checked="checked"{/if}/>
                            <label for="PAY24_REPAY_on">{l s='Yes' mod='pg24pay'}</label>
                            <input type="radio" name="PAY24_REPAY" id="PAY24_REPAY_off" value="0" {if $PAY24_REPAY eq '0'}checked="checked"{/if}/>
                            <label for="PAY24_REPAY_off">{l s='No' mod='pg24pay'}</label>
                            <a class="slide-button btn"></a>
                        </span>														
                        </div>
                            
                            
                        <div class="form-group">			
                        <label>{l s='LOG PAYMENT NOTIFICATION' mod='pg24pay'}</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="PAY24_LOG" id="PAY24_LOG_on" value="1" {if $PAY24_LOG eq '1'}checked="checked"{/if}/>
                            <label for="PAY24_LOG_on">{l s='Yes' mod='pg24pay'}</label>
                            <input type="radio" name="PAY24_LOG" id="PAY24_LOG_off" value="0" {if $PAY24_LOG eq '0'}checked="checked"{/if}/>
                            <label for="PAY24_LOG_off">{l s='No' mod='pg24pay'}</label>
                            <a class="slide-button btn"></a>
                        </span>														
                        </div>
                </div>
                <div class="panel-footer">
                    <input type="hidden" name="submitPaypal" value="pg24pay_configuration" />
                    <button type="submit" value="1" id="module_form_submit_btn" name="btnSubmit" class="btn btn-default pull-right">
                        <i class="process-icon-save"></i> {l s='Save' mod='pg24pay'}
                    </button>
                </div>
            </form>
        </div>            
    </div>
    <div class="col-md-6">
        <div class="panel" id="veinte-wrapper">
            <div class="panel-heading">
                <i class="icon-gears"></i>&nbsp;&nbsp;&nbsp; {l s='24-pay Setting Test' mod='pg24pay'}
            </div>
            <div class="form-wrapper">
                {if $PAY24_TEST eq 1}
                    <h4>{l s='Test result' mod='pg24pay'}</h4>
                    <b>{l s='Sign' mod='pg24pay'}:</b> {$PAY24_SIGN_RESULT}<br/>
                    <b>{l s='Active gates' mod='pg24pay'}:</b> {$PAY24_GATES_RESULT}
                {else}
                    <div class="alert alert-warning">
                        {l s='Click on button test below and launch the 24-pay settings test' mod='pg24pay'}
                    </div>
                {/if}
            </div>
            <div class="panel-footer">
                <input type="hidden" name="submitPaypal" value="pg24pay_configuration" />
                <a href="{$PAY24_CONFIG_LINK}&action=test" type="submit" value="1" id="module_test_btn"  class="btn btn-large btn-default pull-right">
                    <i class="icon-gear" style="font-size:28px;width:30px;height:30px;"></i><br/> {l s='Test' mod='pg24pay'}
                </a>
            </div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
                        
                        
	