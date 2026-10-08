<?php

if (!function_exists('image_url')) {
    /**
     * Get bulletproof URL for an image path compatible with both local and live hosting environments.
     *
     * @param string|null $path
     * @param string|null $default
     * @return string
     */
    function image_url(?string $path, ?string $default = null): string
    {
        $defaultUrl = $default ?: asset('assets/olgasehat-icon.png');

        if (empty($path)) {
            return $defaultUrl;
        }

        // Return external URLs or base64 data URIs as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:image/')) {
            return $path;
        }

        // Normalize Windows backslashes and whitespace
        $path = str_replace('\\', '/', trim($path));
        $path = ltrim($path, '/');

        if (empty($path)) {
            return $defaultUrl;
        }

        // Check 1: Direct file in public/ directory (e.g. public/fotoklinik/xxx.jpg)
        if (file_exists(public_path($path)) && !is_dir(public_path($path))) {
            return asset($path);
        }

        // Check 2: Leading 'public/' prefix (e.g. public/logovenue/xxx.png)
        if (str_starts_with($path, 'public/')) {
            $stripped = substr($path, 7);
            if (file_exists(public_path($stripped)) && !is_dir(public_path($stripped))) {
                return asset($stripped);
            }
            if (file_exists(storage_path('app/public/' . $stripped)) || file_exists(public_path('storage/' . $stripped))) {
                return asset('storage/' . $stripped);
            }
            $path = $stripped;
        }

        // Check 3: Leading 'storage/' prefix (e.g. storage/logovenue/xxx.png or storage/app/public/xxx)
        if (str_starts_with($path, 'storage/')) {
            $relativeStorage = substr($path, 8);
            if (str_starts_with($relativeStorage, 'app/public/')) {
                $relativeStorage = substr($relativeStorage, 11);
            }
            if (file_exists(public_path('storage/' . $relativeStorage)) || file_exists(storage_path('app/public/' . $relativeStorage))) {
                return asset('storage/' . $relativeStorage);
            }
            if (file_exists(public_path($relativeStorage)) && !is_dir(public_path($relativeStorage))) {
                return asset($relativeStorage);
            }
            return asset('storage/' . $relativeStorage);
        }

        // Check 4: File stored in storage/app/public (e.g. logovenue/xxx.png, profile_images/xxx.jpg, venue_galleries/xxx.jpg)
        if (file_exists(storage_path('app/public/' . $path)) || file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        // Check 5: Direct folder in public/ (e.g. fotoklinik/xxx.jpg, fotodokter/xxx.jpg, bukti_pembayaran/xxx.jpg)
        $knownPublicFolders = ['fotoklinik', 'fotodokter', 'bukti_pembayaran', 'fotogaleri', 'fotoberita', 'fotoaktivitas', 'fotoprogram', 'images', 'uploads', 'assets', 'aset'];
        $firstDir = explode('/', $path)[0];

        if (in_array($firstDir, $knownPublicFolders)) {
            return asset($path);
        }

        // Storage folders fallback
        $knownStorageFolders = ['logovenue', 'venue_galleries', 'clinic_galleries', 'profile_images', 'fotoreview', 'fotoprogram', 'banners'];
        if (in_array($firstDir, $knownStorageFolders)) {
            return asset('storage/' . $path);
        }

        // If file exists anywhere under storage/app/public
        return asset('storage/' . $path);
    }
}
