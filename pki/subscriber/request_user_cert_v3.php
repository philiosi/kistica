<?php

  //phpinfo();
  ### Debugging
  #ini_set('display_errors', 1);
  #ini_set('display_startup_errors', 1);
  #error_reporting(E_ALL);

  include("common_v3.php");
  #var_dump($form);
  #var_dump($conf);

  ######################################### set user info ####################################################
  # set user info
  $subscid = $env['subscid'];
  $email = $env['email'];         //subscriber's email of WACC
  ######################################### END set user info ################################################
  #var_dump($subscid);
  #var_dump($now);
  #var_dump($vuntil);

  #################################### Function ##############################################################

  function getUserCertInfo($m_serial) {
    $connect = connectDB();
    $qry = "SELECT c.* FROM cert c LEFT JOIN csr r ON c.certid=r.certid LEFT JOIN webcert w ON w.email = r.email WHERE c.ctype='user' AND c.status='issued' AND w.serial=? ORDER BY c.certid DESC LIMIT 1";

    if ($stmt = $connect->prepare($qry)) {
        // Bind the serial parameter to the query
        $stmt->bind_param("i", $m_serial);
        // Execute the query
        $stmt->execute();
        // Fetch the results
        $result = $stmt->get_result();
        // $result = $stmt->bind_result();
        $row = $result->fetch_assoc();
        // Close the statement
        $stmt->close();
    } else {
        echo "getUserCertInfo Query preparation error.";
    }
    return $row;
  }

  function getUserCsrInfo($email, $status) {
    $connect = connectDB();
    $qry = "SELECT * FROM csr WHERE status=? AND email=?";

    if ($stmt = $connect->prepare($qry)) {
        // Bind the email parameter to the query
        $stmt->bind_param("ss", $status, $email);
        // Execute the query
        $stmt->execute();
        // Fetch the results
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        // Close the statement
        $stmt->close();
    } else {
        echo "getUserCsrInfo Query preparation error.";
    }
    return $row;
  }

  function getCsrID (){
    $dbname = "kistica_v3";
    $table = "csr";

    $connect = connectDB();
    $qry = "SELECT auto_increment FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?";

    if ($stmt = $connect->prepare($qry)) {
        // Bind the email parameter to the query
        $stmt->bind_param("ss", $dbname, $table);
        // Execute the query
        $stmt->execute();
        // Fetch the results
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        // Close the statement
        $stmt->close();
    } else {
        echo "getUserCsrInfo Query preparation error.";
    }
    return $row['auto_increment'];
  }

  function getPIN ($id){
    $dbname = "kistica_v3";
    $table = "subscriber";

    $connect = connectDB();
    $qry = "select pin from subscriber where id = ?;";

    if ($stmt = $connect->prepare($qry)) {
        // Bind the email parameter to the query
        $stmt->bind_param("s", $id);
        // Execute the query
        $stmt->execute();
        // Fetch the results
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        // Close the statement
        $stmt->close();
    } else {
        echo "getUserCsrInfo Query preparation error.";
    }
    return $row['pin'];
  }

  function downloadPkey($file){
    #var_dump($file);

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
  }

  ## data diff function
  function _date_diff($dt1,$dt2){
    $y1 = substr($dt1,0,4);
    $m1 = substr($dt1,5,2);
    $d1 = substr($dt1,8,2);
    $h1 = substr($dt1,11,2);
    $i1 = substr($dt1,14,2);
    $s1 = substr($dt1,17,2);

    $y2 = substr($dt2,0,4);
    $m2 = substr($dt2,5,2);
    $d2 = substr($dt2,8,2);
    $h2 = substr($dt2,11,2);
    $i2 = substr($dt2,14,2);
    $s2 = substr($dt2,17,2);

    $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
    $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
    $diff = $r1-$r2;
    $days = floor($diff / 3600 / 24);
    return $days;
  }
#################################### End Function ##############################################################


