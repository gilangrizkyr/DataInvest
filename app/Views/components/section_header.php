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
$module = $module ?? 'Module';
?>

<style>
    @keyframes headerReveal {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .header-animate {
        animation: headerReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .glass-header {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="mb-8 p-6 rounded-2xl glass-header border-l-4 border-l-primary-600 header-animate flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="space-y-1">
        <div class="flex items-center gap-4">
            <?php if($icon): ?>
                <div class="w-12 h-12 bg-white text-primary-600 rounded-2xl flex items-center justify-center shadow-sm border border-slate-100">
                    <i class="<?= htmlspecialchars($icon) ?> text-lg"></i>
                </div>
            <?php endif; ?>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-none mb-2"><?= htmlspecialchars($title) ?></h1>
                <?php if($description): ?>
                    <p class="text-[13px] font-medium text-slate-500 opacity-80"><?= htmlspecialchars($description) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Action Section -->
    <?php if($action_text): ?>
        <div class="flex items-center gap-3">
            <?php if($action_url): ?>
                <a href="<?= base_url($action_url) ?>" class="px-6 py-3 bg-slate-900 hover:bg-black text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-plus"></i> <?= htmlspecialchars($action_text) ?>
                </a>
            <?php elseif($action_onclick): ?>
                <button onclick="<?= htmlspecialchars($action_onclick) ?>" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-primary-100 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-plus"></i> <?= htmlspecialchars($action_text) ?>
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>