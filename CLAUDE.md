# KISTI CA - PKI Certificate Management System

## Quick Overview
KISTI Grid 인증기관(CA) 시스템으로, X.509 사용자/호스트 인증서를 발급하고 관리하는 PHP 기반 PKI 웹 애플리케이션입니다.

## Tech Stack
- **Backend**: PHP 8.x (MySQLi)
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Security**: OpenSSL, SSL/TLS Client Certificate Authentication

## Core Flows

### 주요 기능별 핵심 파일

| 기능 | 파일 경로 | 설명 |
|------|----------|------|
| **메인 진입점** | `pki/index.php` | 역할별 포털 라우팅 (CA/RA/Subscriber) |
| **CA 관리** | `pki/ca/index.php` | CA 관리자 대시보드 |
| **CA 인증서 관리** | `pki/ca/cert_v3.php` | 발급된 인증서 조회/수정 |
| **RA 관리** | `pki/ra/index.php` | RA 관리자 대시보드 |
| **RA 사용자 등록** | `pki/ra/newsubscriber_v3.php` | 신규 사용자 등록 |
| **사용자 포털** | `pki/subscriber/index.php` | 사용자 서비스 홈 |
| **사용자 인증서 요청** | `pki/subscriber/request_user_cert_v3.php` | CSR 생성 및 인증서 요청 |
| **호스트 인증서 요청** | `pki/subscriber/request_host_cert_v3.php` | 서버 인증서 요청 |
| **공개 포털** | `pub/index.php` | 공개 웹사이트 메인 |

### 공통 모듈

| 파일 | 설명 |
|------|------|
| `config/config_v3.php` | DB 설정 (host, user, password, dbname) |
| `include/common_v3.php` | 공통 초기화 (DB 연결, 세션) |
| `include/func.mysql.php` | DB 함수 (connectDB, DBQuery, DBFetchRow) |
| `include/func.misc.php` | 유틸리티 (iError, Redirect, Pager_s) |

### 템플릿 파일

| 역할 | Header | Footer |
|------|--------|--------|
| CA | `pki/ca/head.php` | `pki/ca/tail.php` |
| RA | `pki/ra/head.php` | `pki/ra/tail.php` |
| Subscriber | `pki/subscriber/head.php` | `pki/subscriber/tail.php` |
| Public | `pub/head.php` | `pub/tail.php` |

## Database Tables

| 테이블 | 용도 |
|--------|------|
| `cert` | 발급된 인증서 저장 |
| `csr` | 인증서 서명 요청 |
| `subscriber` | 등록된 사용자 정보 |
| `webcert` | 웹 접근 클라이언트 인증서 (권한 관리) |
| `counter` | ID 카운터 |

## Development Commands

```bash
# 로컬 개발 서버 (XAMPP)
# Apache 시작: XAMPP Control Panel에서 Apache Start

# 데이터베이스 설정
mysql -u root -p < db/full_schema.sql
mysql -u root -p < db/local_setup.sql

# PHP 문법 체크
php -l <filename.php>

# 전체 PHP 파일 문법 체크
find . -name "*.php" -exec php -l {} \;
```

## Permissions

### ALLOWED
- PHP 파일 수정 (pki/, pub/, include/)
- CSS/JS 수정 (style.css, script.js)
- SQL 스키마 수정 (db/)
- 설정 파일 수정 (config/)

### FORBIDDEN
- config/config_v3.php의 실제 비밀번호 커밋 금지
- pub/certificates/ 내 실제 인증서 파일 수정 금지
- pub/CRL/ 내 CRL 파일 직접 수정 금지
- OpenSSL 개인키 파일 접근 금지

## File Naming Convention
- `*_v3.php` - 현재 버전 (MySQLi 사용)
- `*.php` (v3 없음) - 레거시 버전 (deprecated mysql_* 사용)
- `*.php.bk`, `*.php.rm` - 백업/제거된 파일

## Important Notes
- SSL 클라이언트 인증서 기반 인증 사용 (`$_SERVER['SSL_CLIENT_*']`)
- 권한 확인: `webcert.authz` 필드 ('ca', 'ra', 'subscriber')
- 문자 인코딩: EUC-KR (일부), UTF-8 (신규)
