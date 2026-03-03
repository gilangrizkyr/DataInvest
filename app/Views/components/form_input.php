<?php
/**
 * Form Input Component
 * 
 * Parameters:
 * - name: Input name (required)
 * - label: Label text
 * - type: input, email, password, number, date, select, textarea, checkbox, radio
 * - value: Input value
 * - placeholder: Placeholder text
 * - required: boolean
 * - disabled: boolean
 * - readonly: boolean
 * - pattern: HTML5 pattern
 * - error: error message
 * - options: array for select/radio/checkbox (key => value)
 * - class: Additional CSS classes
 * - attributes: Additional HTML attributes
 */

$name = $name ?? '';
$label = $label ?? '';
$type = $type ?? 'text';
$value = $value ?? '';
$placeholder = $placeholder ?? '';
$required = $required ?? false;
$disabled = $disabled ?? false;
$readonly = $readonly ?? false;
$pattern = $pattern ?? '';
$error = $error ?? '';
$options = $options ?? [];
$custom_class = $class ?? '';
$attributes = $attributes ?? [];

$field_id = str_replace(['[', ']'], ['_', ''], $name);
$input_classes = ['form-control'];
if ($error) $input_classes[] = 'is-invalid';
if ($custom_class) $input_classes[] = $custom_class;

$class_string = implode(' ', $input_classes);
$attrs = implode(' ', array_map(fn($k, $v) => $k . '="' . htmlspecialchars($v) . '"', array_keys($attributes), $attributes));
if ($attrs) $attrs = ' ' . $attrs;
?>

<div class="form-group">
    <?php if($label): ?>
        <label for="<?= $field_id ?>" class="form-label">
            <?= htmlspecialchars($label) ?>
            <?php if($required): ?><span class="required">*</span><?php endif; ?>
        </label>
    <?php endif; ?>

    <?php if($type === 'textarea'): ?>
        <textarea 
            id="<?= $field_id ?>" 
            name="<?= htmlspecialchars($name) ?>" 
            class="<?= $class_string ?>"
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            <?= $required ? 'required' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>
            <?= $readonly ? 'readonly' : '' ?>
            <?= $attrs ?>
        ><?= htmlspecialchars($value) ?></textarea>

    <?php elseif($type === 'select'): ?>
        <select 
            id="<?= $field_id ?>" 
            name="<?= htmlspecialchars($name) ?>" 
            class="<?= $class_string ?>"
            <?= $required ? 'required' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>
            <?= $attrs ?>
        >
            <option value="">-- Select --</option>
            <?php foreach($options as $opt_value => $opt_label): ?>
                <option value="<?= htmlspecialchars($opt_value) ?>" <?= $value == $opt_value ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opt_label) ?>
                </option>
            <?php endforeach; ?>
        </select>

    <?php elseif($type === 'checkbox'): ?>
        <div class="form-check">
            <input 
                type="checkbox" 
                id="<?= $field_id ?>" 
                name="<?= htmlspecialchars($name) ?>" 
                value="<?= htmlspecialchars($value) ?>"
                class="form-check-input"
                <?= $value ? 'checked' : '' ?>
                <?= $required ? 'required' : '' ?>
                <?= $disabled ? 'disabled' : '' ?>
                <?= $attrs ?>
            >
            <?php if($label): ?>
                <label class="form-check-label" for="<?= $field_id ?>">
                    <?= htmlspecialchars($label) ?>
                </label>
            <?php endif; ?>
        </div>

    <?php elseif($type === 'radio'): ?>
        <?php foreach($options as $opt_value => $opt_label): ?>
            <div class="form-check">
                <input 
                    type="radio" 
                    id="<?= $field_id ?>_<?= htmlspecialchars($opt_value) ?>" 
                    name="<?= htmlspecialchars($name) ?>" 
                    value="<?= htmlspecialchars($opt_value) ?>"
                    class="form-check-input"
                    <?= $value == $opt_value ? 'checked' : '' ?>
                    <?= $required ? 'required' : '' ?>
                    <?= $disabled ? 'disabled' : '' ?>
                    <?= $attrs ?>
                >
                <label class="form-check-label" for="<?= $field_id ?>_<?= htmlspecialchars($opt_value) ?>">
                    <?= htmlspecialchars($opt_label) ?>
                </label>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <input 
            type="<?= htmlspecialchars($type) ?>" 
            id="<?= $field_id ?>" 
            name="<?= htmlspecialchars($name) ?>" 
            class="<?= $class_string ?>"
            value="<?= htmlspecialchars($value) ?>"
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            <?= $required ? 'required' : '' ?>
            <?= $disabled ? 'disabled' : '' ?>
            <?= $readonly ? 'readonly' : '' ?>
            <?= $pattern ? 'pattern="' . htmlspecialchars($pattern) . '"' : '' ?>
            <?= $attrs ?>
        >
    <?php endif; ?>

    <?php if($error): ?>
        <div class="form-text error">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
</div>
