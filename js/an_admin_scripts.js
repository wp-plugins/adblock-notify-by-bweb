/*
	an_admin_scripts.js
	AdBlock Notify
	Copyright: (c) 2014 Brice CAPOBIANCO, b-website.com
*/

jQuery(document).ready(function($) {
	
	resetButton = $('p.submit button.button-secondary[value!="save"]');
	resetButtonVal = resetButton.attr('onclick');
    resetButton.attr('onclick', 'javascript:if(!confirm(\'Are you sure ? Your custom settings will be lost.\')) return false; ' + resetButtonVal);  
	
});