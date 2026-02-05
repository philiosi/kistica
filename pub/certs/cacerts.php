<?php
  include("../head.php");
?>

<style>
.cert-card {
  background: #fff;
  border: 1px solid #dee2e6;
  margin-bottom: 20px;
}

.cert-card.current {
  border-color: #1a5276;
  border-width: 2px;
}

.cert-card.obsolete {
  opacity: 0.8;
}

.cert-header {
  background: #1a5276;
  color: #fff;
  padding: 12px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.cert-header.obsolete {
  background: #7f8c8d;
}

.cert-header.expired {
  background: #95a5a6;
}

.cert-title {
  font-weight: 600;
  font-size: 1.1rem;
}

.cert-badge {
  font-size: 0.75rem;
  padding: 3px 10px;
  border-radius: 3px;
  background: rgba(255,255,255,0.2);
}

.cert-badge.active {
  background: #27ae60;
}

.cert-badge.obsolete {
  background: #e67e22;
}

.cert-badge.expired {
  background: #c0392b;
}

.cert-body {
  padding: 20px;
}

.cert-info {
  display: grid;
  grid-template-columns: 140px 1fr;
  gap: 8px 15px;
  margin-bottom: 15px;
}

.cert-info .label {
  color: #666;
  font-size: 0.9rem;
}

.cert-info .value {
  font-size: 0.9rem;
  word-break: break-all;
}

.cert-info .value.subject {
  font-weight: 600;
  color: #1a5276;
}

.cert-info .value.fingerprint {
  font-family: 'Consolas', monospace;
  font-size: 0.8rem;
  background: #f8f9fa;
  padding: 5px 8px;
}

.cert-downloads {
  background: #f8f9fa;
  padding: 15px;
  margin-top: 15px;
  border: 1px solid #dee2e6;
}

.cert-downloads-title {
  font-weight: 600;
  margin-bottom: 10px;
  color: #1a5276;
}

.cert-downloads a {
  display: inline-block;
  background: #fff;
  border: 1px solid #dee2e6;
  padding: 5px 12px;
  margin: 3px;
  font-size: 0.85rem;
  transition: all 0.2s;
}

.cert-downloads a:hover {
  border-color: #1a5276;
  background: #eaf2f8;
  text-decoration: none;
}

.cpcps-list {
  margin-top: 10px;
  padding-left: 20px;
}

.cpcps-list li {
  margin: 5px 0;
  font-size: 0.9rem;
}
</style>

<div class="page-header">
  <h1>CA Certificates</h1>
</div>

<!-- KISTI CA v3.0 (Current) -->
<div class="cert-card current">
  <div class="cert-header">
    <span class="cert-title">KISTI CA v3.0</span>
    <span class="cert-badge active">Current</span>
  </div>
  <div class="cert-body">
    <div class="cert-info">
      <span class="label">Subject</span>
      <span class="value subject">C=KR, O=KISTI, CN=KISTI Certification Authority</span>

      <span class="label">Valid From</span>
      <span class="value">Apr 14, 2017</span>

      <span class="label">Valid Until</span>
      <span class="value">Apr 9, 2037 (20 years)</span>

      <span class="label">Key Size</span>
      <span class="value">4096 bits</span>

      <span class="label">SHA256 Fingerprint</span>
      <span class="value fingerprint">CD:D6:F9:D8:39:75:94:29:71:6F:61:07:8E:77:91:A0:61:F7:42:B7:BE:72:CF:A9:CE:7C:E2:60:7F:5A:09:F1</span>

      <span class="label">SHA1 Fingerprint</span>
      <span class="value fingerprint">06:52:34:EE:81:0A:E5:9C:43:68:54:84:8C:8A:D4:DF:0D:75:A7:45</span>

      <span class="label">Issued Certificates</span>
      <span class="value"><a href="/issued_v3/">View List</a></span>

      <span class="label">CRL</span>
      <span class="value"><a href="/CRL/kisti-ca-v3.crl">http://ca.gridcenter.or.kr/CRL/kisti-ca-v3.crl</a></span>

      <span class="label">CP/CPS</span>
      <span class="value"><a href="/cps/KISTI-CA-CPCPS-3.0.pdf">KISTI-CA-CPCPS-3.0.pdf</a></span>
    </div>

    <div class="cert-downloads">
      <div class="cert-downloads-title">Download Certificate</div>
      <a href="certificates/kisti-ca-v3.0">kisti-ca-v3.0</a>
      <a href="certificates/kisti-ca-v3.crt">.crt</a>
      <a href="certificates/kisti-ca-v3.txt">.txt</a>
      <a href="certificates/kisti-ca-v3.signing_policy">signing_policy</a>
    </div>
  </div>
</div>

<!-- KISTI GRID CA v2.0 (Obsolete) -->
<div class="cert-card obsolete">
  <div class="cert-header obsolete">
    <span class="cert-title">KISTI GRID CA v2.0</span>
    <span class="cert-badge obsolete">Unaccredited by IGTF</span>
  </div>
  <div class="cert-body">
    <div class="cert-info">
      <span class="label">Subject</span>
      <span class="value subject">C=KR, O=KISTI, O=GRID, CN=KISTI Grid Certificate Authority</span>

      <span class="label">Valid From</span>
      <span class="value">Jul 12, 2007</span>

      <span class="label">Valid Until</span>
      <span class="value">Aug 1, 2017 (10 years)</span>

      <span class="label">Key Size</span>
      <span class="value">2048 bits</span>

      <span class="label">SHA1 Fingerprint</span>
      <span class="value fingerprint">EA:08:BA:6A:36:C9:F1:0A:B5:2F:BB:67:C7:A4:3D:C9:52:B7:CE:DB</span>

      <span class="label">Issued Certificates</span>
      <span class="value"><a href="/issued/">View List</a></span>

      <span class="label">CRL</span>
      <span class="value"><a href="/CRL/722e5071.crl">http://ca.gridcenter.or.kr/CRL/722e5071.crl</a></span>

      <span class="label">CP/CPS</span>
      <span class="value"><a href="/cps/KISTI-CPCPS-2.0.html">KISTI-CPCPS-2.0.html</a></span>
    </div>

    <div class="cert-downloads">
      <div class="cert-downloads-title">Download Certificate</div>
      <a href="certificates/722e5071.0">722e5071.0</a>
      <a href="certificates/722e5071.crt">.crt</a>
      <a href="certificates/722e5071.txt">.txt</a>
      <a href="certificates/722e5071.signing_policy">signing_policy</a>
    </div>
  </div>
</div>

<!-- Production Level CA (2004-2006) -->
<div class="cert-card obsolete">
  <div class="cert-header expired">
    <span class="cert-title">Production Level CA</span>
    <span class="cert-badge expired">Jun 2004 - Nov 2006</span>
  </div>
  <div class="cert-body">
    <div class="cert-info">
      <span class="label">Subject</span>
      <span class="value subject">C=KR, O=KISTI, CN=KISTI GRID ROOT CA</span>

      <span class="label">Valid Until</span>
      <span class="value">May 30, 2009 GMT</span>

      <span class="label">Key Size</span>
      <span class="value">2048 bits</span>

      <span class="label">CRL</span>
      <span class="value"><a href="http://ca.gridcenter.or.kr/CRL/47183fda.crl">http://ca.gridcenter.or.kr/CRL/47183fda.crl</a></span>
    </div>

    <div class="cert-downloads">
      <div class="cert-downloads-title">Download Certificate</div>
      <a href="certificates/47183fda.0">47183fda.0</a>
      <a href="certificates/47183fda.signing_policy">signing_policy</a>
      <a href="certificates/47183fda.txt">View (.txt)</a>
      <a href="certificates/globus-host-ssl.conf">globus-host-ssl.conf</a>
      <a href="certificates/globus-user-ssl.conf">globus-user-ssl.conf</a>
    </div>

    <div style="margin-top: 15px;">
      <strong>CP/CPS History:</strong>
      <ul class="cpcps-list">
        <li><a href="/cps/47183fda/KISTI-GRID-CA-CP-CPS-V1.3.pdf">Version 1.3 (August 9, 2004)</a></li>
        <li><a href="/cps/47183fda/KISTI-GRID-CA-CP-CPS-V1.2.pdf">Version 1.2 (June 29, 2004)</a></li>
        <li><a href="/cps/47183fda/KISTI-GRID-CA-CP-CPS-V1.1.pdf">Version 1.1 (June 7, 2004)</a></li>
        <li><a href="/cps/47183fda/KISTI-GRID-CA-CP-CPS-V1.0.pdf">Version 1.0 (June 1, 2004)</a></li>
      </ul>
    </div>
  </div>
</div>

<!-- Experimental version -->
<div class="cert-card obsolete">
  <div class="cert-header expired">
    <span class="cert-title">Experimental Version</span>
    <span class="cert-badge expired">Expired</span>
  </div>
  <div class="cert-body">
    <div class="cert-info">
      <span class="label">Subject</span>
      <span class="value subject">C=KR, O=Globus, CN=KISTI Supercomputing Center CA2</span>

      <span class="label">Valid Until</span>
      <span class="value">March 14, 2013</span>
    </div>

    <div class="cert-downloads">
      <div class="cert-downloads-title">Download Certificate</div>
      <a href="certificates/f93666d2.0">f93666d2.0</a>
      <a href="certificates/f93666d2.signing_policy">signing_policy</a>
      <a href="certificates/f93666d2.txt">View (.txt)</a>
    </div>
  </div>
</div>

<!-- Experimental version (EXPIRED) -->
<div class="cert-card obsolete">
  <div class="cert-header expired">
    <span class="cert-title">Experimental Version (Legacy)</span>
    <span class="cert-badge expired">Expired</span>
  </div>
  <div class="cert-body">
    <div class="cert-info">
      <span class="label">Subject</span>
      <span class="value subject">C=KR, O=Globus, CN=KISTI Supercomputing Center CA</span>

      <span class="label">Valid Until</span>
      <span class="value">March 27, 2003</span>
    </div>

    <div class="cert-downloads">
      <div class="cert-downloads-title">Download Certificate</div>
      <a href="certificates/82da68f0.0">82da68f0.0</a>
      <a href="certificates/82da68f0.signing_policy">signing_policy</a>
      <a href="certificates/82da68f0.txt">View (.txt)</a>
    </div>
  </div>
</div>

<?php
  include("../tail.php");
?>
