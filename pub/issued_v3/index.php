<?php

  unset($env);
  $env['prefix'] = '/kistica/html';
  include("$env[prefix]/include/common_v3.php");

  $env['self'] = $_SERVER['SCRIPT_NAME'];

  $env['pagewidth'] = 1000;
  include("../head.php");
?>

<style>
/* Issued Certificates Table Styles */
.issued-container {
  margin-top: 20px;
}

.issuer-info {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-left: 4px solid #1a5276;
  padding: 12px 15px;
  margin-bottom: 20px;
  font-size: 0.9rem;
}

.issuer-info strong {
  color: #1a5276;
}

.issuer-info code {
  background: #e9ecef;
  padding: 2px 6px;
  font-size: 0.85rem;
}

/* Desktop Table */
.cert-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}

.cert-table th {
  background: #1a5276;
  color: #fff;
  padding: 12px 10px;
  text-align: left;
  font-weight: 600;
  font-size: 0.85rem;
  white-space: nowrap;
}

.cert-table td {
  padding: 10px;
  border-bottom: 1px solid #dee2e6;
  vertical-align: middle;
}

.cert-table tbody tr:hover {
  background: #f8f9fa;
}

.cert-table tbody tr:nth-child(even) {
  background: #fafbfc;
}

.cert-table tbody tr:nth-child(even):hover {
  background: #f0f4f7;
}

/* Serial Number */
.serial-num {
  font-family: 'Consolas', monospace;
  color: #666;
}

.serial-hex {
  font-family: 'Consolas', monospace;
  color: #1a5276;
  font-weight: 500;
}

/* Type Badge */
.type-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.type-badge.person {
  background: #e8f4fd;
  color: #1a5276;
}

.type-badge.host {
  background: #fef3e2;
  color: #b45309;
}

/* Subject */
.subject-cell {
  max-width: 300px;
  word-break: break-all;
  font-size: 0.85rem;
  line-height: 1.4;
}

/* Valid Until */
.valid-date {
  white-space: nowrap;
  font-family: 'Consolas', monospace;
  font-size: 0.85rem;
}

/* Download Links */
.download-links {
  display: flex;
  gap: 5px;
}

.download-links a {
  display: inline-block;
  padding: 4px 10px;
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  font-size: 0.8rem;
  text-decoration: none;
  color: #333;
  transition: all 0.2s;
}

.download-links a:hover {
  background: #1a5276;
  color: #fff;
  border-color: #1a5276;
}

.view-link {
  color: #1a5276;
  font-weight: 500;
}

.view-link:hover {
  text-decoration: underline;
}

/* Mobile Card View */
.cert-cards {
  display: none;
}

.cert-card-item {
  background: #fff;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 12px;
}

.cert-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  padding-bottom: 10px;
  border-bottom: 1px solid #eee;
}

.cert-card-serial {
  font-family: 'Consolas', monospace;
  font-weight: 600;
  color: #1a5276;
}

.cert-card-body {
  font-size: 0.9rem;
}

.cert-card-row {
  display: flex;
  margin-bottom: 8px;
}

.cert-card-label {
  width: 80px;
  color: #666;
  flex-shrink: 0;
}

.cert-card-value {
  flex: 1;
  word-break: break-all;
}

.cert-card-actions {
  display: flex;
  gap: 8px;
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px solid #eee;
}

.cert-card-actions a {
  flex: 1;
  text-align: center;
  padding: 8px;
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  text-decoration: none;
  color: #333;
  font-size: 0.85rem;
  border-radius: 4px;
}

.cert-card-actions a:hover {
  background: #1a5276;
  color: #fff;
  border-color: #1a5276;
}

/* Responsive */
@media (max-width: 768px) {
  .cert-table {
    display: none;
  }

  .cert-cards {
    display: block;
  }

  .issuer-info code {
    display: block;
    margin-top: 5px;
    word-break: break-all;
  }
}
</style>

<div class="page-header">
  <h1>Issued Certificates List</h1>
</div>

<div class="issued-container">

  <div class="issuer-info">
    <strong>Issuer Subject:</strong>
    <code>C=KR, O=KISTI, CN=KISTI Certification Authority</code>
  </div>

  <!-- Desktop Table View -->
  <table class="cert-table">
    <thead>
      <tr>
        <th>Serial</th>
        <th>Hex</th>
        <th>Type</th>
        <th>Download</th>
        <th>Subject</th>
        <th>Valid Until</th>
        <th>Detail</th>
      </tr>
    </thead>
    <tbody>
<?php
  $qry = "SELECT * FROM cert WHERE status='issued' ORDER BY serial DESC";
  $ret = mysql_query($qry);

  $cert_data = array();
  while ($row = mysql_fetch_array($ret)) {
    $serial = $row['serial'];
    $serial_h = sprintf("%02x", $serial);
    $subject = $row['subject'];
    $ctype = $row['ctype'];
    $ctype_s = ($ctype == 'user') ? 'person' : 'host';
    $vuntil = $row['vuntil'];
    $vuntil_s = substr($vuntil, 0, 10);

    // Store for mobile view
    $cert_data[] = array(
      'serial' => $serial,
      'serial_h' => $serial_h,
      'subject' => $subject,
      'ctype_s' => $ctype_s,
      'vuntil_s' => $vuntil_s
    );

    print<<<EOS
      <tr>
        <td class="serial-num">$serial</td>
        <td class="serial-hex">$serial_h</td>
        <td><span class="type-badge $ctype_s">$ctype_s</span></td>
        <td>
          <div class="download-links">
            <a href="$serial_h.pem">PEM</a>
            <a href="$serial_h.crt">CRT</a>
          </div>
        </td>
        <td class="subject-cell">$subject</td>
        <td class="valid-date">$vuntil_s</td>
        <td><a href="$serial_h.txt" class="view-link">View</a></td>
      </tr>
EOS;
  }
?>
    </tbody>
  </table>

  <!-- Mobile Card View -->
  <div class="cert-cards">
<?php
  foreach ($cert_data as $cert) {
    $serial = $cert['serial'];
    $serial_h = $cert['serial_h'];
    $subject = $cert['subject'];
    $ctype_s = $cert['ctype_s'];
    $vuntil_s = $cert['vuntil_s'];

    print<<<EOS
    <div class="cert-card-item">
      <div class="cert-card-header">
        <span class="cert-card-serial">#$serial ($serial_h)</span>
        <span class="type-badge $ctype_s">$ctype_s</span>
      </div>
      <div class="cert-card-body">
        <div class="cert-card-row">
          <span class="cert-card-label">Subject</span>
          <span class="cert-card-value">$subject</span>
        </div>
        <div class="cert-card-row">
          <span class="cert-card-label">Valid Until</span>
          <span class="cert-card-value">$vuntil_s</span>
        </div>
      </div>
      <div class="cert-card-actions">
        <a href="$serial_h.pem">PEM</a>
        <a href="$serial_h.crt">CRT</a>
        <a href="$serial_h.txt">Detail</a>
      </div>
    </div>
EOS;
  }
?>
  </div>

</div>

<?php
  include("../tail.php");
?>
