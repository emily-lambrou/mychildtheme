<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme functions.
 *
 * @package    theme_moove
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */

//function theme_moove_set_loginbgimg($theme) {
//    global $OUTPUT;
//
//    $loginbgimg = $theme->setting_file_url('loginbgimg', 'loginbgimg');
//
//    if (is_null($loginbgimg)) {
//        $loginbgimg = $OUTPUT->image_url('login_bg', 'theme');
//    }
//
//    $headercss = "#page-login-index.moove-login #page-wrapper #page {background-image: url('$loginbgimg');}";
//
//    return $headercss;
//}

/**
 * Adds the logo image to CSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */

function theme_mychildtheme_get_logo() {
    global $OUTPUT;
    $theme = theme_config::load('mychildtheme');

    $logoimgurl = $theme->setting_file_url('logo', 'logo');


    if (is_null($logoimgurl)) {
        $logoimgurl = $OUTPUT->image_url('logo', 'theme');
    }

    return $logoimgurl;
}

function theme_mychildtheme_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_mychildtheme', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    // Moove scss.
    $moovevariables = file_get_contents($CFG->dirroot . '/theme/mychildtheme/scss/mychildtheme/_variables.scss');
    $moove = file_get_contents($CFG->dirroot . '/theme/mychildtheme/scss/default.scss');
    $security = file_get_contents($CFG->dirroot . '/theme/mychildtheme/scss/mychildtheme/_security.scss');

    // Combine them together.
    $allscss = $moovevariables . "\n" . $scss . "\n" . $mychildtheme . "\n" . $security;

    return $allscss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_mychildtheme_get_extra_scss($theme) {
    $content = '';

    // Sets the login background image.

    $loginbgimgurl = $theme->image_url('login_bg', 'theme');

    if (!empty($loginbgimgurl)) {
        $content .= 'body.pagelayout-login #page { ';
        $content .= "background-image: url('$loginbgimgurl'); background-size: cover;";
        $content .= ' }';
    }


    // Always return the background image with the scss when we have it.
    return !empty($theme->settings->scss) ? $theme->settings->scss . ' ' . $content : $content;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_mychildtheme_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['brand-primary'],
        'secondarymenucolor' => 'secondary-menu-color',
        'fontsite' => 'font-family-sans-serif',
        'brandcolor' => ['brand-primary'],
        'navbarheadercolor' => 'navbar-header-color',
        'navbarbg' => 'navbar-bg',
        'navbarbghover' => 'navbar-bg-hover'
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            if ($target == 'fontsite') {
                $scss .= '$' . $target . ': "' . $value . '", sans-serif !default' .";\n";
            } else {
                $scss .= '$' . $target . ': ' . $value . ";\n";
            }
        }, (array) $targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_mychildtheme_get_precompiled_css() {
    global $CFG;

    return file_get_contents($CFG->dirroot . '/theme/moove/style/moodle.css');
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return mixed
 */
function theme_mychildtheme_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    $theme = theme_config::load('mychildtheme');

    if ($context->contextlevel == CONTEXT_SYSTEM &&
        ($filearea === 'logo' || $filearea === 'loginbgimg' || $filearea == 'favicon')) {
        $theme = theme_config::load('mychildtheme');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM && preg_match("/^sliderimage[1-9][0-9]?$/", $filearea) !== false) {
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM && $filearea === 'marketing1icon') {
        return $theme->setting_file_serve('marketing1icon', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM && $filearea === 'marketing2icon') {
        return $theme->setting_file_serve('marketing2icon', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM && $filearea === 'marketing3icon') {
        return $theme->setting_file_serve('marketing3icon', $args, $forcedownload, $options);
    }

    if ($context->contextlevel == CONTEXT_SYSTEM && $filearea === 'marketing4icon') {
        return $theme->setting_file_serve('marketing4icon', $args, $forcedownload, $options);
    }

    send_file_not_found();
}


function theme_mychildtheme_get_footer_context() {
    global $PAGE;

    $context = [
        'tiktok' => get_config('theme_mychildtheme', 'tiktok'),
        'facebook' => get_config('theme_mychildtheme', 'facebook'),
        'instagram' => get_config('theme_mychildtheme', 'instagram'),
        'youtube' => get_config('theme_mychildtheme', 'youtube'),
    ];

    return $context;
}
