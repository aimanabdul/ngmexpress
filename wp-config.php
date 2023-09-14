<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db3on8vg4bbvfu' );

/** Database username */
define( 'DB_USER', 'uv5m2q3vrzq3y' );

/** Database password */
define( 'DB_PASSWORD', 'eg12hhajfmzb' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'eVxBp18JYjYu5f~Ggx9s^55Y+uF+S(o1Whr46&IRjWA|g8(|<Unt&CZ#pC|sh=Rx' );
define( 'SECURE_AUTH_KEY',   '[#abxFux!-$>XQsI&UW8^Q-f1gWXCt|5$B&`lI52HC_+!pSy]-zicndFTH;Wm)D+' );
define( 'LOGGED_IN_KEY',     ',>]FpRld?MZ/Hrtys|.6Y_;$q_X;NEY/6nO|FVc-Su0;v^)moK(H`ST9UL(@JdiM' );
define( 'NONCE_KEY',         '0(3RCi^:slS6tt{.p0$Ne@p,My`~~].6ZL/6q8L0FJ]H&^FabVO7!amt8|{#:5++' );
define( 'AUTH_SALT',         '*fjXjwvv[--%sm`M4/Bg>}`{@I^`76sZ;zB<yDt*!7FA8oCw!+x%qsOB~B9oSS5P' );
define( 'SECURE_AUTH_SALT',  'R&R9]/6Pnc&TDROVl:qwUL-a4N`+aOn/1GaRU[JyT=8nm.*gUiGFz4:H}yr#GIoY' );
define( 'LOGGED_IN_SALT',    'mxKbeiYnGy47S~Kra{j(QGiN7eK~)<R*GGMHui*9Q;AXP94*6AB`rwyN&G|V&C4,' );
define( 'NONCE_SALT',        '8e|kqCBEMUl1Cu7G/7U`e}4sQc)k8E8[;,UDfv/sS s`,-(:#Sc=L:k !zI6q,ee' );
define( 'WP_CACHE_KEY_SALT', 'B+[.{a4}bOyt}R)`qKeUTI=h68+0_fvuXmjlI/vad{7j*DD4G*cSV{Y]Ws[*g$Q!' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'htv_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
