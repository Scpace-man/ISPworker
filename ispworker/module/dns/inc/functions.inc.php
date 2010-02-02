<?

function dns_request($params) 
{
    global $dns_apiurl;
    global $dns_comkey;
    
    $ts = time();   
    
    $tan = sha1($dns_comkey.$ts)."-".$ts;
    $str = @file_get_contents($dns_apiurl."?tan=$tan".$params);

    return $str;
}
?>