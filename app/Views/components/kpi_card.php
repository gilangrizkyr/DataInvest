<?php
/**
 * KPI Card Component
 * 
 * Parameters:
 * - label: KPI label
 * - value: KPI value (number or text)
 * - icon: Font Awesome icon class
 * - color: primary, success, danger, warning, info - default: primary
 * - subtitle: Small subtitle text
 * - trend: Badge text for trend (e.g., "+5% from last month")
 * - trend_positive: boolean (green for positive, red for negative)
 */

$label = $label ?? '';
$value = $value ?? '0';
$icon = $icon ?? '';
$color = $color ?? 'primary';
$subtitle = $subtitle ?? '';
$trend = $trend ?? '';
$trend_positive = $trend_positive ?? true;

$color_map = [
    'primary' => 'bg-blue-50 text-blue-600 border-blue-100',
    'success' => 'bg-green-50 text-green-600 border-green-100',
    'danger' => 'bg-red-50 text-red-600 border-red-100',
    'warning' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
    'info' => 'bg-purple-50 text-purple-600 border-purple-100',
];

$color_class = $color_map[$color] ?? $color_map['primary'];
$icon_bg_map = [
    'primary' => 'bg-blue-100',
    'success' => 'bg-green-100',
    'danger' => 'bg-red-100',
    'warning' => 'bg-yellow-100',
    'info' => 'bg-purple-100',
];
$icon_bg = $icon_bg_map[$color] ?? $icon_bg_map['primary'];
?>

<div class="card border-l-4 <?= $color_class ?>">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 mb-1"><?= htmlspecialchars($label) ?></p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-bold text-gray-900">
                    <?= htmlspecialchars($value) ?>
                </h3>
                <?php if($trend): ?>
                    <span class="text-xs px-2 py-1 rounded <?= $trend_positive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                        <?= htmlspecialchars($trend) ?>
                    </span>
                <?php endif; ?>
            </div>
            <?php if($subtitle): ?>
                <p class="text-xs text-gray-500 mt-2"><?= htmlspecialchars($subtitle) ?></p>
            <?php endif; ?>
        </div>
        <?php if($icon): ?>
            <div class="<?= $icon_bg ?> p-3 rounded-lg">
                <i class="<?= htmlspecialchars($icon) ?> text-lg"></i>
            </div>
        <?php endif; ?>
    </div>
</div>
