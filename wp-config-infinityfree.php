<?php
/**
 * The base configuration for WordPress
 *
 * @package WordPress
 */

// ** Database settings - InfinityFree ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'if0_40359713_almetallerie' );

/** Database username */
define( 'DB_USER', 'if0_40359713' );

/** Database password */
define( 'DB_PASSWORD', 'QSXNmkwxyOzOT' );

/** Database hostname */
define( 'DB_HOST', 'sql301.infinityfree.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

// ** URLs du site ** //
define('WP_HOME', 'https://al-metallerie.great-site.net');
define('WP_SITEURL', 'https://al-metallerie.great-site.net');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'd1cebf50f94ed18ec29af20b8c87e19977c5d434' );
define( 'SECURE_AUTH_KEY',  '85638e0842cc031208ab46004bba6962446667aa' );
define( 'LOGGED_IN_KEY',    'd4a647c2e00696754a031357cc9574682e26f604' );
define( 'NONCE_KEY',        '2de9cfa17c492172fce549c4442c563368b0a7c7' );
define( 'AUTH_SALT',        '71be222523ad26bdf6a3d6b814e50f746165a61e' );
define( 'SECURE_AUTH_SALT', '1889426387746ce0b7dae58d09d6b41e1b71b47a' );
define( 'LOGGED_IN_SALT',   'f1610520e31b11c28cd2d527b992bfe8c37ef6da' );
define( 'NONCE_SALT',       '857bceb52f619d99312942572943a4bfcae11bef' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
@ini_set( 'display_errors', 1 );

/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
