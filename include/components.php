<?php
/**
 * KISTI CA - UI 컴포넌트 헬퍼 함수
 * Tailwind CSS 기반 재사용 가능한 UI 컴포넌트
 * PHP 5.4+ 호환
 */

/**
 * 상태 배지 렌더링
 * @param string $status - success, pending, failed, warning
 * @param string $text - 표시할 텍스트 (선택적, 없으면 상태명 사용)
 */
function StatusBadge($status, $text = null) {
  $statusConfig = array(
    'success' => array('bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Success'),
    'pending' => array('bg' => 'bg-blue-100', 'text' => 'text-primary', 'label' => 'Pending'),
    'failed'  => array('bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Failed'),
    'warning' => array('bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Warning'),
    'expired' => array('bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => 'Expired'),
    'revoked' => array('bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Revoked'),
    'valid'   => array('bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Valid'),
  );

  $config = isset($statusConfig[$status]) ? $statusConfig[$status] : $statusConfig['pending'];
  $displayText = isset($text) ? $text : $config['label'];

  return "<span class=\"status-badge {$config['bg']} {$config['text']}\">{$displayText}</span>";
}

/**
 * 리스트 아이템 렌더링
 * @param array $options - 아이템 옵션
 *   - icon: Material Symbols 아이콘명
 *   - iconColor: success, pending, failed, warning, info
 *   - title: 제목
 *   - subtitle: 부제목 (선택적)
 *   - meta: 메타 정보 (선택적)
 *   - status: 상태 배지 (선택적)
 *   - href: 링크 URL (선택적)
 */
function ListItem($options) {
  $icon = isset($options['icon']) ? $options['icon'] : 'article';
  $iconColor = isset($options['iconColor']) ? $options['iconColor'] : 'info';
  $title = htmlspecialchars(isset($options['title']) ? $options['title'] : '');
  $subtitle = isset($options['subtitle']) ? htmlspecialchars($options['subtitle']) : '';
  $meta = isset($options['meta']) ? htmlspecialchars($options['meta']) : '';
  $status = isset($options['status']) ? $options['status'] : null;
  $href = isset($options['href']) ? $options['href'] : null;

  $iconColors = array(
    'success' => 'bg-green-500',
    'pending' => 'bg-primary',
    'failed'  => 'bg-red-500',
    'warning' => 'bg-amber-500',
    'info'    => 'bg-slate-400',
  );

  $bgColor = isset($iconColors[$iconColor]) ? $iconColors[$iconColor] : $iconColors['info'];
  $dataHref = $href ? "data-href=\"{$href}\"" : '';

  $html = <<<HTML
<div class="list-item" {$dataHref}>
  <div class="list-item-icon {$bgColor}">
    <span class="material-symbols-outlined">{$icon}</span>
  </div>
  <div class="flex-1 min-w-0">
    <p class="text-slate-900 text-base font-semibold leading-normal truncate">{$title}</p>
HTML;

  if ($subtitle) {
    $html .= "<p class=\"text-slate-500 text-sm font-normal leading-normal truncate\">{$subtitle}</p>";
  }

  if ($meta) {
    $html .= "<p class=\"text-slate-400 text-xs mt-0.5\">{$meta}</p>";
  }

  $html .= '</div>';

  if ($status) {
    $html .= '<div class="shrink-0 flex items-center gap-2">';
    $statusType = isset($status['type']) ? $status['type'] : 'pending';
    $statusText = isset($status['text']) ? $status['text'] : null;
    $html .= StatusBadge($statusType, $statusText);
    $html .= '<span class="material-symbols-outlined text-slate-300">chevron_right</span>';
    $html .= '</div>';
  } else {
    $html .= '<span class="material-symbols-outlined text-slate-300">chevron_right</span>';
  }

  $html .= '</div>';

  return $html;
}

/**
 * 섹션 레이블 (날짜별 그룹 등)
 */
function SectionLabel($text) {
  $text = htmlspecialchars($text);
  return "<p class=\"section-label\">{$text}</p>";
}

/**
 * 공지 박스
 * @param string $type - info, warning, error, success
 * @param string $message - 메시지 내용 (HTML 허용)
 */
function NoticeBox($type, $message) {
  return "<div class=\"notice-box {$type}\">{$message}</div>";
}

/**
 * 검색 바
 * @param string $placeholder - 플레이스홀더 텍스트
 * @param string $value - 현재 값
 * @param string $name - 입력 필드 이름
 */
function SearchBar($placeholder = 'Search...', $value = '', $name = 'search') {
  $placeholder = htmlspecialchars($placeholder);
  $value = htmlspecialchars($value);
  $name = htmlspecialchars($name);

  return <<<HTML
<div class="search-bar">
  <div class="search-bar-icon">
    <span class="material-symbols-outlined">search</span>
  </div>
  <input type="text" name="{$name}" placeholder="{$placeholder}" value="{$value}"
         class="flex-1 bg-transparent border-none outline-none">
</div>
HTML;
}

/**
 * 필터 칩
 * @param array $filters - ['value' => 'label', ...]
 * @param string $active - 현재 활성화된 필터 값
 */
function FilterChips($filters, $active = 'all') {
  $html = '<div class="flex gap-3 overflow-x-auto no-scrollbar py-2">';

  foreach ($filters as $value => $label) {
    $isActive = ($value === $active) ? 'active' : '';
    $html .= "<button class=\"filter-chip {$isActive}\" data-filter=\"{$value}\">";
    $html .= htmlspecialchars($label);
    $html .= '</button>';
  }

  $html .= '</div>';
  return $html;
}

/**
 * 데이터 카드 (테이블 대체용)
 * @param array $rows - [['label' => '', 'value' => ''], ...]
 */
function DataCard($rows) {
  $html = '<div class="data-card">';

  foreach ($rows as $row) {
    $label = htmlspecialchars(isset($row['label']) ? $row['label'] : '');
    $value = isset($row['value']) ? $row['value'] : '';  // HTML 허용

    $html .= <<<HTML
<div class="data-card-row">
  <span class="data-card-label">{$label}</span>
  <span class="data-card-value">{$value}</span>
</div>
HTML;
  }

  $html .= '</div>';
  return $html;
}

/**
 * 버튼 렌더링
 * @param string $text - 버튼 텍스트
 * @param string $type - primary, secondary, danger
 * @param array $attrs - 추가 속성 ['onclick' => '', 'disabled' => true, ...]
 */
function Button($text, $type = 'primary', $attrs = array()) {
  $text = htmlspecialchars($text);
  $class = "btn btn-{$type}";

  $attrStr = '';
  foreach ($attrs as $key => $val) {
    if ($val === true) {
      $attrStr .= " {$key}";
    } elseif ($val !== false) {
      $attrStr .= " {$key}=\"" . htmlspecialchars($val) . "\"";
    }
  }

  return "<button class=\"{$class}\"{$attrStr}>{$text}</button>";
}

/**
 * 페이지 헤더 (ParagraphTitle 대체)
 * @param string $title - 페이지 제목
 * @param string $icon - Material Symbols 아이콘 (선택적)
 */
function PageHeader($title, $icon = null) {
  $title = htmlspecialchars($title);

  $html = '<div class="page-header">';
  if ($icon) {
    $html .= "<span class=\"material-symbols-outlined text-primary\">{$icon}</span>";
  }
  $html .= "<h1>{$title}</h1>";
  $html .= '</div>';

  return $html;
}

/**
 * 빈 상태 표시
 * @param string $message - 메시지
 * @param string $icon - 아이콘명
 */
function EmptyState($message, $icon = 'inbox') {
  $message = htmlspecialchars($message);

  return <<<HTML
<div class="flex flex-col items-center justify-center py-12 text-slate-400">
  <span class="material-symbols-outlined text-5xl mb-4">{$icon}</span>
  <p class="text-sm">{$message}</p>
</div>
HTML;
}

/**
 * 인증서 상태에 따른 아이콘/색상 반환
 */
function getCertStatusInfo($status) {
  $statusMap = array(
    'valid'   => array('icon' => 'verified', 'color' => 'success', 'label' => 'Valid'),
    'expired' => array('icon' => 'schedule', 'color' => 'warning', 'label' => 'Expired'),
    'revoked' => array('icon' => 'gpp_maybe', 'color' => 'failed', 'label' => 'Revoked'),
    'pending' => array('icon' => 'sync', 'color' => 'pending', 'label' => 'Pending'),
  );

  return isset($statusMap[$status]) ? $statusMap[$status] : $statusMap['pending'];
}

/**
 * CSR 상태에 따른 아이콘/색상 반환
 */
function getCsrStatusInfo($status) {
  $statusMap = array(
    'pending'  => array('icon' => 'pending_actions', 'color' => 'pending', 'label' => 'Pending'),
    'approved' => array('icon' => 'task_alt', 'color' => 'success', 'label' => 'Approved'),
    'rejected' => array('icon' => 'cancel', 'color' => 'failed', 'label' => 'Rejected'),
    'issued'   => array('icon' => 'verified', 'color' => 'success', 'label' => 'Issued'),
  );

  return isset($statusMap[$status]) ? $statusMap[$status] : $statusMap['pending'];
}
?>
