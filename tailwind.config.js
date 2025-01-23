import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/codewithdennis/filament-simple-alert/resources/**/*.blade.php',
    ],
    plugins: [
        require("flyonui"),
        require("flyonui/plugin") // Require only if you want to use FlyonUI JS component
    ]
}