// print("mode :".$mode."\n");
// print("can_upload : ".$can_upload."\n");
#################################### CSR Upload #############################################################
  if ($mode == 'upload') {
    $connect = connectDB();
    $qry = "SELECT * FROM csr WHERE status='upload' AND email='?'";
    if ($stmt = $connect->prepare($qry)) {
        // Bind the email parameter to the query
        $stmt->bind_param("s", $email);
        // Execute the query
        $stmt->execute();
        // Fetch the results
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        // Close the statement
        $stmt->close();
    } else {
        echo "getUserCertInfo Query preparation error.";
    }

    $row = getUserCsrInfo($email, "upload");

    if($row){
        $can_upload = false;
    }else{
        $can_upload = true;
    }

    $dnc_o = $form['dnc_o']; // Organization
    $dnc_cn = $form['dnc_cn']; // CN
    $csr = $form['csr']; # CSR text
    $email = $env['email']; # subscriber's email of WACC

    if($can_upload) {
        $forminfo =<<<EOS
O=$dnc_o|CN=$dnc_cn
EOS;
        $forminfo = addslashes($forminfo);
        #var_dump($forminfo);
        #var_dump($can_upload);
        $qry = "INSERT INTO csr SET csr='$csr'"
            .",forminfo='$forminfo'"
            .",csrtype='user'"
            .",email='$email'"
            .",status='upload'"
            .",certid=-1"
            .",idate=NOW()";

        //$connect = connectDB();
        #var_dump($qry);
        $ret = DBQuery($qry);

        $to = $email;
        $subject = "[KISTI Grid CA] CSR (certificate signing request) has been sent to KISTI CA";
        $header = "FROM: 'KISTI Grid CA'<ca@gridcenter.or.kr>\n";
        $header .= "CC: 'KISTI Grid CA'<ca@gridcenter.or.kr>\n";
        $message =<<<EOS

    Your CSR has been requestd to KISTI CA.

    KISTI CA will review your CSR, before issuing your certificate.

    After KISTI CA issue your certificate, a notification e-mail will be
    sent to this e-mail address.

    Issued certificates can be downloaded from the KISTI CA web site.

    If you have any question or problem, please send email to us.

    Your CSR is as follows:
    --------------------------------------------------------
    $csr
    --------------------------------------------------------

EOS;
        mail($to, $subject, $message, $header);

        include("head.php");

        $csrid = $_POST['csrid'];
?>
<!-- Page Title -->
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold text-slate-900 dark:text-white">Request User Certificate</h1>
</div>

<!-- Success Card -->
<div class="flex flex-col items-stretch justify-start rounded-xl shadow-lg bg-white dark:bg-[#192233] overflow-hidden border border-slate-200 dark:border-slate-800 mb-6">
  <div class="w-full h-24 bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center relative">
    <span class="material-symbols-outlined text-white/20 text-7xl absolute right-[-10px] bottom-[-10px]">check_circle</span>
    <div class="flex flex-col items-center">
      <span class="material-symbols-outlined text-white text-3xl">check_circle</span>
      <span class="text-white font-bold tracking-widest text-xs uppercase mt-1">CSR Submitted</span>
    </div>
  </div>
  <div class="p-5">
    <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed">
      Your CSR has been submitted to KISTI CA.<br><br>
      KISTI CA will review your CSR before issuing your certificate.<br><br>
      After KISTI CA issues your certificate, a notification email will be sent to you.
    </p>
  </div>
</div>

<!-- Warning Card -->
<div class="flex gap-3 items-start p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/30 mb-4">
  <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 shrink-0 text-xl">priority_high</span>
  <div class="text-sm text-slate-700 dark:text-slate-300">
    <strong class="block mb-1 font-bold text-slate-900 dark:text-white">Important!</strong>
    You cannot return to this page. Please <strong>DO NOT MISS</strong> to download your private key zip file!
  </div>
</div>

<div class="flex gap-3 items-start p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/30 mb-6">
  <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 shrink-0 text-xl">lock</span>
  <div class="text-sm text-slate-700 dark:text-slate-300">
    The ZIP file contains sensitive information and is encrypted for security. You'll find the PIN number in the email "[KISTI CA] User registration completed".
  </div>
</div>

<!-- Download Button -->
<form method="post" action="request_user_cert_v3.php">
  <input type='hidden' name='mode' value='download'>
  <input type='hidden' name='csrid' value='<?php echo htmlspecialchars($csrid); ?>'>
  <button type="submit" class="w-full h-14 bg-primary text-white font-bold text-base rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
    <span class="material-symbols-outlined">download</span>
    Download Private Key
  </button>
</form>

<?php
        include("tail.php");
        exit;
    }

  // end if ($mode == 'upload')
  }else if ($mode == 'download'){
    #var_dump($m_serial);
    $csrid = $_POST['csrid'];
    //echo $csrid;
    $keyPath = "/var/www/html/pki/subscriber/syek/".$csrid."_privateKey.zip";
    downloadPkey($keyPath);
    include("tail.php");
    exit;
  }

