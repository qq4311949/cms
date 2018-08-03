function checkInput(){
		var imgcode = document.getElementById("mycode").value;	
		if(imgcode == ""){
				alert("Please enter the right verification code!");
		}else{
			document.forms[0].submit();
		}
	}
	function newcode(){
 		var  img_obj = document.getElementById('imgcode');
  	img_obj.src = '/code.php?timeamp=' + new Date().getTime();
	}	