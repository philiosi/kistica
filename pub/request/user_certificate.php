<?php
  include("../head.php");
?>

<style>
/* Clean Professional Style for Certificate Authority */
.cert-container {
  max-width: 860px;
  margin: 0 auto;
  padding: 30px 20px;
  font-family: 'Segoe UI', Tahoma, sans-serif;
  color: #333;
  line-height: 1.6;
}

.page-header {
  border-bottom: 3px solid #1a5276;
  padding-bottom: 15px;
  margin-bottom: 25px;
}

.page-header h1 {
  font-size: 1.6rem;
  color: #1a5276;
  font-weight: 600;
  margin: 0;
}

.notice-box {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-left: 4px solid #1a5276;
  padding: 15px 20px;
  margin-bottom: 20px;
  font-size: 0.95rem;
}

.notice-box strong {
  color: #1a5276;
}

.section {
  margin-bottom: 30px;
}

.section-title {
  font-size: 1.15rem;
  font-weight: 600;
  color: #fff;
  background: #1a5276;
  padding: 10px 15px;
  margin-bottom: 0;
}

.section-content {
  border: 1px solid #dee2e6;
  border-top: none;
  padding: 20px;
  background: #fff;
}

.btn-request {
  display: inline-block;
  background: #1a5276;
  color: #fff;
  padding: 12px 24px;
  text-decoration: none;
  font-size: 1rem;
  font-weight: 500;
  margin: 15px 0;
}

.btn-request:hover {
  background: #154360;
  color: #fff;
}

.info-box {
  background: #eaf2f8;
  border: 1px solid #aed6f1;
  border-left: 4px solid #3498db;
  padding: 12px 15px;
  margin: 15px 0;
  font-size: 0.9rem;
}

.info-box strong {
  color: #1a5276;
}

.alert-box {
  background: #fef9e7;
  border: 1px solid #f9e79f;
  border-left: 4px solid #e67e22;
  padding: 12px 15px;
  margin-top: 15px;
  font-size: 0.9rem;
}

.success-box {
  background: #eafaf1;
  border: 1px solid #a9dfbf;
  border-left: 4px solid #27ae60;
  padding: 12px 15px;
  margin: 15px 0;
  font-size: 0.9rem;
}

.code-box {
  background: #2c3e50;
  color: #ecf0f1;
  padding: 12px 15px;
  font-family: 'Consolas', 'Courier New', monospace;
  font-size: 0.9rem;
  margin: 10px 0;
  overflow-x: auto;
}

a {
  color: #1a5276;
}

a:hover {
  color: #154360;
}
</style>

<div class="cert-container">

  <div class="page-header">
    <h1>User Certificate Request</h1>
  </div>

  <div class="notice-box">
    <strong>Policy Agreement</strong><br>
    By requesting a certificate, you accept the
    <a href="http://ca.gridcenter.or.kr/cps/KISTI-CA-CPCPS-3.0.pdf">Certificate Policy and Certification Practice Statement (CP/CPS)</a>
    and agree to comply with the Subscriber Obligations (Section 1.3.3).
  </div>

  <!-- Step 1 -->
  <div class="section">
    <h2 class="section-title">Step 1. Enroll to KISTI CA</h2>
    <div class="section-content">
      <p>Before requesting a user certificate from KISTI CA, you must complete the enrollment process.</p>
      <p>If you haven't enrolled yet, please visit the <a href="certificte_request.php">Certificate Request</a> page for enrollment instructions.</p>
    </div>
  </div>

  <!-- Step 2 -->
  <div class="section">
    <h2 class="section-title">Step 2. Online Certificate Request</h2>
    <div class="section-content">
      <p>Click the button below to access the online certificate request service.</p>

      <a href="https://pki.gridcenter.or.kr/subscriber/request_v3.php" target="_blank" rel="noopener noreferrer" class="btn-request">
        Request User Certificate
      </a>

      <div class="alert-box">
        <strong>Note:</strong> You must have a valid WACC (Web Access Client Certificate) installed in your browser to access the request service.
      </div>

      <div class="success-box">
        <strong>Browser Support:</strong> This service supports all modern browsers including Google Chrome, Edge, Firefox, and Safari. IE (Internet Explorer) mode is no longer required.
      </div>

      <div class="info-box">
        <strong>Important - Private Key Download:</strong><br>
        After uploading your CSR, you can download a PIN-protected ZIP file containing your private key (filename: <code>#csrid_privateKey.zip</code>) from the result page.
      </div>

      <div class="info-box">
        <strong>ZIP File Password:</strong><br>
        The ZIP file is encrypted for security. You will find the PIN number in the email titled "[KISTI CA] User registration completed".
      </div>

      <p style="margin-top: 15px;"><strong>Unzip Command:</strong></p>
      <div class="code-box">
        unzip -P PIN_NUMBER /path/to/csrid_privateKey.zip
      </div>

      <p style="margin-top: 15px;"><strong>Protect Private Key with Password (Recommended):</strong></p>
      <div class="code-box">
        openssl rsa -aes256 -in your_private_key.pem -out your_private_key_encrypted.pem
      </div>

      <div class="info-box">
        For maximum security, use a passphrase of at least 12 characters. Always back up your private key in a secure location.
      </div>
    </div>
  </div>

  <!-- Step 3 -->
  <div class="section">
    <h2 class="section-title">Step 3. Download and Import Certificate</h2>
    <div class="section-content">
      <p>Once your certificate is issued, it will be published on the <a href="/issued_v3/">Issued Certificates</a> page.</p>
      <p>You will receive a confirmation email with a direct download link for your certificate.</p>
    </div>
  </div>

</div>

<?php
  include("../tail.php");
?>
