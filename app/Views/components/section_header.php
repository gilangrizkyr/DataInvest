<?php
/**
 * Section Header Component
 * 
 * Parameters:
 * - title: Section title
 * - description: Section description
 * - icon: Font Awesome icon class
 * - action_text: Action button text
 * - action_url: Action button URL
 * - action_onclick: Action button onclick handler
 */

$title = $title ?? '';
$description = $description ?? '';
$icon = $icon ?? '';
$action_text = $action_text ?? '';
$action_url = $action_url ?? '';
$action_onclick = $action_onclick ?? '';
?>

<div class="mb-8">
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <?php if($icon): ?>
                    <i class="<?= htmlspecialchars($icon) ?> text-2xl text-primary-600"></i>
                <?php endif; ?>
                <h2 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h2>
            </div>
            <?php if($description): ?>
                <p class="text-gray-600"><?= htmlspecialchars($description) ?></p>
            <?php endif; ?>
        </div>
        <?php if($action_text): ?>
            <div>
                <?php if($action_url): ?>
                    <a href="<?= base_url($action_url) ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i><?= htmlspecialchars($action_text) ?>
                    </a>
                <?php elseif($action_onclick): ?>
                    <button onclick="<?= htmlspecialchars($action_onclick) ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i><?= htmlspecialchars($action_text) ?>
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <hr class="border-gray-200 mt-4">
</div>
