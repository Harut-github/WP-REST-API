<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_harut' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'd*q:g%<clwYQrCgA86Ga?u3=]v4Ea(-TPOCkLp8DW!N{pG9t4C>_^G/(`_rZ6>VE' );
define( 'SECURE_AUTH_KEY',  'qDC.Krk;ynbL3b?z<.;:,HT^no}P5y566_%XGNwPtl]IhYWKvi=/7$d@)*T#ANBr' );
define( 'LOGGED_IN_KEY',    ';TYPa([A6d>D)He^J434AH#7,:o2kumy.&f`r;`ZSd4rU!O|o~dTxMFo@b;4fArM' );
define( 'NONCE_KEY',        'k3K8nx~;HFv^S~gXEEf5PyOWZ#e@lxnIKeJgLW0Qc Vj}f8JW/^^lni-kH:EZ%wz' );
define( 'AUTH_SALT',        'bOA.@}1PhzcBYELC: f?W,flAy:(b6H[eWdJtW>z/PZ*z5hP]z0[c9LQzW2RP:Oi' );
define( 'SECURE_AUTH_SALT', 'v,B@K~j}6F8!JdDrx[1ySUNev7Dnd5-U`Gy<$2a~4m V@<`iUttQhWg9%.Fh7-@Q' );
define( 'LOGGED_IN_SALT',   '5k@88ji#HS_eOI;$OYQ}|!=wS[`R~&u5F.lQa2u%|&t82tL4<}QzS.oYPE7hUj9>' );
define( 'NONCE_SALT',       'L&[FH2CZ4cVNG+f#[/y/mvu]6Q@B<*=V3-W:1{o$_P<)0EK[&_LiE,bh#}N::{3)' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
