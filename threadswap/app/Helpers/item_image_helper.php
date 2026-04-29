<?php

if (!function_exists('item_image_url')) {
    function item_image_url(?string $image): string
    {
        if (empty($image)) {
            return '';
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        return base_url('uploads/' . $image);
    }
}

if (!function_exists('item_has_image')) {
    function item_has_image(?string $image): bool
    {
        return !empty($image);
    }
}