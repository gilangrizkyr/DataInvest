<?php
// Flash messages / alerts component
// Handle both string and array flashdata
$processFlashMessage = function($message) {
    if (is_array($message)) {
        return implode(', ', $message);
    }
    return (string)$message;
};
?>
<div class="container mx-auto px-4 pt-6">
    <?php if($success = session()->getFlashdata('success')): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3 animate-fade-in" role="alert" x-data="{ show: true }" x-show="show">
            <i class="fas fa-check-circle text-green-600 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <p class="text-green-800 font-medium">Success</p>
                <p class="text-green-700 text-sm"><?= htmlspecialchars($processFlashMessage($success)) ?></p>
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if($error = session()->getFlashdata('error')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3 animate-fade-in" role="alert" x-data="{ show: true }" x-show="show">
            <i class="fas fa-exclamation-circle text-red-600 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <p class="text-red-800 font-medium">Error</p>
                <p class="text-red-700 text-sm"><?= htmlspecialchars($processFlashMessage($error)) ?></p>
            </div>
            <button @click="show = false" class="text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if($warning = session()->getFlashdata('warning')): ?>
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start gap-3 animate-fade-in" role="alert" x-data="{ show: true }" x-show="show">
            <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <p class="text-yellow-800 font-medium">Warning</p>
                <p class="text-yellow-700 text-sm"><?= htmlspecialchars($processFlashMessage($warning)) ?></p>
            </div>
            <button @click="show = false" class="text-yellow-600 hover:text-yellow-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if($info = session()->getFlashdata('info')): ?>
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-3 animate-fade-in" role="alert" x-data="{ show: true }" x-show="show">
            <i class="fas fa-info-circle text-blue-600 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <p class="text-blue-800 font-medium">Information</p>
                <p class="text-blue-700 text-sm"><?= htmlspecialchars($processFlashMessage($info)) ?></p>
            </div>
            <button @click="show = false" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if(isset($errors) && is_array($errors) && count($errors) > 0): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg" role="alert">
            <p class="text-red-800 font-medium mb-2">Validation Errors:</p>
            <ul class="list-disc list-inside space-y-1">
                <?php foreach($errors as $error): ?>
                    <li class="text-red-700 text-sm"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
</style>
