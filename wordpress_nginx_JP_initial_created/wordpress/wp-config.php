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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'db');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '!0hp{xZDRX7 S1cSxP&ymKw9H~V7o]5Q|w5Xoxl/4ev2.X2ul{5Tyej2!0,H}o|r');
define('SECURE_AUTH_KEY',  '1X1f|vHWrC}*Q]aus_EcFtKs%|$+~VE2C|y.9r1YIipu4QO-UjJ[SHgNGAar]u!]');
define('LOGGED_IN_KEY',    ':+FPw;3L. -^6<cOVUo(|Bi&P,}T::GD@?P$g@[efk|WL1In-E1a{pf{4gO@C)5V');
define('NONCE_KEY',        '@LC)tbQR0N#<3x[Yfv,Moq)9xq}ziJ-WNQj%{sxG |%]i6slS}O>43i}c}gfIP9g');
define('AUTH_SALT',        '@{>x~%S`pDv?ko(qZEI|wGQB-LRHE,rhe TvE36w!F13_s}S|sQh*9yrToxk!2EC');
define('SECURE_AUTH_SALT', '_4ps*|{R(<F4b(0du#MxJIB%0S4Eu/7CfAwM_L%3/={;fV%jOnl[3==>K5.cM_vF');
define('LOGGED_IN_SALT',   '$FSoP4h#$c<is*ERT GZ4XR2$jigZVXC#B31[1>QltH,81.`G&CqaF]5U45,94by');
define('NONCE_SALT',       '!!LLS7*T,ST3]5I}Pwj8Hoo212Mw6QIA!Jq-$p/RuE~;72Q3Bb(~C+W^u6m.f5HT');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
