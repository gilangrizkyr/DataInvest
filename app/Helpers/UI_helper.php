<?php
/**
 * Application Helpers
 * Custom helper functions for views and controllers
 */

/**
 * Get status badge HTML
 * @param string $status Status name
 * @return string HTML badge
 */
if (!function_exists('status_badge')) {
    function status_badge($status) {
        $variants = [
            'completed' => ['success', 'fas fa-check-circle', 'Completed'],
            'processing' => ['warning', 'fas fa-hourglass-half', 'Processing'],
            'pending' => ['warning', 'fas fa-clock', 'Pending'],
            'error' => ['danger', 'fas fa-exclamation-circle', 'Error'],
            'active' => ['success', 'fas fa-check-circle', 'Active'],
            'inactive' => ['danger', 'fas fa-times-circle', 'Inactive'],
        ];
        
        $badge = $variants[$status] ?? ['info', 'fas fa-circle', $status];
        
        return view('components/badge', [
            'text' => $badge[2],
            'variant' => $badge[0],
            'icon' => $badge[1]
        ]);
    }
}

/**
 * Format date in Indonesian locale
 * @param string|DateTime $date Date to format
 * @return string Formatted date
 */
if (!function_exists('format_date_id')) {
    function format_date_id($date) {
        if (!$date) return '-';
        if (is_string($date)) $date = new DateTime($date);
        
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        $month = $months[(int)$date->format('m') - 1];
        return $date->format('d') . ' ' . $month . ' ' . $date->format('Y');
    }
}

/**
 * Format datetime in Indonesian locale
 * @param string|DateTime $date DateTime to format
 * @return string Formatted datetime
 */
if (!function_exists('format_datetime_id')) {
    function format_datetime_id($date) {
        if (!$date) return '-';
        if (is_string($date)) $date = new DateTime($date);
        
        return format_date_id($date) . ' ' . $date->format('H:i');
    }
}

/**
 * Format currency in Indonesian format
 * @param float $value Value to format
 * @return string Formatted currency
 */
if (!function_exists('format_money_id')) {
    function format_money_id($value) {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}

/**
 * Format percentage
 * @param float $value Percentage value
 * @param int $decimals Decimal places
 * @return string Formatted percentage
 */
if (!function_exists('format_percent')) {
    function format_percent($value, $decimals = 2) {
        return number_format($value, $decimals) . '%';
    }
}

/**
 * Get status color
 * @param string $status Status name
 * @return string Color class
 */
if (!function_exists('get_status_color')) {
    function get_status_color($status) {
        $colors = [
            'completed' => 'success',
            'processing' => 'warning',
            'pending' => 'warning',
            'error' => 'danger',
            'active' => 'success',
            'inactive' => 'danger',
        ];
        
        return $colors[$status] ?? 'info';
    }
}

/**
 * Check if user has role
 * @param string $role Role name
 * @return bool
 */
if (!function_exists('has_role')) {
    function has_role($role) {
        $user = session()->get('user');
        if (!$user) return false;
        
        if (is_array($role)) {
            return in_array($user['role'] ?? '', $role);
        }
        
        return ($user['role'] ?? '') === $role;
    }
}

/**
 * Check if user is admin
 * @return bool
 */
if (!function_exists('is_admin')) {
    function is_admin() {
        return has_role(['admin', 'superadmin']);
    }
}

/**
 * Get user full name or fallback
 * @param array $user User array
 * @return string User name
 */
if (!function_exists('user_name')) {
    function user_name($user = null) {
        $user = $user ?? session()->get('user');
        
        if (!$user) return 'Guest';
        
        if (isset($user['full_name'])) {
            return $user['full_name'];
        }
        
        return $user['name'] ?? 'Unknown';
    }
}

/**
 * Get user avatar initials
 * @param array $user User array
 * @return string Avatar initials
 */
if (!function_exists('user_initials')) {
    function user_initials($user = null) {
        $user = $user ?? session()->get('user');
        $name = user_name($user);
        
        $parts = explode(' ', trim($name));
        $initials = '';
        
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper($part[0]);
        }
        
        return $initials;
    }
}

/**
 * Generate unique token
 * @param int $length Token length
 * @return string Random token
 */
if (!function_exists('generate_token')) {
    function generate_token($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
}

/**
 * Check if email is valid format
 * @param string $email Email to validate
 * @return bool
 */
if (!function_exists('is_valid_email')) {
    function is_valid_email($email) {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

/**
 * Get file size in human readable format
 * @param int $bytes File size in bytes
 * @return string Human readable size
 */
if (!function_exists('human_filesize')) {
    function human_filesize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

/**
 * Get time ago string
 * @param string|DateTime $date Date to compare
 * @return string Time ago string
 */
if (!function_exists('time_ago')) {
    function time_ago($date) {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        $now = new DateTime();
        $interval = $date->diff($now);
        
        if ($interval->y > 0) return $interval->y . ' tahunyg lalu';
        if ($interval->m > 0) return $interval->m . ' bulan lalu';
        if ($interval->d > 0) return $interval->d . ' hari lalu';
        if ($interval->h > 0) return $interval->h . ' jam lalu';
        if ($interval->i > 0) return $interval->i . ' menit lalu';
        
        return 'baru saja';
    }
}

/**
 * Render component with fallback
 * @param string $component Component name
 * @param array $data Component data
 * @return string Rendered component
 */
if (!function_exists('component')) {
    function component($component, $data = []) {
        $path = 'components/' . $component;
        
        if (view($path, $data)) {
            return view($path, $data);
        }
        
        return '<div class="alert alert-warning">Component not found: ' . htmlspecialchars($component) . '</div>';
    }
}

/**
 * Get config value
 * @param string $key Config key (dot notation)
 * @param mixed $default Default value
 * @return mixed Config value
 */
if (!function_exists('config_get')) {
    function config_get($key, $default = null) {
        $parts = explode('.', $key);
        $class = '\\Config\\' . ucfirst(array_shift($parts));
        
        try {
            $config = new $class();
            $value = $config;
            
            foreach ($parts as $part) {
                $value = $value->{$part} ?? null;
                if ($value === null) return $default;
            }
            
            return $value ?? $default;
        } catch (Exception $e) {
            return $default;
        }
    }
}
