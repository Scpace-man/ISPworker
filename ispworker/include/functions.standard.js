	
	function findobject(n, d)
	{
		var p,i,x;
		if(!d) d=document;
		if((p=n.indexOf("?"))>0&&parent.frames.length)
		{
			d=parent.frames[n.substring(p+1)].document;
			n=n.substring(0,p);
		}
		if(!(x=d[n])&&d.all) x=d.all[n];
		for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=Find_Object(n,d.layers[i].document);
		return x;
	}
	
	function swapimage()
	{
		var i,j=0,x,a=swapimage.arguments;
		document.MM_sr=new Array;
		for(i=0;i<(a.length-2);i+=3)
		{
			if ((x=findobject(a[i]))!=null)
			{
				document.MM_sr[j++]=x;
				if(!x.oSrc) x.oSrc=x.src;
				x.src=a[i+2];
			}
		}
	}
	
	function preloadimages()
	{
		var d=document;
		if(d.images)
		{
			if(!d.MM_p) d.MM_p=new Array();
			var i,j=d.MM_p.length,a=preloadimages.arguments;
			for(i=0; i<a.length; i++)
			{
				if (a[i].indexOf("#")!=0)
				{
					d.MM_p[j]=new Image;
					d.MM_p[j++].src=a[i];
				}
			}
		}
	}
	
	function restoreimage()
	{
		var i,x,a=document.MM_sr;
		for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
	}
	
	function jumpmenu(targ,selObj,restore)
	{
  		eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
		if(restore) selObj.selectedIndex=0;
	}
	
	function openwindow(a, b, h, scrollbars)
	{
		var BildschirmB = screen.availWidth;
		var BildschirmH = screen.availHeight;
		var PixelX = (BildschirmB - b) / 2;
		var PixelY = (BildschirmH - h) / 2;
		var Link = a;
		if(scrollbars)
		{
			var Eigenschaften = "height=" + h + ",width=" + b + ",menubar=no,locationbar=no,status=no,resizable=no,screenX=" + PixelX + ",screenY=" + PixelY;
		}
		else
		{
			var Eigenschaften = "height=" + h + ",width=" + b + ",menubar=no,locationbar=no,status=no,scrollbars=no,resizable=no,screenX=" + PixelX + ",screenY=" + PixelY;
		}
			
		fenster = window.open(Link, "fenster", Eigenschaften);
		fenster.resizeTo(b,h);
		fenster.focus();
	}
	
	function setfocus()
	{
		if (document.forms.length > 0)
		{
			var form = document.forms[0]
			var num = form.elements.length;
			for (var i = 0; i < num; i++)
			{
				if ((form.elements[i].type == "text" || form.elements[i].type == "textarea" || form.elements[i].type == "password") && !form.elements[i].disabled)
				{
					form.elements[i].focus();
					break;
				}
			}
		}
	}
	
	function togglebutton(form,toggletext)
	{
		form.submitbutton.value=toggletext;
		form.submitbutton.disabled=true;
		form.submit();
	}

function inserttag(tag){
  textarea = window.document.forms['formular'].elements['textfeld'];
  textarea.value += tag;
  textarea.focus();
}
