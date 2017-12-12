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
					alert(JSON.stringify(dataString));
				},
				dataType:"json"
			});  
		}
		
		function init_dashboard() {
			balance_refresh();	
			address_refresh();
			grabFees();
			setInterval('balance_refresh()', 15000);
			setInterval('address_refresh()', 2000);
		}