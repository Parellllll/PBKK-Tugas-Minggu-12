<?php

if (!function_exists('se_lang')) {
    function se_lang(): string
    {
        $lang = (string) session()->get('se_lang');
        return in_array($lang, ['id', 'en'], true) ? $lang : 'id';
    }
}

if (!function_exists('se_t')) {
    function se_t(string $idText, string $enText): string
    {
        return se_lang() === 'en' ? $enText : $idText;
    }
}

if (!function_exists('se_localized')) {
    function se_localized(array $row, string $field = 'name'): string
    {
        if (se_lang() === 'id' && !empty($row[$field . '_id'])) {
            return (string) $row[$field . '_id'];
        }
        return (string) ($row[$field] ?? '');
    }
}

if (!function_exists('se_workspace_prefix')) {
    function se_workspace_prefix(): string
    {
        $uri = trim(service('uri')->getPath(), '/');
        if (str_starts_with($uri, 'operator')) {
            return 'operator';
        }
        if (str_starts_with($uri, 'admin')) {
            return 'admin';
        }
        return session()->get('role') === 'operator' ? 'operator' : 'admin';
    }
}

