/**
 * Custom JS for the theme
 */
import example from './js/example'

/**
 * Custom CSS for the theme
 *
 * @see https://vite.dev/guide/features#css
 */
import './main.css'

/**
 * Run our custom JS when the page is fully loaded.
 */
document.addEventListener('DOMContentLoaded',function() {
  example.run()
})