########################################## Main : Default #############################################################
    include("head.php");

    $email = $env['email']; # subscriber's email of WACC

    ## Get User Cert Info (using WACC serial number)
    $m_serial = $_SERVER['SSL_CLIENT_M_SERIAL'];
    $m_serial = hexdec($m_serial);
    $row = getUserCertInfo($m_serial);
    $vuntil = substr($row['vuntil'],0,10);

    $now = date("Y-m-d");
    $can_upload = false;
    $div_message = '';
    $status = '';
    $vuntil_html = '';

    if ($row){
        if ($now <= $vuntil) {
            $remains = _date_diff($vuntil,$now);

            if ($remains < 30) {        // 1) hava a valid cert, will be expired soon.
                $can_upload = true;
                $vuntil_html = "<span class='text-red-500 font-bold'>$row[vuntil] (D-$remains)</span>";
                $div_message = "You have a valid user certificate that expires soon (in 30 days). You can proceed with Step 1 and 2.";
                $status = "Expiring Soon";
                $status_type = "warning";
            } else {                    // 2) hava a valid cert
                $can_upload = false;
                $vuntil_html = "$row[vuntil] (D-$remains)";
                $div_message = "You have a valid user certificate. You cannot upload a CSR. (You can request a new user certificate if the certificate valid period is under 30 days)";
                $status = "Valid";
                $status_type = "success";
            }
        } else {                       // 3) have not a valid cert;
            $can_upload = true;
            $vuntil_html = $row['vuntil'];
            $div_message = "Your certificate has expired. You can proceed with Step 1 and 2.";
            $status = "Expired";
            $status_type = "failed";
        }
    } else {
        // No certificate found
        $qry = "SELECT * FROM csr WHERE status='upload' AND email='$email'";
        $connect = connectDB();
        $result = $connect->query($qry);
        $row_csr = $result->fetch_assoc();

        if(!$row_csr) {
            $can_upload=true;
            $div_message = "You don't have any valid user certificate. You can proceed with Step 1 and 2.";
        }else{
            $can_upload=false;
            $div_message = "You already have an uploaded CSR pending review.";
        }
        $connect->close();
    }

    $qry = "SELECT * FROM subscriber WHERE id='$subscid'";

    $connect = connectDB();
    $result=$connect->query($qry);
    if( !$result ) {
        exit( "subscriber query error:".$connect->error );
    }

    $row_sub = $result->fetch_assoc();
    $result->free();
    $connect->close();

    $cn = sprintf("%s %s %s", $row_sub['perid'], $row_sub['firstname'], $row_sub['lastname']);

    $m_serial = $_SERVER['SSL_CLIENT_M_SERIAL'];
    $m_serial = hexdec($m_serial);
    $qry = "SELECT w.*, s.*"
       ." FROM webcert w"
       ." LEFT JOIN subscriber s ON w.subscid=s.id"
       ." WHERE w.serial='$m_serial'";
    $row_wc = DBQueryAndFetchRow($qry);
    $org = $row_wc['org'];
?>

<!-- Page Title -->
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold text-slate-900 dark:text-white">Request User Certificate</h1>
</div>

