<?php
// This file is part of Ranking block for Moodle - http://moodle.org/
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
 * Theme Moove block settings file
 *
 * @package    theme_moove
 * @copyright  2017 Willian Mano http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingmychildtheme', get_string('configtitle', 'theme_mychildtheme'));

    /*
    * ----------------------
    * General settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_mychildtheme_general', get_string('generalsettings', 'theme_mychildtheme'));

    // Logo file setting.
    $name = 'theme_mychildtheme/logo';
    $title = get_string('logo', 'theme_mychildtheme');
    $description = get_string('logodesc', 'theme_mychildtheme');
    $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0, $opts);
    $page->add($setting);

    // Favicon setting.
    $name = 'theme_mychildtheme/favicon';
    $title = get_string('favicon', 'theme_mychildtheme');
    $description = get_string('favicondesc', 'theme_mychildtheme');
    $opts = array('accepted_types' => array('.ico'), 'maxfiles' => 1);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0, $opts);
    $page->add($setting);

    // Preset.
    $name = 'theme_mychildtheme/preset';
    $title = get_string('preset', 'theme_mychildtheme');
    $description = get_string('preset_desc', 'theme_mychildtheme');
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_mychildtheme', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    // These are the built in presets.
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files setting.
    $name = 'theme_mychildtheme/presetfiles';
    $title = get_string('presetfiles', 'theme_mychildtheme');
    $description = get_string('presetfiles_desc', 'theme_mychildtheme');

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
        array('maxfiles' => 10, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Login page background image.
    $name = 'theme_mychildtheme/loginbgimg';
    $title = get_string('loginbgimg', 'theme_mychildtheme');
    $description = get_string('loginbgimg_desc', 'theme_mychildtheme');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbgimg', 0, $opts);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $brand-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_mychildtheme/brandcolor';
    $title = get_string('brandcolor', 'theme_mychildtheme');
    $description = get_string('brandcolor_desc', 'theme_mychildtheme');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#0f47ad');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Variable $navbar-header-color.
    // We use an empty default value because the default colour should come from the preset.
    $name = 'theme_mychildtheme/secondarymenucolor';
    $title = get_string('secondarymenucolor', 'theme_mychildtheme');
    $description = get_string('secondarymenucolor_desc', 'theme_mychildtheme');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#0f47ad');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $fontsarr = [
        'Roboto' => 'Roboto',
        'Poppins' => 'Poppins',
        'Montserrat' => 'Montserrat',
        'Open Sans' => 'Open Sans',
        'Lato' => 'Lato',
        'Raleway' => 'Raleway',
        'Inter' => 'Inter',
        'Nunito' => 'Nunito',
        'Encode Sans' => 'Encode Sans',
        'Work Sans' => 'Work Sans',
        'Oxygen' => 'Oxygen',
        'Manrope' => 'Manrope',
        'Sora' => 'Sora',
        'Epilogue' => 'Epilogue'
    ];

    $name = 'theme_mychildtheme/fontsite';
    $title = get_string('fontsite', 'theme_mychildtheme');
    $description = get_string('fontsite_desc', 'theme_mychildtheme');
    $setting = new admin_setting_configselect($name, $title, $description, 'Roboto', $fontsarr);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_mychildtheme/enablecourseindex';
    $title = get_string('enablecourseindex', 'theme_mychildtheme');
    $description = get_string('enablecourseindex_desc', 'theme_mychildtheme');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    /*
    * ----------------------
    * Advanced settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_mychildtheme_advanced', get_string('advancedsettings', 'theme_mychildtheme'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_mychildtheme/scsspre',
        get_string('rawscsspre', 'theme_mychildtheme'), get_string('rawscsspre_desc', 'theme_mychildtheme'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_mychildtheme/scss', get_string('rawscss', 'theme_mychildtheme'),
        get_string('rawscss_desc', 'theme_mychildtheme'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Google analytics block.
    $name = 'theme_mychildtheme/googleanalytics';
    $title = get_string('googleanalytics', 'theme_mychildtheme');
    $description = get_string('googleanalyticsdesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    /*
    * -----------------------
    * Frontpage settings tab
    * -----------------------
    */
    $page = new admin_settingpage('theme_mychildtheme_frontpage', get_string('frontpagesettings', 'theme_mychildtheme'));

    // Disable teachers from cards.
    $name = 'theme_mychildtheme/disableteacherspic';
    $title = get_string('disableteacherspic', 'theme_mychildtheme');
    $description = get_string('disableteacherspicdesc', 'theme_mychildtheme');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // Slideshow.
    $name = 'theme_mychildtheme/slidercount';
    $title = get_string('slidercount', 'theme_mychildtheme');
    $description = get_string('slidercountdesc', 'theme_mychildtheme');
    $default = 0;
    $options = array();
    for ($i = 0; $i < 13; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // If we don't have an slide yet, default to the preset.
    $slidercount = get_config('theme_mychildtheme', 'slidercount');

    if (!$slidercount) {
        $slidercount = $default;
    }

    if ($slidercount) {
        for ($sliderindex = 1; $sliderindex <= $slidercount; $sliderindex++) {
            $fileid = 'sliderimage' . $sliderindex;
            $name = 'theme_mychildtheme/sliderimage' . $sliderindex;
            $title = get_string('sliderimage', 'theme_mychildtheme');
            $description = get_string('sliderimagedesc', 'theme_mychildtheme');
            $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
            $setting = new admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
            $page->add($setting);

            $name = 'theme_mychildtheme/slidertitle' . $sliderindex;
            $title = get_string('slidertitle', 'theme_mychildtheme');
            $description = get_string('slidertitledesc', 'theme_mychildtheme');
            $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TEXT);
            $page->add($setting);

            $name = 'theme_mychildtheme/slidercap' . $sliderindex;
            $title = get_string('slidercaption', 'theme_mychildtheme');
            $description = get_string('slidercaptiondesc', 'theme_mychildtheme');
            $default = '';
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $page->add($setting);
        }
    }

    $setting = new admin_setting_heading('slidercountseparator', '', '<hr>');
    $page->add($setting);

    $name = 'theme_mychildtheme/displaymarketingbox';
    $title = get_string('displaymarketingboxes', 'theme_mychildtheme');
    $description = get_string('displaymarketingboxesdesc', 'theme_mychildtheme');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    $displaymarketingbox = get_config('theme_mychildtheme', 'displaymarketingbox');

    if ($displaymarketingbox) {
        // Marketingheading.
        $name = 'theme_mychildtheme/marketingheading';
        $title = get_string('marketingsectionheading', 'theme_mychildtheme');
        $default = 'Awesome App Features';
        $setting = new admin_setting_configtext($name, $title, '', $default);
        $page->add($setting);

        // Marketingcontent.
        $name = 'theme_mychildtheme/marketingcontent';
        $title = get_string('marketingsectioncontent', 'theme_mychildtheme');
        $default = 'Moove is a Moodle template based on Boost with modern and creative design.';
        $setting = new admin_setting_confightmleditor($name, $title, '', $default);
        $page->add($setting);

        for ($i = 1; $i < 5; $i++) {
            $filearea = "marketing{$i}icon";
            $name = "theme_mychildtheme/$filearea";
            $title = get_string('marketingicon', 'theme_mychildtheme', $i . '');
            $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'));
            $setting = new admin_setting_configstoredfile($name, $title, '', $filearea, 0, $opts);
            $page->add($setting);

            $name = "theme_mychildtheme/marketing{$i}heading";
            $title = get_string('marketingheading', 'theme_mychildtheme', $i . '');
            $default = 'Lorem';
            $setting = new admin_setting_configtext($name, $title, '', $default);
            $page->add($setting);

            $name = "theme_mychildtheme/marketing{$i}content";
            $title = get_string('marketingcontent', 'theme_mychildtheme', $i . '');
            $default = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod.';
            $setting = new admin_setting_confightmleditor($name, $title, '', $default);
            $page->add($setting);
        }

        $setting = new admin_setting_heading('displaymarketingboxseparator', '', '<hr>');
        $page->add($setting);
    }

    // Enable or disable Numbers sections settings.
    $name = 'theme_mychildtheme/numbersfrontpage';
    $title = get_string('numbersfrontpage', 'theme_mychildtheme');
    $description = get_string('numbersfrontpagedesc', 'theme_mychildtheme');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    $numbersfrontpage = get_config('theme_mychildtheme', 'numbersfrontpage');

    if ($numbersfrontpage) {
        $name = 'theme_mychildtheme/numbersfrontpagecontent';
        $title = get_string('numbersfrontpagecontent', 'theme_mychildtheme');
        $description = get_string('numbersfrontpagecontentdesc', 'theme_mychildtheme');
        $default = get_string('numbersfrontpagecontentdefault', 'theme_mychildtheme');
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);
    }

    // Enable FAQ.
    $name = 'theme_mychildtheme/faqcount';
    $title = get_string('faqcount', 'theme_mychildtheme');
    $description = get_string('faqcountdesc', 'theme_mychildtheme');
    $default = 0;
    $options = array();
    for ($i = 0; $i < 11; $i++) {
        $options[$i] = $i;
    }
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $page->add($setting);

    $faqcount = get_config('theme_mychildtheme', 'faqcount');

    if ($faqcount > 0) {
        for ($i = 1; $i <= $faqcount; $i++) {
            $name = "theme_mychildtheme/faqquestion{$i}";
            $title = get_string('faqquestion', 'theme_mychildtheme', $i . '');
            $setting = new admin_setting_configtext($name, $title, '', '');
            $page->add($setting);

            $name = "theme_mychildtheme/faqanswer{$i}";
            $title = get_string('faqanswer', 'theme_mychildtheme', $i . '');
            $setting = new admin_setting_confightmleditor($name, $title, '', '');
            $page->add($setting);
        }

        $setting = new admin_setting_heading('faqseparator', '', '<hr>');
        $page->add($setting);
    }

    $settings->add($page);

    /*
    * --------------------
    * Footer settings tab
    * --------------------
    */
    $page = new admin_settingpage('theme_mychildtheme_footer', get_string('footersettings', 'theme_mychildtheme'));


    // Website.
    $name = 'theme_mychildtheme/website';
    $title = get_string('website', 'theme_mychildtheme');
    $description = get_string('websitedesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Mobile.
    $name = 'theme_mychildtheme/mobile';
    $title = get_string('mobile', 'theme_mychildtheme');
    $description = get_string('mobiledesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Mail.
    $name = 'theme_mychildtheme/mail';
    $title = get_string('mail', 'theme_mychildtheme');
    $description = get_string('maildesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Facebook url setting.
    $name = 'theme_mychildtheme/facebook';
    $title = get_string('facebook', 'theme_mychildtheme');
    $description = get_string('facebookdesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Twitter url setting.
    $name = 'theme_mychildtheme/x';
    $title = get_string('x', 'theme_mychildtheme');
    $description = get_string('xdesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Linkdin url setting.
    $name = 'theme_mychildtheme/linkedin';
    $title = get_string('linkedin', 'theme_mychildtheme');
    $description = get_string('linkedindesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Youtube url setting.
    $name = 'theme_mychildtheme/youtube';
    $title = get_string('youtube', 'theme_mychildtheme');
    $description = get_string('youtubedesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // Instagram url setting.
    $name = 'theme_mychildtheme/instagram';
    $title = get_string('instagram', 'theme_mychildtheme');
    $description = get_string('instagramdesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    // TikTok url setting.
    $name = 'theme_mychildtheme/tiktok';
    $title = get_string('tiktok', 'theme_mychildtheme');
    $description = get_string('tiktokdesc', 'theme_mychildtheme');
    $setting = new admin_setting_configtext($name, $title, $description, '');
    $page->add($setting);

    $settings->add($page);
}



