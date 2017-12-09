		function balance_refresh() 
		{
		    $.ajax({  
				type: "POST",  
				url: "vault/classes/Vault.php",  
				data: {'level': 'level'},  
				success: function(dataString) {  
					$('#vaultlevel').html('LEVEL ' + dataString[0]);
					$('#vaultlevelnav').html('level ' + dataString[0]);
					$('#exp').html('$' + dataString[1] + ' / ' + '$100.00');
				},
				dataType:"json"
			});  
			$.ajax({  
				type: "POST",  
				url: "vault/classes/Vault.php",  
				data: {'balance': 'balance'},  
				success: function(dataString) {  
					$('#vaultbalance').html('$' + Number(dataString[1]).toFixed(2) + ' USD (' + Number(dataString[0]).toFixed(5) + ' BTC)');
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
		
		function init_dashboard() {
			balance_refresh();	
			address_refresh();
			setInterval('balance_refresh()', 15000);
			setInterval('address_refresh()', 2000);
		}