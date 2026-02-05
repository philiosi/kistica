# PHP Syntax Check

프로젝트의 PHP 파일 문법을 검사합니다.

## 실행할 작업

1. 변경된 PHP 파일이 있으면 해당 파일만 검사
2. 인자가 없으면 주요 디렉토리의 PHP 파일 전체 검사

```bash
# 특정 파일 검사
php -l $ARGUMENTS

# 전체 검사 (인자 없을 때)
find pki pub include config -name "*.php" -exec php -l {} \; 2>&1 | grep -v "No syntax errors"
```

## 검사 대상 디렉토리
- `pki/` - PKI 모듈 (ca, ra, subscriber)
- `pub/` - 공개 포털
- `include/` - 공통 함수
- `config/` - 설정 파일

## 출력
- 문법 오류가 있는 파일만 표시
- 오류 없으면 "All PHP files passed syntax check" 출력