<?php if ($row): ?>
<!-- Current Certificate Info Card -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
    <span class="material-symbols-outlined text-primary">badge</span>
    <h3 class="font-bold text-slate-900 dark:text-white">Current Certificate</h3>
  </div>
  <div class="p-4 space-y-3">
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Serial</span>
      <span class="text-sm text-slate-900 dark:text-white font-mono"><?php echo htmlspecialchars($row['serial']); ?></span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Subject</span>
      <span class="text-sm text-slate-900 dark:text-white font-mono text-right max-w-[200px] truncate"><?php echo htmlspecialchars($row['subject']); ?></span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Valid From</span>
      <span class="text-sm text-slate-900 dark:text-white"><?php echo htmlspecialchars($row['vfrom']); ?></span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Valid Until</span>
      <span class="text-sm"><?php echo $vuntil_html; ?></span>
    </div>
    <div class="flex justify-between items-center py-2">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Status</span>
      <?php echo StatusBadge($status_type, $status); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Status Message -->
<div class="flex gap-3 items-start p-4 rounded-xl <?php echo $can_upload ? 'bg-green-50 dark:bg-green-900/20 border-green-100 dark:border-green-800/30' : 'bg-amber-50 dark:bg-amber-900/20 border-amber-100 dark:border-amber-800/30'; ?> border mb-6">
  <span class="material-symbols-outlined <?php echo $can_upload ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400'; ?> shrink-0 text-xl"><?php echo $can_upload ? 'check_circle' : 'info'; ?></span>
  <div class="text-sm text-slate-700 dark:text-slate-300"><?php echo $div_message; ?></div>
</div>

<?php if ($can_upload): ?>

<?php
######################################## CSR Generation ##########################################################
    ## form request
    ## input : mode(=request), dnc_o, dnc_cn, csr, rno
    ## ouput : csr(<textarear></textarea>)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $can_upload) {
        $commonName = $_POST['dnc_cn'];
        $organization = $_POST['dnc_o'];
        $countryName = 'KR';
        $organization0 = 'KISTI';

        ### SET csr Info
        $csrid = getCsrID();
        ### SET openssl config
        $config = array("config" => "/etc/pki/tls/openssl.cnf");
        ### GENERATION privateKey
        $privateKey = openssl_pkey_new($config);

        $keyPath = "/var/www/html/pki/subscriber/syek/" . $csrid . "_privateKey.pem";
        $rlt = openssl_pkey_export_to_file($privateKey, $keyPath);

        ### SET CSR request info
        $configInput = "$countryName\n$organization0\n$organization\n$commonName\n";
        $path = "/var/www/html/pki/subscriber/openssl/";
        $tempConfigFile = tempnam($path, "inputCSR");
        $conRlt = file_put_contents($tempConfigFile, $configInput);

        ### GENERATION CSR with OpenSSL command
        $command = "openssl req -new -key " . $keyPath . " -out " . $path . $csrid . "_csr.pem -config /etc/pki/tls/openssl.cnf < " . escapeshellarg($tempConfigFile) . " 2>&1";
        $output = shell_exec($command);

        if ($rlt== true ){
            $secret = getPIN($subscid);
            $zipname = "/var/www/html/pki/subscriber/syek/" . $csrid . "_privateKey.zip";
            $cmd = "zip -P $secret $zipname -j $keyPath";
            shell_exec($cmd);
            unlink($keyPath);
        }

        $csrPath = $path . $csrid . "_csr.pem";
        if (file_exists($csrPath)) {
            $csrOut = file_get_contents($csrPath);
        } else {
            echo "CSR File not found.";
        }

        unlink($tempConfigFile);
?>

<!-- Step 1: CSR Generated -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">1</div>
    <h3 class="font-bold text-slate-900 dark:text-white">CSR Generated</h3>
    <span class="material-symbols-outlined text-green-500 ml-auto">check_circle</span>
  </div>
  <div class="p-4 space-y-3">
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium">Country</span>
      <span class="text-sm text-slate-900 dark:text-white">KR</span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium">Organization</span>
      <span class="text-sm text-slate-900 dark:text-white"><?php echo htmlspecialchars($org); ?></span>
    </div>
    <div class="flex justify-between items-center py-2">
      <span class="text-xs text-slate-500 font-medium">Common Name</span>
      <span class="text-sm text-slate-900 dark:text-white font-medium"><?php echo htmlspecialchars($cn); ?></span>
    </div>
  </div>
</div>

