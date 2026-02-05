<?php
 print<<<EOS
<!DOCTYPE html>
<html>
<head>
<title>KISTI Grid Certificate Authority</title>
<style>
body {
 font-size:80%;
 color:#000000;
 background-color:#fafafa;
 margin:10px;
}

body,p,td,ul,textarea,input { font-family: verdana; }

a:link    { text-decoration:underline; color:#008800; }
a:visited { text-decoration:underline; color:#008800; }
a:hover   { text-decoration:underline; color:#880000; }

</style>
</head>

<body>

If your are a subscriber, click <a href='/subscriber'>here</a>.<br>
If your are a RA, click <a href='/ra'>here</a>.<br>
If your are a CA operator, click <a href='/ca'>here</a>.<br>
<br>

<table border='0'><!--1-->
<tr><td>

<table border='0' width='230' cellpadding='5'><tr><td bgcolor='#f0f0f0'>
<table border='0' width='100%' cellpadding='0'><tr><td bgcolor='#ffffff'>
<table border='0' width='100%'>
<tr>
    <td align='center' >
        <button id='subscriber'><font size='+2'><br><b>Subscriber</b><br><br></font></button>
    </td>
</td></tr>
</table>
</td></tr></table>
</td></tr></table>

</td><td><!--1-->

<table border='0' width='230' cellpadding='5'><tr><td bgcolor='#aacaaa'>
<table border='0' width='100%' cellpadding='0'><tr><td bgcolor='#ffffff'>
<table border='0' width='100%'>
<tr>
    <td align='center' >
        <button id='ra'><font size='+2'><br><b>RA Operator</b><br><br></font></button>
    </td>
</tr>
</table>
</td></tr></table>
</td></tr></table>

</td><td><!--1-->

<table border='0' width='230' cellpadding='5'><tr><td bgcolor='#caaaaa'>
<table border='0' width='100%' cellpadding='0'><tr><td bgcolor='#ffffff'>
<table border='0' width='100%'>
<tr>
    <td align='center' >
        <button id='ca'><font size='+2'><br><b>CA Operator</b><br><br></font></button>
    </td>
</td></tr>
</table>
</td></tr></table>
</td></tr></table>

</td></tr></table><!--1-->

<script nonce="6f236925325e7a76eaa77450eea83215">
    const subBtn = document.getElementById("subscriber");
    subBtn.addEventListener("click", subSelection);

    const raBtn = document.getElementById("ra");
    raBtn.addEventListener("click", raSelection);

    const caBtn = document.getElementById("ca");
    caBtn.addEventListener("click", caSelection);

    function subSelection(){
        console.log("I am RA");
        document.location='/subscriber';
    }

    function raSelection(){
        console.log("I am RA");
        document.location='/ra';
    }

    function caSelection(){
        console.log("I am RA");
        document.location='/ca';
    }
</script>
</body>
</html>
EOS;
?>