<style>
	#twentyfourpay {
		text-align: center;
	}

	#twentyfourpay .twentyfourpay-gateways {
		text-align: center;
		padding: 20px 0;
	}

	#twentyfourpay .twentyfourpay-gateway {
		display: inline-block;
		margin: 0 5px;
	}

	#twentyfourpay .twentyfourpay-gateway button {
		background-size: 90%;
		background-position: center;
		width: 150px;
		height: 150px;
		border-radius: 5px;
		border: 1px solid #ccc;
		box-shadow: inset 0 0 0 3px #fafafa, 0 1px 5px rgba(0,0,0,.1);
		
		appearance: none;
		-webkit-appearance: none;
	}

	#twentyfourpay .twentyfourpay-gateway button img {
		display: none;
	}
</style>

<div id="twentyfourpay">
	<h1>{l s='Select your prefered payment gateway' mod='twentyfourpay'}</h1>
	<p>{l s='Please, choose from the available 24pay gateways for making the payment transaction' mod='twentyfourpay'}</p>
	{$htmlForms}
	<script language="javascript">
        document.getElementById("formButton").click();
    </script>
</div>