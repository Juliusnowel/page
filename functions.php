<?php
/**
 * Vite Child Theme functions and definitions
 */

function vite_child_enqueue_scripts()
{
    // Bootstrap 5 CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');

    // Font Awesome 6
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');

    // Google Fonts (Inter, Satoshi, Climate Crisis, Playfair Display)
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Climate+Crisis&family=Inter:wght@400;500;600&family=Satoshi:wght@400;500;700;900&family=Playfair+Display:ital,wght@1,500;1,700&display=swap', array(), null);

    // Custom Header CSS
    wp_enqueue_style('vite-child-header', get_stylesheet_directory_uri() . '/assets/css/header.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/header.css'));

    // Custom Footer CSS
    wp_enqueue_style('vite-child-footer', get_stylesheet_directory_uri() . '/assets/css/footer.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/footer.css'));

    // Bootstrap 5 JS Bundle
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true);
}
add_action('wp_enqueue_scripts', 'vite_child_enqueue_scripts');

// Auto-register any block with block.json under /blocks/*/
add_action('init', function () {
    $base = get_stylesheet_directory() . '/blocks';
    foreach (glob($base . '/*/block.json') as $json) register_block_type(dirname($json));
  });

/**
 * Cursor debug logging (local only)
 * Writes NDJSON lines to .cursor/debug.log so we can capture browser runtime state.
 */
add_action('rest_api_init', function () {
    register_rest_route('vite-debug/v1', '/log', [
        'methods'             => 'POST',
        'permission_callback' => function () {
            // Keep this permissive for local debugging; restrict to localhost.
            $remote = $_SERVER['REMOTE_ADDR'] ?? '';
            return in_array($remote, ['127.0.0.1', '::1'], true);
        },
        'callback'            => function (WP_REST_Request $request) {
            $payload = $request->get_json_params();
            if (!is_array($payload)) {
                $payload = ['message' => 'invalid payload', 'timestamp' => round(microtime(true) * 1000)];
            }

            $log_path = trailingslashit(get_stylesheet_directory()) . '.cursor/debug.log';
            $line = wp_json_encode($payload) . "\n";

            // Best-effort write; surface errors for debugging.
            $ok = @file_put_contents($log_path, $line, FILE_APPEND | LOCK_EX);
            if ($ok === false) {
                return new WP_REST_Response([
                    'ok' => false,
                    'error' => 'write_failed',
                    'log_path' => $log_path,
                ], 500);
            }

            return new WP_REST_Response(['ok' => true], 200);
        },
    ]);
});

add_filter( 'show_admin_bar', '__return_false' );
