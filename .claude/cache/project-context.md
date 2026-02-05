# KISTI CA Project Context

## Architecture Overview

```
+------------------+     +------------------+     +------------------+
|   Public Portal  |     |   PKI Portal     |     |    Database      |
|   (pub/)         |     |   (pki/)         |     |    (MySQL)       |
+------------------+     +------------------+     +------------------+
        |                        |                        |
        |   +--------------------+--------------------+   |
        |   |                    |                    |   |
        v   v                    v                    v   v
+------------------+     +------------------+     +------------------+
|   Subscriber     |     |   RA Manager     |     |   CA Manager     |
|   (subscriber/)  |     |   (ra/)          |     |   (ca/)          |
+------------------+     +------------------+     +------------------+
        |                        |                        |
        +------------------------+------------------------+
                                 |
                    +------------+------------+
                    |                         |
              +-----v-----+           +-------v-------+
              |  include/ |           |   config/     |
              | common_v3 |           | config_v3.php |
              | func.*    |           +---------------+
              +-----------+
                    |
              +-----v-----+
              |   MySQL   |
              | kistica_v3|
              +-----------+

SSL Client Certificate Flow:
+--------+    SSL Cert    +---------+    Verify    +----------+
| Browser| ------------> | Apache  | -----------> | webcert  |
+--------+               +---------+              | (authz)  |
                              |                   +----------+
                              v
                    $_SERVER['SSL_CLIENT_*']
```

## Key Entry Points

| Entry Point | Path | Auth Required | Description |
|-------------|------|---------------|-------------|
| Main Router | `pki/index.php` | SSL Cert | 역할별 포털 선택 |
| CA Dashboard | `pki/ca/index.php` | authz=ca | CA 관리 홈 |
| RA Dashboard | `pki/ra/index.php` | authz=ra | RA 관리 홈 |
| User Portal | `pki/subscriber/index.php` | authz=subscriber | 사용자 서비스 |
| Public Site | `pub/index.php` | None | 공개 정보 |

## Request Flow

### User Certificate Request
```
1. User Login (SSL Client Cert)
         |
         v
2. pki/subscriber/request_user_cert_v3.php
         |
         v
3. Generate CSR (Browser WebCrypto or Server-side)
         |
         v
4. Save to DB: INSERT INTO csr (...)
         |
         v
5. RA Review: pki/ra/subscribers_v3.php
         |
         v
6. CA Sign: pki/ca/cert_v3.php
         |
         v
7. Save Cert: INSERT INTO cert (...)
         |
         v
8. User Download: pki/subscriber/cert_v3.php
```

## Common Patterns

### Adding New Page (v3 Pattern)
```php
<?php
// 1. Include common module
include("../include/common_v3.php");
// or for pki pages:
include("common_v3.php");

// 2. Include header
include("head.php");
?>

<!-- 3. Page Content -->
<div class="content">
    <!-- Your HTML here -->
</div>

<?php
// 4. Include footer
include("tail.php");
?>
```

### Database Query Pattern
```php
// Using MySQLi (v3)
$conn = connectDB();
$query = "SELECT * FROM cert WHERE certid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $certid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    // Process row
}
$stmt->close();
```

### Error Handling Pattern
```php
// Using iError from func.misc.php
if ($error_condition) {
    iError("Error message here", true, false);
    // true = show back button
    // false = don't close window
}
```

### Redirect Pattern
```php
// Using Redirect from func.misc.php
Redirect("target_page.php?id=" . $id, true);
// true = use HTTP header redirect
```

## Database Models

### cert (Certificates)
```sql
certid      INT AUTO_INCREMENT PRIMARY KEY
cert        MEDIUMTEXT          -- PEM encoded certificate
serial      INT                 -- Certificate serial number
subject     CHAR(255)           -- Subject DN
notbefore   CHAR(30)            -- Valid from
notafter    CHAR(30)            -- Valid until
vfrom       DATETIME            -- Validity start
vuntil      DATETIME            -- Validity end
ctype       CHAR(100)           -- 'user' or 'host'
email       CHAR(100)           -- Contact email
idate       DATETIME            -- Issue date
csrid       INT                 -- Foreign key to csr
status      CHAR(100)           -- Certificate status
```

### csr (Certificate Signing Requests)
```sql
csrid       INT AUTO_INCREMENT PRIMARY KEY
csr         TEXT                -- PEM encoded CSR
forminfo    CHAR(255)           -- Form metadata
idate       DATETIME            -- Request date
status      CHAR(100)           -- 'pending', 'approved', 'rejected'
csrtype     CHAR(10)            -- 'user' or 'host'
email       CHAR(100)           -- Requester email
certid      INT                 -- Foreign key to cert (after issue)
```

### subscriber (Users)
```sql
id          INT AUTO_INCREMENT PRIMARY KEY
firstname   CHAR(100)
lastname    CHAR(100)
perid       CHAR(10)            -- Personal ID
gender      ENUM('M','F')
country     CHAR(100)
org         CHAR(100)           -- Organization
orgunit     CHAR(100)           -- Organization Unit
position    CHAR(100)
email       CHAR(100)
pin         CHAR(100)           -- PIN for verification
ra_cn       CHAR(100)           -- RA Common Name
memo        CHAR(200)
idate       DATETIME
```

### webcert (Web Access Certificates)
```sql
id          INT AUTO_INCREMENT PRIMARY KEY
subscid     INT                 -- Foreign key to subscriber
dn          CHAR(255)           -- Distinguished Name
cn          CHAR(255)           -- Common Name
email       CHAR(50)
authz       CHAR(10)            -- 'ca', 'ra', 'subscriber'
serial      INT                 -- Certificate serial
idate       DATETIME
udate       DATETIME
pin         CHAR(100)
```

## File Dependencies

### When modifying these files, check related files:

| Modified File | Check These Files |
|---------------|-------------------|
| `config/config_v3.php` | All `*_v3.php` files |
| `include/func.mysql.php` | All files using DB functions |
| `include/func.misc.php` | All files using utility functions |
| `pki/*/head.php` | Same folder's `*.php` files |
| `pki/*/tail.php` | Same folder's `*.php` files |
| `pki/*/style.css` | Same folder's HTML output |
| `db/*.sql` | `config/config_v3.php`, migration scripts |

## SSL Variables Reference
```php
$_SERVER['SSL_CLIENT_S_DN']      // Subject DN
$_SERVER['SSL_CLIENT_I_DN']      // Issuer DN
$_SERVER['SSL_CLIENT_M_SERIAL']  // Serial number
$_SERVER['SSL_CLIENT_V_START']   // Valid from
$_SERVER['SSL_CLIENT_V_END']     // Valid until
$_SERVER['SSL_CLIENT_VERIFY']    // Verification status
```
