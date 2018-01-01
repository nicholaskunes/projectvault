		function balance_refresh() 
		{
			$.ajax({  
				type: "POST",  
				url: "vault/classes/Vault.php",  
				data: {'dashboarddata': 'dashboarddata'},  
				success: function(dataString) {  
					$('#btcprice').html('$' + dataString[6]);
					$('#vaultbalance').html('$' + Number(dataString[3]).toFixed(2) + ' USD (' + Number(dataString[2]).toFixed(5) + ' BTC)');
					$('#vaultlevel').html('LEVEL ' + dataString[0]);
					$('#vaultlevelnav').html('level ' + dataString[0]);
					$('#exp').html('$' + dataString[1] + ' / ' + '$' + dataString[4][parseInt(dataString[0])]);
					$('#levelbar').css('width', (dataString[1] / dataString[4][parseInt(dataString[0])] * 100) + "%");
					$('#qrcode').attr('src', "https://blockchain.info/qr?data=" + dataString[7]);
					$('#dailylevelbar').css('width', (dataString[5] / dataString[4][parseInt(dataString[0])]) * 1000 + "%");
					$('#dailycap').html('$' + dataString[5] + ' / ' + '$' + dataString[4][parseInt(dataString[0])]);
					$('#dailycapnote').html('increased to $' + dataString[4][parseInt(dataString[0]) + 1] + ' dollars at level ' + (parseInt(dataString[0]) + 1));
				},
				dataType:"json"
			});  
		}
		
		function populateWithdraw() {
			$.ajax({  
				type: "POST",  
				url: "vault/classes/Vault.php",  
				data: {'dashboarddata': 'dashboarddata'},  
				success: function(dataString) {  
					$('#btccalcprice').html("BTC " + parseFloat(document.getElementById('amountinput').value / dataString[6]).toPrecision(5));
					
					var feedata = JSON.parse(document.getElementById('feedata').innerHTML);
					
					var minersfee = 0.0;
					if($('#minersfeecheck').is(':checked'))
						minersfee = parseFloat(parseFloat(250 * parseFloat(feedata.hourFee / 2)) / 100000000);
					else
						minersfee = parseFloat(parseFloat(250 * parseFloat(feedata.hourFee / 3)) / 100000000);
											
					$('#amountinputfinal').val(parseFloat(parseFloat(document.getElementById('amountinput').value) + parseFloat(parseFloat(minersfee * parseFloat(dataString[6])))).toFixed(2));
					$('#btccalcpricefinal').html("BTC " + parseFloat(document.getElementById('amountinputfinal').value / dataString[6]).toPrecision(5));
				},
				dataType:"json"
			});  
		}
		
		function address_refresh() {
			$.ajax({  
				type: "POST",  
				url: "vault/classes/Vault.php",  
				data: {'address': 'address'},  
				success: function(dataString) {  
					$('#vaultaddress').html(dataString);
				}
			});  
		}
		
		function grabFees() {
			$.ajax({  
				type: "GET",  
				url: "https://bitcoinfees.earn.com/api/v1/fees/recommended",  
				success: function(dataString) {  
					$('#feedata').html(JSON.stringify(dataString));
				},
				dataType:"json"
			});  
		}
		
		
		function vault_refresh() 
		{
			$.ajax({  
				type: "POST",  
				url: "vault/classes/Vault.php",  
				data: {'dashboarddata': 'dashboarddata'},  
				success: function(dataString) {  
					$('#vaultbalance').html('$' + Number(dataString[3]).toFixed(2) + ' USD (' + Number(dataString[2]).toFixed(5) + ' BTC)');
				},
				dataType:"json"
			});  
		}
		
		
		
		
		
		
		
		
		
		function init_dashboard() {
			balance_refresh();	
			address_refresh();
			vault_refresh();
			grabFees();
			setInterval('grabFees()', 3000);
			setInterval('balance_refresh()', 15000);
			setInterval('address_refresh()', 2000);
			setInterval('vault_refresh()', 2000);
		}