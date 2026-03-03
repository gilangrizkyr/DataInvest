<?php
/**
 * Badge Component
 * 
 * Parameters:
 * - text: Badge text (required)
 * - variant: primary, success, danger, warning, info - default: primary
 * - icon: Font Awesome icon class
 * - class: Additional CSS classes
 */

$text = $text ?? '';
$variant = $variant ?? 'primary';
$icon = $icon ?? '';
$custom_class = $class ?? '';

$classes = ['badge', 'badge-' . $variant];
if ($custom_class) $classes[] = $custom_class;
$class_string = implode(' ', $classes);
?>

<span class="<?= $class_string ?>">
    <?php if($icon): ?>
        <i class="<?= htmlspecialchars($icon) ?> mr-1"></i>
    <?php endif; ?>
    <?= htmlspecialchars($text) ?>
</span>
