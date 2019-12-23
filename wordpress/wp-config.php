<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpresswebsite' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '{bw67Dd`s&> ,|^@~wY7 kB9Y*c/^PjWC+oSg#a5Rq,!^NU)U1ER8zF(oO5VO/0_' );
define( 'SECURE_AUTH_KEY',  'V |f%jSV$w]ya7GtN2b([na0|cVNk9jx)}[rVICnOk;MRtbUAa>/D&/ty0c[[FKT' );
define( 'LOGGED_IN_KEY',    ' 675Lb <[verN8EqoinX7Zt82f/}U| )~y,<YV:r.c~hT~J-Y&Wr1nF9Zg=BY?)e' );
define( 'NONCE_KEY',        'yer=CBG44s==Q#y@D<UffUh=gpVIRWp {7Nwmrte($1nrzv{;5SewrA0jQVW`!t.' );
define( 'AUTH_SALT',        '5L`tGWJf TfE(swOi{}Infm;7GiOELI##F}50WovRC)jC{H5n?6scHSTZaq;X7)4' );
define( 'SECURE_AUTH_SALT', 'HtYhXGcyly$K@FmJiMA}5j;L #i/hnU={eQQ+XtDJ0EF#V5>&%bYdUbJn.S,YkZg' );
define( 'LOGGED_IN_SALT',   'hmK}f@4!GR:D[Cf]32~hy+S,jWcR{L*8m6+W#NVMIjJ*M?u^NBgsCyW@O/}6!:Z$' );
define( 'NONCE_SALT',       'qf9[XL.6WcSwGau%;XdE,6i/RqKa:,]()N]+c`6@W&90xMuTc8Gmhs}&gW0|}w=9' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
