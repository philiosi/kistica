<?php
  include("head.php");
?>

<style>
.link-card {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  padding: 12px 15px;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: all 0.2s;
}

.link-card:hover {
  background: #e9ecef;
  border-color: #1a5276;
}

.link-card a {
  color: #333;
  text-decoration: none;
  flex: 1;
}

.link-card a:hover {
  color: #1a5276;
}

.link-card .badge {
  font-size: 0.7rem;
  padding: 2px 8px;
  border-radius: 3px;
  margin-left: 10px;
  white-space: nowrap;
}

.link-card .badge.active {
  background: #27ae60;
  color: #fff;
}

.link-card .badge.outdated {
  background: #f39c12;
  color: #fff;
}

.link-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.link-section {
  background: #fff;
  border: 1px solid #dee2e6;
}

.link-section-title {
  background: #1a5276;
  color: #fff;
  padding: 10px 15px;
  font-weight: 600;
  font-size: 0.95rem;
}

.link-section-content {
  padding: 15px;
}

.last-checked {
  font-size: 0.8rem;
  color: #666;
  margin-top: 20px;
  text-align: right;
}
</style>

<div class="page-header">
  <h1>Related Sites</h1>
</div>

<div class="link-grid">

  <!-- PMAs -->
  <div class="link-section">
    <div class="link-section-title">PMAs (Policy Management Authorities)</div>
    <div class="link-section-content">
      <div class="link-card">
        <a href="https://www.igtf.net/" target="_blank" rel="noopener noreferrer">International Grid Trust Federation (IGTF)</a>
        <span class="badge active">Active</span>
      </div>
      <div class="link-card">
        <a href="https://www.eugridpma.org/" target="_blank" rel="noopener noreferrer">EUGridPMA (Europe, Middle East, Africa)</a>
        <span class="badge active">Active</span>
      </div>
      <div class="link-card">
        <a href="https://www.tagpma.org/" target="_blank" rel="noopener noreferrer">TAGPMA (The Americas)</a>
        <span class="badge active">Active</span>
      </div>
      <div class="link-card">
        <a href="https://www.apgridpma.org/" target="_blank" rel="noopener noreferrer">APGridPMA (Asia Pacific)</a>
        <span class="badge outdated">Outdated</span>
      </div>
    </div>
  </div>

  <!-- CA Sites -->
  <div class="link-section">
    <div class="link-section-title">CA Sites</div>
    <div class="link-section-content">
      <div class="link-card">
        <a href="https://ca.grid.sinica.edu.tw/" target="_blank" rel="noopener noreferrer">ASGCCA (Academia Sinica Grid CA, Taiwan)</a>
        <span class="badge active">Active</span>
      </div>
      <div class="link-card">
        <a href="https://voms.cnaf.infn.it/" target="_blank" rel="noopener noreferrer">INFN CA (Italy)</a>
      </div>
      <div class="link-card">
        <a href="https://www.cilogon.org/" target="_blank" rel="noopener noreferrer">CILogon (US Research &amp; Education)</a>
        <span class="badge active">Active</span>
      </div>
      <div class="link-card">
        <a href="https://www.terena.org/activities/tcs/" target="_blank" rel="noopener noreferrer">GÉANT TCS (Trusted Certificate Service)</a>
      </div>
    </div>
  </div>

  <!-- Information -->
  <div class="link-section">
    <div class="link-section-title">Information &amp; Resources</div>
    <div class="link-section-content">
      <div class="link-card">
        <a href="https://en.wikipedia.org/wiki/X.509" target="_blank" rel="noopener noreferrer">X.509 - Wikipedia</a>
      </div>
      <div class="link-card">
        <a href="https://en.wikipedia.org/wiki/Grid_computing" target="_blank" rel="noopener noreferrer">Grid Computing - Wikipedia</a>
      </div>
      <div class="link-card">
        <a href="https://www.grid.ac/" target="_blank" rel="noopener noreferrer">GridPP (UK Grid for Particle Physics)</a>
      </div>
      <div class="link-card">
        <a href="https://wlcg.web.cern.ch/" target="_blank" rel="noopener noreferrer">WLCG (Worldwide LHC Computing Grid)</a>
      </div>
    </div>
  </div>

  <!-- Trust Anchors -->
  <div class="link-section">
    <div class="link-section-title">Trust Anchor Distribution</div>
    <div class="link-section-content">
      <div class="link-card">
        <a href="https://dist.eugridpma.info/distribution/igtf/" target="_blank" rel="noopener noreferrer">IGTF Trust Anchor Distribution</a>
        <span class="badge active">Active</span>
      </div>
      <div class="link-card">
        <a href="https://www.eugridpma.org/documentation/fetch-crl/" target="_blank" rel="noopener noreferrer">fetch-crl (CRL Download Tool)</a>
      </div>
    </div>
  </div>

</div>

<p class="last-checked">Last verified: January 2026</p>

<?php
  include("tail.php");
?>