<!-- CSR Preview -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
  <details>
    <summary class="px-4 py-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center justify-between transition-colors">
      <h3 class="font-bold text-slate-900 dark:text-white">CSR Data</h3>
      <span class="material-symbols-outlined text-slate-400">expand_more</span>
    </summary>
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
      <div class="bg-slate-800 dark:bg-slate-900 rounded-lg p-4 overflow-x-auto">
        <pre class="text-xs text-green-400 font-mono whitespace-pre-wrap break-all"><?php echo htmlspecialchars($csrOut); ?></pre>
      </div>
    </div>
  </details>
</div>

<!-- Step 2: Upload CSR -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold">2</div>
    <h3 class="font-bold text-slate-900 dark:text-white">Upload CSR</h3>
  </div>
  <div class="p-4">
    <div class="flex gap-3 items-start p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/30 mb-4">
      <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 shrink-0 text-xl">priority_high</span>
      <div class="text-sm text-slate-700 dark:text-slate-300">
        <strong class="block mb-1 font-bold text-slate-900 dark:text-white">Important</strong>
        You can download the private key (pem) from the CSR upload result page.
      </div>
    </div>

    <form name="form" method="post" action="request_user_cert_v3.php">
      <input type='hidden' name='mode' value='upload'>
      <input type='hidden' name='csrid' value='<?php echo $csrid; ?>'>
      <input type='hidden' name='dnc_o' value='<?php echo htmlspecialchars($org); ?>'>
      <input type='hidden' name='dnc_cn' value='<?php echo htmlspecialchars($cn); ?>'>
      <textarea name='csr' class="hidden"><?php echo htmlspecialchars($csrOut); ?></textarea>

      <button type="submit" id="uploadcsrBtn" class="w-full h-14 bg-primary text-white font-bold text-base rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
        <span class="material-symbols-outlined">upload</span>
        Upload CSR
      </button>
    </form>
  </div>
</div>

<?php
    } else {
        // Show Generate CSR form
?>

<!-- Step 1: Generate CSR -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold">1</div>
    <h3 class="font-bold text-slate-900 dark:text-white">Generate CSR</h3>
  </div>
  <div class="p-4">
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
      Check the following form and click the 'Generate CSR' button. The server will create a CSR for you.
    </p>

    <form method="post" action="request_user_cert_v3.php" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Country</label>
        <div class="w-full h-12 px-4 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center text-slate-900 dark:text-white">KR</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Organization</label>
        <div class="w-full h-12 px-4 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center text-slate-900 dark:text-white"><?php echo htmlspecialchars($org); ?></div>
        <input type='hidden' name='dnc_o' value='<?php echo htmlspecialchars($org); ?>'>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Common Name</label>
        <div class="w-full h-12 px-4 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center text-slate-900 dark:text-white font-medium"><?php echo htmlspecialchars($cn); ?></div>
        <input type='hidden' name='dnc_cn' value='<?php echo htmlspecialchars($cn); ?>'>
      </div>

      <button type="submit" class="w-full h-14 bg-primary text-white font-bold text-base rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
        <span class="material-symbols-outlined">key</span>
        Generate CSR
      </button>
    </form>
  </div>
</div>

<!-- Info Notices -->
<div class="space-y-3">
  <div class="flex gap-3 items-start p-4 rounded-xl bg-blue-50 dark:bg-primary/10 border border-blue-100 dark:border-primary/20">
    <span class="material-symbols-outlined text-primary shrink-0 text-xl">info</span>
    <div class="text-sm text-slate-700 dark:text-slate-300">
      <strong class="block mb-1 font-bold text-slate-900 dark:text-white">Browser Support</strong>
      This function supports all modern browsers (Google Chrome, Edge, Firefox, Safari, etc). IE mode in Edge is no longer necessary.
    </div>
  </div>
</div>

<?php
    }
?>

<?php else: ?>
<!-- Cannot Upload - Show info only -->
<div class="text-center py-8">
  <a href="cert_v3.php" class="inline-flex items-center gap-2 text-primary font-medium">
    <span class="material-symbols-outlined">arrow_back</span>
    View My Certificates
  </a>
</div>
<?php endif; ?>

<?php
include("tail.php");
?>
