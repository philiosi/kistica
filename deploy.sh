#!/bin/bash
#
# KISTI CA 배포 스크립트
# 사용법: ./deploy.sh [옵션]
#
# 옵션:
#   --dry-run    실제 전송 없이 미리보기
#   --prod       프로덕션 서버에 배포
#   --staging    스테이징 서버에 배포 (기본값)
#   --assets     assets 폴더만 배포
#   --pub        pub 폴더만 배포
#   --pki        pki 폴더만 배포
#

# ============================================
# 설정 - 환경에 맞게 수정하세요
# ============================================

# 스테이징 서버
STAGING_USER="username"
STAGING_HOST="staging.example.com"
STAGING_PATH="/var/www/html/kisti-ca"

# 프로덕션 서버
PROD_USER="root"
PROD_HOST="150.183.244.13"
PROD_PATH="/kistica/html/"

# SSH 포트 (기본값: 22)
SSH_PORT="2222"

# SSH 키 경로 (선택사항, 비워두면 기본 키 사용)
SSH_KEY=""

# 로컬 소스 디렉토리
LOCAL_PATH="$(cd "$(dirname "$0")" && pwd)"

# ============================================
# rsync 제외 목록
# ============================================
EXCLUDE_LIST=(
    # 개발/배포 파일
    ".git"
    ".gitignore"
    "deploy.sh"
    "deploy.bat"
    "CLAUDE.md"
    "*.md"

    # 설정 파일 (서버에서 직접 관리)
    "config/config_v3.php"
    "config/config.php"

    # 백업/임시 파일
    "*.bak"
    "*.bk"
    "*.rm"
    "*.tmp"
    "*.log"
    "*~"
    ".DS_Store"
    "Thumbs.db"

    # 삭제된 파일 디렉토리
    "_delete"
    "*/_delete"
    "*/_delete/*"

    # 디자인 원본
    "design"
    "design/*"

    # IDE/에디터 설정
    ".vscode"
    ".idea"
    "*.sublime-*"

    # 인증서/키 파일 (보안)
    "*.pem"
    "*.key"
    "*.crt"
    "*.p12"
    "*.pfx"
)

# ============================================
# 함수 정의
# ============================================

# 색상 출력
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# 사용법 출력
usage() {
    echo "사용법: $0 [옵션]"
    echo ""
    echo "옵션:"
    echo "  --dry-run    실제 전송 없이 변경 사항 미리보기"
    echo "  --prod       프로덕션 서버에 배포"
    echo "  --staging    스테이징 서버에 배포 (기본값)"
    echo "  --assets     assets 폴더만 배포"
    echo "  --pub        pub 폴더만 배포"
    echo "  --pki        pki 폴더만 배포"
    echo "  --include    include 폴더만 배포"
    echo "  --help       이 도움말 표시"
    echo ""
    echo "예시:"
    echo "  $0 --dry-run           # 스테이징 미리보기"
    echo "  $0 --prod --dry-run    # 프로덕션 미리보기"
    echo "  $0 --prod              # 프로덕션 배포"
    echo "  $0 --assets            # assets만 스테이징에 배포"
}

# exclude 옵션 생성
build_exclude_opts() {
    local opts=""
    for item in "${EXCLUDE_LIST[@]}"; do
        opts="$opts --exclude='$item'"
    done
    echo "$opts"
}

# rsync 실행
do_rsync() {
    local src="$1"
    local dest="$2"
    local dry_run="$3"

    # 기본 rsync 옵션
    local opts="-avz --progress --stats"

    # dry-run 모드
    if [ "$dry_run" = "true" ]; then
        opts="$opts --dry-run"
        log_warning "DRY-RUN 모드: 실제 파일은 전송되지 않습니다"
    fi

    # 삭제 옵션 (서버에서 로컬에 없는 파일 삭제)
    # 주의: 필요시 주석 해제
    # opts="$opts --delete"

    # SSH 옵션
    local ssh_opts="-p $SSH_PORT"
    if [ -n "$SSH_KEY" ]; then
        ssh_opts="$ssh_opts -i $SSH_KEY"
    fi
    opts="$opts -e \"ssh $ssh_opts\""

    # exclude 옵션 추가
    local exclude_opts=$(build_exclude_opts)

    # 전체 명령 구성
    local cmd="rsync $opts $exclude_opts \"$src\" \"$dest\""

    log_info "실행 명령:"
    echo "$cmd"
    echo ""

    # 실행
    eval $cmd

    return $?
}

# ============================================
# 메인 로직
# ============================================

# 기본값
TARGET="staging"
DRY_RUN="false"
FOLDER=""

# 인자 파싱
while [[ $# -gt 0 ]]; do
    case $1 in
        --dry-run)
            DRY_RUN="true"
            shift
            ;;
        --prod)
            TARGET="prod"
            shift
            ;;
        --staging)
            TARGET="staging"
            shift
            ;;
        --assets)
            FOLDER="assets"
            shift
            ;;
        --pub)
            FOLDER="pub"
            shift
            ;;
        --pki)
            FOLDER="pki"
            shift
            ;;
        --include)
            FOLDER="include"
            shift
            ;;
        --help|-h)
            usage
            exit 0
            ;;
        *)
            log_error "알 수 없는 옵션: $1"
            usage
            exit 1
            ;;
    esac
done

# 타겟 서버 설정
if [ "$TARGET" = "prod" ]; then
    REMOTE_USER="$PROD_USER"
    REMOTE_HOST="$PROD_HOST"
    REMOTE_PATH="$PROD_PATH"
    log_warning "프로덕션 서버에 배포합니다!"
else
    REMOTE_USER="$STAGING_USER"
    REMOTE_HOST="$STAGING_HOST"
    REMOTE_PATH="$STAGING_PATH"
    log_info "스테이징 서버에 배포합니다"
fi

# 소스/대상 경로 설정
if [ -n "$FOLDER" ]; then
    SRC_PATH="$LOCAL_PATH/$FOLDER/"
    DEST_PATH="$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/$FOLDER/"
    log_info "폴더 제한: $FOLDER"
else
    SRC_PATH="$LOCAL_PATH/"
    DEST_PATH="$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/"
fi

echo ""
echo "========================================"
echo "KISTI CA 배포"
echo "========================================"
echo "소스: $SRC_PATH"
echo "대상: $DEST_PATH"
echo "모드: $([ "$DRY_RUN" = "true" ] && echo "미리보기 (dry-run)" || echo "실제 배포")"
echo "========================================"
echo ""

# 프로덕션 배포 시 확인
if [ "$TARGET" = "prod" ] && [ "$DRY_RUN" = "false" ]; then
    read -p "정말 프로덕션 서버에 배포하시겠습니까? (yes/no): " confirm
    if [ "$confirm" != "yes" ]; then
        log_info "배포가 취소되었습니다."
        exit 0
    fi
fi

# rsync 실행
do_rsync "$SRC_PATH" "$DEST_PATH" "$DRY_RUN"

if [ $? -eq 0 ]; then
    log_success "배포 완료!"
else
    log_error "배포 중 오류가 발생했습니다."
    exit 1
fi
