<?php
  include("head.php");
?>

<style>
/* Clean Professional Style for Certificate Authority */
.contact-container {
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

.contact-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 15px;
}

.contact-card {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  padding: 15px;
}

.contact-card .name {
  font-weight: 600;
  color: #1a5276;
  font-size: 1.05rem;
  margin-bottom: 8px;
}

.contact-card .org {
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #dee2e6;
}

.contact-card .info {
  font-size: 0.9rem;
}

.contact-card .info div {
  margin: 4px 0;
}

.contact-card .label {
  color: #666;
  display: inline-block;
  width: 50px;
}

.mailing-list {
  background: #eaf2f8;
  border: 1px solid #aed6f1;
  padding: 15px;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.mailing-list .icon {
  font-size: 1.5rem;
}

.mailing-list .label {
  font-weight: 500;
  color: #1a5276;
}

.mailing-list a {
  font-family: monospace;
  background: #fff;
  padding: 3px 8px;
  border: 1px solid #dee2e6;
}

a {
  color: #1a5276;
  text-decoration: none;
}

a:hover {
  color: #154360;
  text-decoration: underline;
}
</style>

<div class="contact-container">

  <div class="page-header">
    <h1>Contact Information</h1>
  </div>

  <!-- CA Contact -->
  <div class="section">
    <h2 class="section-title">CA (Certificate Authority) Contact</h2>
    <div class="section-content">

      <div class="mailing-list">
        <span class="label">Mailing List:</span>
        <a href="mailto:kisti-grid-ca@kisti.re.kr">kisti-grid-ca@kisti.re.kr</a>
      </div>

      <div class="contact-grid">
        <div class="contact-card">
          <div class="name">Sang-Ho Na</div>
          <div class="org">
            Global Science experimental Data hub Center<br>
            National Institute of Supercomputing and Networking<br>
            Korea Institute of Science and Technology Information<br>
            245 Daehak-ro, Yuseong-gu 34141 Daejeon, Republic of Korea
          </div>
          <div class="info">
            <div><span class="label">Email</span> <a href="mailto:shna@kisti.re.kr">shna@kisti.re.kr</a></div>
            <div><span class="label">Tel</span> +82-42-869-0663</div>
            <div><span class="label">Fax</span> +82-42-869-1068</div>
          </div>
        </div>

        <div class="contact-card">
          <div class="name">Ilyeon Yeo</div>
          <div class="org">
            Global Science experimental Data hub Center<br>
            National Institute of Supercomputing and Networking<br>
            Korea Institute of Science and Technology Information<br>
            245 Daehak-ro, Yuseong-gu 34141 Daejeon, Republic of Korea
          </div>
          <div class="info">
            <div><span class="label">Email</span> <a href="mailto:ilyeon9@kisti.re.kr">ilyeon9@kisti.re.kr</a></div>
            <div><span class="label">Tel</span> +82-42-869-0658</div>
            <div><span class="label">Fax</span> +82-42-869-1015</div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- RA Contact -->
  <div class="section">
    <h2 class="section-title">RA (Registration Authority) Contact</h2>
    <div class="section-content">

      <div class="mailing-list">
        <span class="label">Mailing List:</span>
        <a href="mailto:kisti-grid-ra@kisti.re.kr">kisti-grid-ra@kisti.re.kr</a>
      </div>

      <div class="contact-grid">
        <div class="contact-card">
          <div class="name">Byungyun Kong</div>
          <div class="org">KISTI, GSDC ALICE Support</div>
          <div class="info">
            <div><span class="label">Email</span> <a href="mailto:kong91@kisti.re.kr">kong91@kisti.re.kr</a></div>
            <div><span class="label">Tel</span> 042-869-0843</div>
          </div>
        </div>

        <div class="contact-card">
          <div class="name">Geonmo Ryu</div>
          <div class="org">KISTI, GSDC CMS Support</div>
          <div class="info">
            <div><span class="label">Email</span> <a href="mailto:geonmo@kisti.re.kr">geonmo@kisti.re.kr</a></div>
            <div><span class="label">Tel</span> 042-869-1639</div>
          </div>
        </div>

        <div class="contact-card">
          <div class="name">Sangwook Bae</div>
          <div class="org">KISTI, GSDC LIGO Support</div>
          <div class="info">
            <div><span class="label">Email</span> <a href="mailto:wookie@kisti.re.kr">wookie@kisti.re.kr</a></div>
            <div><span class="label">Tel</span> 042-869-0835</div>
          </div>
        </div>

        <div class="contact-card">
          <div class="name">Ilyeon Yeo</div>
          <div class="org">KISTI, GSDC Belle II Support</div>
          <div class="info">
            <div><span class="label">Email</span> <a href="mailto:ilyeon9@kisti.re.kr">ilyeon9@kisti.re.kr</a></div>
            <div><span class="label">Tel</span> 042-869-0647</div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- System Auditor -->
  <div class="section">
    <h2 class="section-title">System Auditor</h2>
    <div class="section-content">

      <div class="contact-grid">
        <div class="contact-card">
          <div class="name">Sang-Ho Na</div>
          <div class="org">KISTI, GSDC</div>
          <div class="info">
            <div><span class="label">Email</span> <a href="mailto:shna@kisti.re.kr">shna@kisti.re.kr</a></div>
            <div><span class="label">Tel</span> 042-869-0663</div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

<?php
  include("tail.php");
?>
