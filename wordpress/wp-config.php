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
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '}IV<qOJ->|x !g$PoD<PiDxw2u<^<Lp^WnXo<b[cNQQ@eG_>:|lsQNX>S(|AspPb');
define('SECURE_AUTH_KEY',  'fgs.H5ZyaUGAb{0^R|v<&.7=uK7;v-`z<rNQL}!I)t<M=EcB}_}fTirf@`mds4Nx');
define('LOGGED_IN_KEY',    '?Sh$LU3ZDI!R%l[UW0?dmkmTqbzd1(H9(Y(gM^YkiCc Uvht9yQcJ&<n&%BAoqf_');
define('NONCE_KEY',        '/=Q9V}=fOd}-!ijuBl$=uj%C0(;uK6FHTs~e@ai?6fw#X=C`o%TL_saH)$)_ {W$');
define('AUTH_SALT',        'q*uE*VL0lX}P`!jhUA^:J8$Pi-RQ6)e:/Jy45Q 3vvFaT1_FdadSF]L0>6<GB}17');
define('SECURE_AUTH_SALT', 'zdZ~1_WIC/*_DrJ<~!]FTnSb4K9&Y5gOrF)AKvYg>]pn>+L622FZ9ahrZGN`:CG+');
define('LOGGED_IN_SALT',   '3FMijB*;Wg)``N _tD3Z8(=Y`]A{hAC_R%~949Hdb*wID[O05!_lZ?[k{t-*W9Mm');
define('NONCE_SALT',       'Se,j^_PXYh8lyVm8(hOes25[e{y>DTF-!:&Bx4cginVf,v9}Ha9,zH,P5d40YL(b');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
