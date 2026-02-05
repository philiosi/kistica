# Add Mistake Pattern

실수 패턴을 수동으로 기록합니다.

## 사용법
```
/add-mistake <설명>
```

## 실행할 작업

1. 현재 시간과 함께 실수 패턴을 `.claude/cache/mistake-candidates.jsonl`에 기록
2. 기록 형식:
```json
{"timestamp":"ISO8601","type":"manual","description":"<설명>","context":"<현재 작업 컨텍스트>"}
```

## 예시
```
/add-mistake DB 쿼리에서 prepared statement 대신 직접 문자열 연결 사용
/add-mistake head.php 수정 후 다른 파일 테스트 안함
/add-mistake config 파일 경로 하드코딩
```

## 기록된 실수 패턴 확인
```bash
cat .claude/cache/mistake-candidates.jsonl | jq .
```

## 목적
- 반복되는 실수 패턴 추적
- 코드 리뷰 시 참고
- 향후 자동 경고 규칙 생성에 활용
