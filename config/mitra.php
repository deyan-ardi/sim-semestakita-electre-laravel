<?php

use App\Http\Controllers\Controller;

$mitra = Controller::getMitra();
if ($mitra['meta']['code'] == 200) {
    return [
        // Get Mitra Name From API
        'name' => $mitra['data']['nama_mitra'] == null ? env('MITRA_NAME', 'TPS3R YAYASAN TAKSU TRIDATU') : $mitra['data']['nama_mitra'],

        // Get Icon Text From API
        'icon_text' => $mitra['data']['logo_mitra_text'] == null ? env('MITRA_ICON_TEXT', 'https://email.semestakita.id/tridatu/icon-text.png') : $mitra['data']['logo_mitra_text'],

        // Get Icon Only From API
        'icon' => $mitra['data']['logo_mitra_icon'] == null ? env('MITRA_ICON', 'https://email.semestakita.id/tridatu/icon.png') : $mitra['data']['logo_mitra_icon'],

        // Get Icon Favicon From API
        'fav' => $mitra['data']['logo_mitra_fav'] == null ? env('MITRA_FAV', 'https://email.semestakita.id/tridatu/fav.ico') : $mitra['data']['logo_mitra_fav'],
    ];
}
    return [
        'name' =>  env('MITRA_NAME', 'TPS3R YAYASAN TAKSU TRIDATU'),
        'icon_text' => env('MITRA_ICON_TEXT', 'https://email.semestakita.id/tridatu/icon-text.png'),
        'icon' => env('MITRA_ICON', 'https://email.semestakita.id/tridatu/icon.png'),
        'fav' =>  env('MITRA_FAV', 'https://email.semestakita.id/tridatu/fav.ico'),
    ];
