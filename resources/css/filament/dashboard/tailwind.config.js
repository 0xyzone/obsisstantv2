import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Dashboard/**/*.php',
        './resources/views/filament/dashboard/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/guava/filament-knowledge-base/src/**/*.php',
        './vendor/guava/filament-knowledge-base/resources/**/*.blade.php',
        './vendor/bezhansalleh/filament-panel-switch/resources/views/panel-switch-menu.blade.php'
    ],
}
