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
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         'PoyZxPi8,C#PhWIr9V&jDzL(C!LA!k?O5cs4X~:_r(,v:nH4rDQI5,4 s:S2Lu7{' );
define( 'SECURE_AUTH_KEY',  'ja(ASrTW,z$rBBvyZ=pfJp<%`[zm&r`u@S9MiHS!cAqn$(tpZ%6j2YNsuPf,Z4$8' );
define( 'LOGGED_IN_KEY',    '$0LLg/}L|y.d=?^#:;70s9&I%-Q;P]*.1F:__@2 H/]G*?:t.2->p+~$bnu>uSu}' );
define( 'NONCE_KEY',        'N!/S{ara[gF~=8,d AZ~A_UoGlm5zRqJ;Q}Em%SmAw]s25.0{F_^Eqo-4Q!4T*b*' );
define( 'AUTH_SALT',        '~Xt~]pv6xgd(D;|1pa3|9odC*A9p6Acv?nbBVaU(W.HFbOC0nC.gHB:;AW1&)T%)' );
define( 'SECURE_AUTH_SALT', 'Npa*+NmcH7!98#WMn+uyrDIf}zgm]f,G]BIV)}q=Q#;E*LtRw9d$2S%$988(6W+9' );
define( 'LOGGED_IN_SALT',   'R7{kPMun8D]$zIv1p;5_a)X,0*lF%Hu6#7$Rlz10D0EiwqA0Lv@L7X1tSnj):-Q[' );
define( 'NONCE_SALT',       ']4[1#`DxM0[/{7Nj&_AG6u| E]}!P{HT[;>i;US|R`~s2e)|4ebJGE8kMbA;0cdp' );

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
