<?php

require_once 'AddThisAdminUtilities.php';
require_once 'AddThisFeature.php';

if (!class_exists('AddThisAmp')) {
    class AddThisAmp {
        const ADMIN_NOTICE_NOAMP_KEY     = 'admin_amp_notice_noamp';
        const ADMIN_NOTICE_NOAMP_MESSAGE = 'AddThis Plugin is enabled.';
        const ADMIN_NOTICE_NOAMP_LINK    = '<a href="https://wordpress.org/plugins/amp/" target="_blank">Activate on your Accelerated Mobile Pages (AMP)</a>';

        const ADMIN_NOTICE_ENABLED_KEY     = 'admin_amp_notice_enabled';
        const ADMIN_NOTICE_ENABLED_MESSAGE = 'AddThis Plugin is enabled on your site including Accelerated Mobile Pages (AMP)';

        // Key based on compatibility version, so it can be re-enabled in the future. Do not use a
        // period in the key or it will not save.
        const ADMIN_NOTICE_INCOMPAT_KEY  = 'admin_amp_notice_incompat_1-1';
        const ADMIN_NOTICE_INCOMPAT_MESSAGE = 'AddThis Plugin is incompatible with the current version of the AMP plugin (using {cur}, requires {req} or higher). AMP functionality will not be available.';
        const ADMIN_NOTICE_INCOMPAT_LINK = '<a href="https://wordpress.org/plugins/amp/" target="_blank">Update to latest Official AMP Plugin for WordPress</a>';

        const ADMIN_NOTICE_ANON_KEY     = 'admin_amp_notice_anon';
        const ADMIN_NOTICE_ANON_MESSAGE = 'AddThis Plugin is enabled. Accelerated Mobile Pages are not currently available in anonymous WordPress mode, register for free on';
        const ADMIN_NOTICE_ANON_LINK    = '<a href="http://www.addthis.com/register" target="_blank">AddThis.com</a>';

        protected static $ampCompatChecked = false;
        protected static $ampCompatible    = false;
        protected static $ampCompatVersion = '1.1';

        protected static $floatingInserted = false;

        /**
         * Enqueued notice callback for outputting the admin notice message when in anonymous mode
         *
         * @return null
         */
        public static function adminNoticeAnonCallback() {
            AddThisAdminUtilities::showNotice(
                AddThisAmp::ADMIN_NOTICE_ANON_MESSAGE,
                AddThisAmp::ADMIN_NOTICE_ANON_KEY,
                AddThisAmp::ADMIN_NOTICE_ANON_LINK
            );
        }

        /**
         * Enqueued notice callback for outputting the admin notice message when AMP is enabled
         *
         * @return null
         */
        public static function adminNoticeEnabledCallback() {
            AddThisAdminUtilities::showNotice(
                AddThisAmp::ADMIN_NOTICE_ENABLED_MESSAGE,
                AddThisAmp::ADMIN_NOTICE_ENABLED_KEY
            );
        }

        /**
         * Enqueued notice callback for outputting the admin notice message when AddThis plugin
         * is not compatible with AMP for WordPress plugin
         *
         * @return null
         */
        public static function adminNoticeIncompatCallback() {
            $currentVersion = 'UNKNOWN';
            if (defined('AMP__VERSION')) {
                $currentVersion = AMP__VERSION;
            }

            $find = array('{cur}', '{req}');
            $replace = array($currentVersion, self::$ampCompatVersion);
            $message = str_replace($find, $replace, AddThisAmp::ADMIN_NOTICE_INCOMPAT_MESSAGE);

            AddThisAdminUtilities::showNotice(
                $message,
                AddThisAmp::ADMIN_NOTICE_INCOMPAT_KEY,
                AddThisAmp::ADMIN_NOTICE_INCOMPAT_LINK,
                'notice-warning'
            );
        }

        /**
         * Enqueued notice callback for outputting the admin notice message when AMP is not enabled
         *
         * @return null
         */
        public static function adminNoticeNoAmpCallback() {
            AddThisAdminUtilities::showNotice(
                AddThisAmp::ADMIN_NOTICE_NOAMP_MESSAGE,
                AddThisAmp::ADMIN_NOTICE_NOAMP_KEY,
                AddThisAmp::ADMIN_NOTICE_NOAMP_LINK
            );
        }

        /**
         * Determines if the current context is an AMP page
         *
         * @return boolean
         */
        public static function inAmpMode() {
            // disable in anonymous mode (for now)
            if (AddThisAdminUtilities::isAnonMode()) {
                return false;
            }

            if (self::isAmpCompatible() && function_exists('is_amp_endpoint')) {
                return is_amp_endpoint();
            }

            return false;
        }

        /**
         * Determines if the AMP plugin is compatible with this plugin
         *
         * @return boolean
         */
        public static function isAmpCompatible() {
            if (self::$ampCompatChecked === false) {
                self::$ampCompatChecked = true;

                if (
                    defined('AMP__VERSION') &&
                    version_compare(strtok(AMP__VERSION, '-'), self::$ampCompatVersion, '>=')
                ) {
                    self::$ampCompatible = true;
                } else {
                    self::$ampCompatible = false;
                }
            }

            return self::$ampCompatible;
        }

        /**
         * Determines if the AMP plugin is enabled
         *
         * @return boolean
         */
        public static function isAmpPluginEnabled() {
            return did_action('amp_init');
        }

        /**
         * Callback for admin_init hook
         *
         * @return null
         */
        public static function initAdmin() {
            /* Disable messaging for now
            if (AddThisAdminUtilities::isAdminInterface() && AddThisAdminUtilities::userHasCapabilities()) {
                if (self::isAmpPluginEnabled()) {
                    // AMP is enabled, messaging dependent upon anonymous mode and compatibility

                    if (self::isAmpCompatible() === false) {
                        AddThisAdminUtilities::enqueueNotice(AddThisAmp::ADMIN_NOTICE_INCOMPAT_KEY, array(__CLASS__, 'adminNoticeIncompatCallback'));
                    } else if (AddThisAdminUtilities::isAnonMode()) {
                        AddThisAdminUtilities::enqueueNotice(AddThisAmp::ADMIN_NOTICE_ANON_KEY, array(__CLASS__, 'adminNoticeAnonCallback'));
                    } else {
                        AddThisAdminUtilities::enqueueNotice(AddThisAmp::ADMIN_NOTICE_ENABLED_KEY, array(__CLASS__, 'adminNoticeEnabledCallback'));
                    }
                } else {
                    // AMP is not enabled
                    AddThisAdminUtilities::enqueueNotice(AddThisAmp::ADMIN_NOTICE_NOAMP_KEY, array(__CLASS__, 'adminNoticeNoAmpCallback'));
                }
            }
            */
        }

        /**
         * Generates the AMP-specific tag using the AMP plugin's helper if available.
         *
         * @param string $profileId Pub ID
         * @param string $widgetId ID of the widget
         * @param string $widgetType the type of widget
         * @param string $class CSS class for the tag
         * @param integer $width width of element
         * @param integer $height height of element
         *
         * @return string
         */
        public static function getAmpHtml($profileId, $widgetId, $widgetType = 'shin', $class = null, $width = 320, $height = 65) {
            $params = array(
                'width'          => $width,
                'height'         => $height,
                'data-pub-id'    => $profileId
            );

            if (!empty($widgetId)) {
                $params['data-widget-id'] = $widgetId;
            } else if (!empty($widgetType)) {
                $params['data-product-code'] = $widgetType;
            }

            if ($widgetType == 'shfs') {
                $params['layout'] = 'responsive';
            }

            if (!empty($class)) {
                $params['data-class-name'] = $class;
            }

            if (class_exists('AMP_HTML_Utils')) {
                return AMP_HTML_Utils::build_tag('amp-addthis', $params);
            } else {
                $html = '<amp-addthis';
                foreach ($params as $key => $value) {
                    $html .= ' ' . $key . '="' . $value . '"';
                }
                $html .= '></amp-addthis>';

                return $html;
            }
        }

        /**
         * Generates the AMP-specific tag for a floating share tool, only once
         *
         * @param string $profileId Pub ID
         *
         * @return string
         */
        public static function getFloatingHtml($profileId) {
            if (!self::$floatingInserted) {
                self::$floatingInserted = true;
                return self::getAmpHtml($profileId, null, 'shfs', null, 48, 48);
            }
        }
    }

    if (AddThisAdminUtilities::isAdminInterface()) {
        add_action('admin_init', array('AddThisAmp', 'initAdmin'));
    }
}