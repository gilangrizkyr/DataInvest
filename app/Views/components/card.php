<?php
/**
 * Card Component
 * 
 * Parameters:
 * - title: Card title
 * - subtitle: Card subtitle
 * - icon: Font Awesome icon class
 * - footer: Footer content
 * - class: Additional CSS classes
 * - no_padding: boolean (removes padding)
 */

$title = $title ?? '';
$subtitle = $subtitle ?? '';
$icon = $icon ?? '';
$footer = $footer ?? '';
$custom_class = $class ?? '';
$no_padding = $no_padding ?? false;

$card_classes = ['card'];
if ($custom_class) $card_classes[] = $custom_class;
$class_string = implode(' ', $card_classes);

$content_classes = ['card-content'];
if (!$no_padding) $content_classes[] = 'p-6';
$content_class_string = implode(' ', $content_classes);
?>

<div class="<?= $class_string ?>">
    <?php if($title || $icon): ?>
        <div class="card-header border-b border-gray-200 pb-4 mb-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <?php if($icon): ?>
                    <div class="text-2xl text-primary-600">
                        <i class="<?= htmlspecialchars($icon) ?>"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <?php if($title): ?>
                        <h3 class="text-lg font-bold text-gray-900">
                            <?= htmlspecialchars($title) ?>
                        </h3>
                    <?php endif; ?>
                    <?php if($subtitle): ?>
                        <p class="text-sm text-gray-600">
                            <?= htmlspecialchars($subtitle) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="<?= $content_class_string ?>">
        <?= $this->renderSection('content') ?: $content ?? '' ?>
    </div>

    <?php if($footer): ?>
        <div class="card-footer border-t border-gray-200 pt-4 mt-4 flex justify-end gap-2">
            <?= $footer ?>
        </div>
    <?php endif; ?>
</div>
