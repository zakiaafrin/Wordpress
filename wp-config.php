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
/** Direct connection */
define('FS_METHOD', 'direct');
define('FS_CHMOD_DIR', '0770');
define('FS_CHMOD_FILE', '0660');
/** FTP username */
define('FTP_USER', 'root');
/** FTP Password */
define('FTP_PASS', '@1234Nijhum');
/** FTP Host Name */
define('FTP_HOST', '142.93.122.228/wordpress');
/** FTP SSL */
define('FTP_SSL', false);

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', '@1234Nijhum');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'Tmxz? #Hv&0/<i-Ky$JN~{c5e%#KvMsQSi%JI+j{@uIJJ_5Z=Mb)Gd{i5Y2+4dk[');
define('SECURE_AUTH_KEY',  'ey:Nzy1eG{=V{~c[vCK_&Gp;@mTUB$22(Fn O6v7!z$_rH,@0156wt*K3+TYGD2N');
define('LOGGED_IN_KEY',    'eLTfCwEejb812j1O:LFBBS)R}~a,57mnQ^[Lpjb3P`MMT%,>zCDI,_}$|zGdQYs-');
define('NONCE_KEY',        '3#e,J@7k;E$;a<D(#aT sIT6ga{px}:%MEL|-ZH|u]kR4F^=jIv^oCJ7HTKyPXvY');
define('AUTH_SALT',        'W3gE-b,h j1BT6bI&dg1!5fGm_AK}OM^<O[waJ;GSM1u$Vf1LGg$td-PHzs3fZa1');
define('SECURE_AUTH_SALT', '%P-woinll|gI#$a>SqPi]B,b|TF(w7<oL]I87`KAq5% ;rC201`_-x:uo#[O7oi4');
define('LOGGED_IN_SALT',   'D]bLk^c/xRgwb6DcnNrEH*!QwDRmXuXc1<,Ma1PBOV(^C*L9I9<q<MdZjm1QMShk');
define('NONCE_SALT',       'HJHknbM4VK)v]iD`$WiBr-oP+XN^#qHT$RUy:W54qo!D$-=9>+wO~7FP]a<.5V G');

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
