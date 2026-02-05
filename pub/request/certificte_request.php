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

/* Page Header */
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

/* Notice Box */
.notice-box {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-left: 4px solid #1a5276;
  padding: 15px 20px;
  margin-bottom: 20px;
  font-size: 0.95rem;
}

.notice-box.warning {
  border-left-color: #e67e22;
  background: #fef9e7;
}

.notice-box strong {
  color: #1a5276;
}

/* Section */
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

/* Document Links */
.doc-link {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  margin-bottom: 8px;
  text-decoration: none;
  color: #333;
  transition: background 0.2s;
}

.doc-link:hover {
  background: #e9ecef;
  border-color: #1a5276;
}

.doc-link .icon {
  width: 24px;
  height: 24px;
  margin-right: 12px;
  flex-shrink: 0;
}

.doc-link .icon.pdf {
  color: #c0392b;
}

.doc-link .icon.web {
  color: #1a5276;
}

/* Ordered List */
.step-list {
  margin: 0;
  padding: 0;
  list-style: none;
  counter-reset: step;
}

.step-list > li {
  padding: 15px 0 15px 45px;
  border-bottom: 1px solid #eee;
  position: relative;
}

.step-list > li:last-child {
  border-bottom: none;
}

.step-list > li::before {
  counter-increment: step;
  content: counter(step);
  position: absolute;
  left: 0;
  top: 15px;
  width: 28px;
  height: 28px;
  background: #1a5276;
  color: #fff;
  border-radius: 50%;
  text-align: center;
  line-height: 28px;
  font-size: 0.85rem;
  font-weight: 600;
}

.step-list ul {
  margin: 10px 0 0 0;
  padding-left: 20px;
}

.step-list ul li {
  margin: 5px 0;
}

/* Alert Inline */
.alert-inline {
  display: inline-block;
  background: #fdedec;
  border: 1px solid #f5b7b1;
  color: #c0392b;
  padding: 4px 10px;
  font-size: 0.85rem;
  font-weight: 500;
  margin-top: 5px;
}

/* Certificate Type Table */
.cert-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.cert-table th,
.cert-table td {
  border: 1px solid #dee2e6;
  padding: 15px;
  text-align: left;
  vertical-align: top;
}

.cert-table th {
  background: #f8f9fa;
  font-weight: 600;
  width: 50%;
}

.cert-table .btn-request {
  display: inline-block;
  background: #1a5276;
  color: #fff;
  padding: 8px 20px;
  text-decoration: none;
  font-size: 0.9rem;
  margin-bottom: 10px;
}

.cert-table .btn-request:hover {
  background: #154360;
}

/* Links */
a {
  color: #1a5276;
}

a:hover {
  color: #154360;
}

/* Email */
.email-box {
  display: inline-block;
  background: #eaf2f8;
  padding: 3px 10px;
  font-family: monospace;
}
</style>

<div class="cert-container">

  <div class="page-header">
    <h1>Request Certificate</h1>
  </div>

  <!-- Notice -->
  <div class="notice-box warning">
    <strong>Before You Start</strong><br>
    Please review the <a href="KISTI_Grid_CA_Operation.ppt">KISTI CA Operation Document</a> (PowerPoint)
    to understand the certificate issuance procedure.
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

      <p>Subscribers must enroll to KISTI CA before requesting grid certificates.</p>

      <p><strong>Download Application Forms:</strong></p>

      <a href="kisti_ca_user_form_v1.5.pdf" class="doc-link">
        <svg class="icon pdf" viewBox="0 0 24 24" fill="currentColor">
          <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13,9V3.5L18.5,9H13Z"/>
        </svg>
        KISTI CA User Application Form (PDF)
      </a>

      <a href="Personal_Information_Protection_Act.pdf" class="doc-link">
        <svg class="icon pdf" viewBox="0 0 24 24" fill="currentColor">
          <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13,9V3.5L18.5,9H13Z"/>
        </svg>
        Personal Information Collection Agreement (Korean Only)
      </a>

      <p style="margin-top:20px;"><strong>Enrollment Procedure:</strong></p>

      <ol class="step-list">
        <li>
          <strong>Interview with RA (Registration Authority)</strong>
          <a href="/contact.php">[RA Contact]</a><br>
          Provide the following:
          <ul>
            <li>Completed application form</li>
            <li>Proof of identity (work ID, passport, national ID, etc.)
              <div class="alert-inline">Mask sensitive information before submission</div>
            </li>
          </ul>
        </li>

        <li>
          <strong>Keep your PIN number secure</strong><br>
          The RA will fill in the PIN field on the application form.
        </li>

        <li>
          <strong>Submit application via email</strong><br>
          Send the completed application form to
          <span class="email-box">kisti-grid-ca@kisti.re.kr</span>
        </li>

        <li>
          <strong>Wait for response</strong><br>
          KISTI CA staff will respond promptly upon receiving your request.
        </li>

        <li>
          <strong>Receive WACC (Web Access Client Certificate)</strong><br>
          If approved, WACC will be sent to your email. Install it in your browser to access the online certificate request service.<br>
          <em>Your PIN number is the WACC password.</em>
        </li>
      </ol>

      <a href="install_web_access_cert.php" class="doc-link">
        <svg class="icon web" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6Z"/>
        </svg>
        How to Install WACC in Your Browser
      </a>

    </div>
  </div>

  <!-- Step 2 -->
  <div class="section">
    <h2 class="section-title">Step 2. Request Grid Certificate</h2>
    <div class="section-content">

      <table class="cert-table">
        <tr>
          <th>User Certificate</th>
          <th>Host/Service Certificate</th>
        </tr>
        <tr>
          <td>
            <p>For individual users</p>
            <a href="user_certificate.php" class="btn-request">Request User Certificate</a>
            <a href="./How to Request User Certificate.pdf" class="doc-link">
              <svg class="icon pdf" viewBox="0 0 24 24" fill="currentColor">
                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13,9V3.5L18.5,9H13Z"/>
              </svg>
              Guide (KR/EN) - Updated 2024-03-18
            </a>
          </td>
          <td>
            <p>For servers and services</p>
            <a href="host_certificate.php" class="btn-request">Request Host Certificate</a>
            <a href="./How_to_Request_Host_Certificate_Korean.pdf" class="doc-link">
              <svg class="icon pdf" viewBox="0 0 24 24" fill="currentColor">
                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13,9V3.5L18.5,9H13Z"/>
              </svg>
              Guide (Korean)
            </a>
            <a href="./How_to_Request_Host_Certificate_English.pdf" class="doc-link">
              <svg class="icon pdf" viewBox="0 0 24 24" fill="currentColor">
                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M13,9V3.5L18.5,9H13Z"/>
              </svg>
              Guide (English)
            </a>
          </td>
        </tr>
      </table>

    </div>
  </div>

</div>

<?php
  include("../tail.php");
?>
