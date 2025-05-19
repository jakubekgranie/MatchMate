import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',

                'resources/js/app.js',
                'resources/js/clickableCard.js',
                'resources/js/disableFakeNavBars.js',
                "resources/js/notificationHandler.js",
                'resources/js/profile.js',

                'resources/images/matchmate_icon.png',
                'resources/images/matchmate_title.png',
                'resources/images/check_mark.png',

                'resources/images/defaults/banner.png',
                'resources/images/defaults/cardbg.png',
                'resources/images/defaults/default_league_icon.png',
                'resources/images/defaults/default_user_pfp.png',
                'resources/images/defaults/pfp.png',
                'resources/images/defaults/team_icon.png',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
