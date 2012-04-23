var $j = jQuery.noConflict();

$j(document).ready(function(){
});
function processData(f1,f2,f3,f4,f5,f6){
	var v1 = document.getElementById(f1).value;
	var v2 = document.getElementById(f2).value;
	var v3 = document.getElementById(f3).value;
	var v4 = document.getElementById(f4).value;
	var v5 = document.getElementById(f5).value;
	var v6 = document.getElementById(f6).value;
	alert(v1+"\n"+v2+"\n"+v3+"\n"+v4+"\n"+v5+"\n"+v6);
	// Use Ajax-PHP to send the values to server storage
	// Ajax-PHP Video Tutorial - www.developphp.com/view.php?tid=1185
}
