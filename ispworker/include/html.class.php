<?php

class html {


    function table($width)
    {
	return new html_table($width);
    }    

}


class html_table extends html {

    var $string;
    var $string2;

    function html_table($width)
    {
	echo '
	<table width="'.$width.'" border="0" cellspacing="0" cellpadding="0">
	<tr class="tb">
	<td>
	    <table width="100%" border="0" cellspacing="1" cellpadding="3">
	';
    }
    
    
    function addcol($desc,$width,$colspan="0")
    {	
	if($desc=="") {
	    $this->string .= '<td width="'.$width.'" colspan="'.$colspan.'" align="center"><img src="img/pixel.gif" width="1" height="1"></td>'."\n";
	}
	else
	    $this->string .= '<td width="'.$width.'" colspan="'.$colspan.'"><b>'.$desc.'</b></td>'."\n";
    }
    
    
    function cols()
    {
	echo '<tr class="th">'.$this->string.'</tr>'."\n"; $this->clear();
    }
    
    
    function addrow($desc,$colspan="0",$width="0")
    {
	if($width==0) { $pw = ""; } else { $pw = "width=\"$width\""; }
	$this->string2 .= '<td valign="top" '.$pw.' colspan="'.$colspan.'">'.$desc.'</td>'."\n";  
    }
    
    
    function rows()
    {
	echo '<tr class="tr">'.$this->string2.'</tr>'."\n"; $this->clear();
    }

        
    function close() 
    {
	echo '
	</table>
	
	</td>
	</tr>
	</table>
	<br>
	';    
    }


    function clear() 
    {
	$this->string  = "";
	$this->string2 = "";
    }

}

?>