<?php
/**
 * Button Component
 * 
 * Parameters:
 * - text: Button text (required)
 * - href: Link href (if empty, renders as <button>)
 * - class: Additional CSS classes
 * - type: button type (button, submit, reset) - default: submit
 * - variant: primary, secondary, success, danger, warning, info - default: primary
 * - size: sm, md, lg - default: md
 * - icon: Font Awesome icon class (e.g., 'fas fa-save')
 * - disabled: boolean
 * - loading: boolean
 * - onclick: onclick handler
 * - attributes: Array of additional HTML attributes
 */

$href = $href ?? null;
$text = $text ?? 'Button';
$type = $type ?? 'submit';
$variant = $variant ?? 'primary';
$size = $size ?? 'md';
$icon = $icon ?? null;
$disabled = $disabled ?? false;
$loading = $loading ?? false;
$onclick = $onclick ?? null;
$custom_class = $class ?? '';
$attributes = $attributes ?? [];

$classes = ['btn', 'btn-' . $variant];

if ($size !== 'md') {
    $classes[] = 'btn-' . $size;
}

if ($custom_class) {
    $classes[] = $custom_class;
}

if ($loading) {
    $disabled = true;
}

$class_string = implode(' ', $classes);
$attrs = implode(' ', array_map(fn($k, $v) => $k . '="' . htmlspecialchars($v) . '"', array_keys($attributes), $attributes));
if ($attrs) $attrs = ' ' . $attrs;

if ($href):
?>
    <a 
        href="<?= base_url($href) ?>" 
        class="<?= $class_string ?>"
        <?= $onclick ? 'onclick="' . htmlspecialchars($onclick) . '"' : '' ?>
        <?= $disabled ? 'style="pointer-events: none; opacity: 0.6;"' : '' ?>
        <?= $attrs ?>
    >
        <?php if($icon): ?>
            <i class="<?= htmlspecialchars($icon) ?>"></i>
        <?php endif; ?>
        <span><?php if($loading): ?><i class="fas fa-spinner animate-spin mr-2"></i><?php endif; ?><?= htmlspecialchars($text) ?></span>
    </a>
<?php else: ?>
    <button 
        type="<?= htmlspecialchars($type) ?>" 
        class="<?= $class_string ?>"
        <?= $disabled ? 'disabled' : '' ?>
        <?= $onclick ? 'onclick="' . htmlspecialchars($onclick) . '"' : '' ?>
        <?= $attrs ?>
    >
        <?php if($icon): ?>
            <i class="<?= htmlspecialchars($icon) ?>"></i>
        <?php endif; ?>
        <span><?php if($loading): ?><i class="fas fa-spinner animate-spin mr-2"></i><?php endif; ?><?= htmlspecialchars($text) ?></span>
    </button>
<?php endif; ?>
