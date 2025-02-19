<?php
//namespace theme_mychildtheme\output\core_courses;
//
//class core_renderer extends \theme_moove\output\core_renderer {
//    public function footer() {
//        return $this->render_from_template('theme_mychildtheme/footer', []);
//    }
//}
//
//
//
//
//

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
 * Overriden theme boost core renderer.
 *
 * @package    theme_moove
 * @copyright  2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_mychildtheme\output;

use theme_config;
use context_course;
use moodle_url;
use html_writer;
use theme_mychildtheme\output\core_course\activity_navigation;
use custom_menu;
use action_menu_filler;
use action_menu_link_secondary;
use stdClass;
use action_menu;
use pix_icon;
use core_text;
use help_icon;
use context_system;
use core_course_list_element;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_moove
 * @copyright  2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer
{
    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_head_html()
    {
        $output = parent::standard_head_html();

        $googleanalyticscode = "<script
                                    async
                                    src='https://www.googletagmanager.com/gtag/js?id=GOOGLE-ANALYTICS-CODE'>
                                </script>
                                <script>
                                    window.dataLayer = window.dataLayer || [];
                                    function gtag() {
                                        dataLayer.push(arguments);
                                    }
                                    gtag('js', new Date());
                                    gtag('config', 'GOOGLE-ANALYTICS-CODE');
                                </script>";

        $theme = theme_config::load('mychildtheme');

        if (!empty($theme->settings->googleanalytics)) {
            $output .= str_replace("GOOGLE-ANALYTICS-CODE", trim($theme->settings->googleanalytics), $googleanalyticscode);
        }

        $sitefont = isset($theme->settings->fontsite) ? $theme->settings->fontsite : 'Roboto';

        $output .= '<link rel="preconnect" href="https://fonts.googleapis.com">
                       <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                       <link href="https://fonts.googleapis.com/css2?family='
            . $sitefont .
            ':ital,wght@0,300;0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">';

        return $output;
    }

    public function navbar(): string
    {
        //Shows in course navigation of tree
        return $this->render_from_template('core/navbar', $this->page->navbar);

    }

    /**
     * Returns HTML attributes to use within the body tag. This includes an ID and classes.
     *
     * @param string|array $additionalclasses Any additional classes to give the body tag,
     *
     * @return string
     *
     * @throws \coding_exception
     *
     * @since Moodle 2.5.1 2.6
     */
    public function body_attributes($additionalclasses = array())
    {
        $hasaccessibilitybar = get_user_preferences('thememoovesettings_enableaccessibilitytoolbar', '');
        if ($hasaccessibilitybar) {
            $additionalclasses[] = 'hasaccessibilitybar';

            $currentfontsizeclass = get_user_preferences('accessibilitystyles_fontsizeclass', '');
            if ($currentfontsizeclass) {
                $additionalclasses[] = $currentfontsizeclass;
            }

            $currentsitecolorclass = get_user_preferences('accessibilitystyles_sitecolorclass', '');
            if ($currentsitecolorclass) {
                $additionalclasses[] = $currentsitecolorclass;
            }
        }

        $fonttype = get_user_preferences('thememoovesettings_fonttype', '');
        if ($fonttype) {
            $additionalclasses[] = $fonttype;
        }

        if (!is_array($additionalclasses)) {
            $additionalclasses = explode(' ', $additionalclasses);
        }

        return ' id="' . $this->body_id() . '" class="' . $this->body_css_classes($additionalclasses) . '"';
    }

    /**
     * Whether we should display the main theme or site logo in the navbar.
     *
     * @return bool
     */
    public function should_display_logo()
    {
        if ($this->should_display_theme_logo() || parent::should_display_navbar_logo()) {
            return true;
        }

        return false;
    }

    /**
     * Whether we should display the main theme logo in the navbar.
     *
     * @return bool
     */
    public function should_display_theme_logo()
    {
        $logo = $this->get_theme_logo_url();

        return !empty($logo);
    }

    /**
     * Get the main logo URL.
     *
     * @return string
     */
    public function get_logo()
    {
        $logo = $this->get_theme_logo_url();

        if ($logo) {
            return $logo;
        }

        $logo = $this->get_logo_url();

        if ($logo) {
            return $logo->out(false);
        }

        return false;
    }

    /**
     * Get the main logo URL.
     *
     * @return string
     */
    public function get_theme_logo_url()
    {
        $theme = theme_config::load('mychildtheme');

        return $theme->setting_file_url('logo', 'logo');
    }

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form)
    {
        global $SITE, $CFG;

        $context = $form->export_for_template($this);

        $context->errorformatted = $this->error_text($context->error);


        if ($CFG->rememberusername == 0) {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabledonlysession');
        } else {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        }
        $context->errorformatted = $this->error_text($context->error);

//        $context->logourl = $this->get_logo();
        $get_logo = $this->get_logo();
        if ($get_logo) {
            $context->logourl = $get_logo;
        } else {
            $context->logourl = (string)theme_moove_get_logo();
        }
        $context->loginhelpstringname = get_string('loginhelpstringname', 'theme_moove');

        $context->sitename = format_string($SITE->fullname, true,
            ['context' => context_course::instance(SITEID), "escape" => false]);

        if (!$CFG->auth_instructions) {
            $context->instructions = null;
            $context->hasinstructions = false;
        }

        $context->hastwocolumns = false;
        if ($context->hasidentityproviders || $CFG->auth_instructions) {
            $context->hastwocolumns = true;
        }

        if ($context->identityproviders) {
            foreach ($context->identityproviders as $key => $provider) {
                $isfacebook = false;

                if (strpos($provider['iconurl'], 'facebook') !== false) {
                    $isfacebook = true;
                }

                $context->identityproviders[$key]['isfacebook'] = $isfacebook;
            }
        }

        return $this->render_from_template('core/loginform', $context);
    }

    /**
     * Returns the HTML for the site support email link
     *
     * @param array $customattribs Array of custom attributes for the support email anchor tag.
     * @return string The html code for the support email link.
     */
    public function supportemail(array $customattribs = []): string
    {
        global $CFG;

        $label = get_string('contactsitesupport', 'admin');
        $icon = $this->pix_icon('t/life-ring', '', 'moodle', ['class' => 'iconhelp icon-pre']);
        $content = $icon . $label;

        if (!empty($CFG->supportpage)) {
            $attributes = ['href' => $CFG->supportpage, 'target' => 'blank', 'class' => 'btn contactsitesupport btn-outline-info'];
        } else {
            $attributes = [
                'href' => $CFG->wwwroot . '/user/contactsitesupport.php',
                'class' => 'btn contactsitesupport btn-outline-info'
            ];
        }

        $attributes += $customattribs;

        return \html_writer::tag('a', $content, $attributes);
    }

    /**
     * Returns the moodle_url for the favicon.
     *
     * @return moodle_url The moodle_url for the favicon
     * @since Moodle 2.5.1 2.6
     */
    public function favicon()
    {
        global $CFG;

        $theme = theme_config::load('moove');

        $favicon = $theme->setting_file_url('favicon', 'favicon');

        if (!empty(($favicon))) {
            $urlreplace = preg_replace('|^https?://|i', '//', $CFG->wwwroot);
            $favicon = str_replace($urlreplace, '', $favicon);

            return new moodle_url($favicon);
        }

        return parent::favicon();
    }

    /**
     * Renders the header bar.
     *
     * @param \context_header $contextheader Header bar object.
     * @return string HTML for the header bar.
     */
    protected function render_context_header(\context_header $contextheader)
    {
        if ($this->page->pagelayout == 'mypublic') {
            return '';
        }

        // Generate the heading first and before everything else as we might have to do an early return.
        if (!isset($contextheader->heading)) {
            $heading = $this->heading($this->page->heading, $contextheader->headinglevel, 'h2');
        } else {
            $heading = $this->heading($contextheader->heading, $contextheader->headinglevel, 'h2');
        }

        // All the html stuff goes here.
        $html = html_writer::start_div('page-context-header');

        // Image data.
        if (isset($contextheader->imagedata)) {
            // Header specific image.
            $html .= html_writer::div($contextheader->imagedata, 'page-header-image mr-2');
        }

        // Headings.
        if (isset($contextheader->prefix)) {
            $prefix = html_writer::div($contextheader->prefix, 'text-muted text-uppercase small line-height-3');
            $heading = $prefix . $heading;
        }
        $html .= html_writer::tag('div', $heading, array('class' => 'page-header-headings'));

        // Buttons.
        if (isset($contextheader->additionalbuttons)) {
            $html .= html_writer::start_div('btn-group header-button-group');
            foreach ($contextheader->additionalbuttons as $button) {
                if (!isset($button->page)) {
                    // Include js for messaging.
                    if ($button['buttontype'] === 'togglecontact') {
                        \core_message\helper::togglecontact_requirejs();
                    }
                    if ($button['buttontype'] === 'message') {
                        \core_message\helper::messageuser_requirejs();
                    }
                    $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', array(
                        'class' => 'iconsmall',
                        'role' => 'presentation'
                    ));
                    $image .= html_writer::span($button['title'], 'header-button-title');
                } else {
                    $image = html_writer::empty_tag('img', array(
                        'src' => $button['formattedimage'],
                        'role' => 'presentation'
                    ));
                }
                $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
            }
            $html .= html_writer::end_div();
        }
        $html .= html_writer::end_div();

        return $html;
    }

    /**
     * Returns standard navigation between activities in a course.
     *
     * @return string the navigation HTML.
     */
    public function activity_navigation()
    {
        // First we should check if we want to add navigation.
        $context = $this->page->context;
        if (($this->page->pagelayout !== 'incourse' && $this->page->pagelayout !== 'frametop')
            || $context->contextlevel != CONTEXT_MODULE) {
            return '';
        }

        // If the activity is in stealth mode, show no links.
        if ($this->page->cm->is_stealth()) {
            return '';
        }

        $course = $this->page->cm->get_course();
        $courseformat = course_get_format($course);

        // Get a list of all the activities in the course.
        $modules = get_fast_modinfo($course->id)->get_cms();

        // Put the modules into an array in order by the position they are shown in the course.
        $mods = [];
        $activitylist = [];
        foreach ($modules as $module) {
            // Only add activities the user can access, aren't in stealth mode and have a url (eg. mod_label does not).
            if (!$module->uservisible || $module->is_stealth() || empty($module->url)) {
                continue;
            }
            $mods[$module->id] = $module;

            // No need to add the current module to the list for the activity dropdown menu.
            if ($module->id == $this->page->cm->id) {
                continue;
            }
            // Module name.
            $modname = $module->get_formatted_name();
            // Display the hidden text if necessary.
            if (!$module->visible) {
                $modname .= ' ' . get_string('hiddenwithbrackets');
            }
            // Module URL.
            $linkurl = new moodle_url($module->url, array('forceview' => 1));
            // Add module URL (as key) and name (as value) to the activity list array.
            $activitylist[$linkurl->out(false)] = $modname;
        }

        $nummods = count($mods);

        // If there is only one mod then do nothing.
        if ($nummods == 1) {
            return '';
        }

        // Get an array of just the course module ids used to get the cmid value based on their position in the course.
        $modids = array_keys($mods);

        // Get the position in the array of the course module we are viewing.
        $position = array_search($this->page->cm->id, $modids);

        $prevmod = null;
        $nextmod = null;

        // Check if we have a previous mod to show.
        if ($position > 0) {
            $prevmod = $mods[$modids[$position - 1]];
        }

        // Check if we have a next mod to show.
        if ($position < ($nummods - 1)) {
            $nextmod = $mods[$modids[$position + 1]];
        }

        $activitynav = new activity_navigation($prevmod, $nextmod, $activitylist);
        $renderer = $this->page->get_renderer('core', 'course');
        return $renderer->render($activitynav);
    }

    /**
     * Returns plugins callback renderable data to be printed on navbar.
     *
     * @return string Final html code.
     */
    public function get_navbar_callbacks_data()
    {
        $callbacks = get_plugins_with_function('moove_additional_header', 'lib.php');

        if (!$callbacks) {
            return '';
        }

        $output = '';

        foreach ($callbacks as $plugins) {
            foreach ($plugins as $pluginfunction) {
                if (function_exists($pluginfunction)) {
                    $output .= $pluginfunction();
                }
            }
        }

        return $output;
    }

    /**
     * Returns plugins callback renderable data to be printed on navbar.
     *
     * @return string Final html code.
     */
    public function get_module_footer_callbacks_data()
    {
        $callbacks = get_plugins_with_function('moove_module_footer', 'lib.php');

        if (!$callbacks) {
            return '';
        }

        $output = '';

        foreach ($callbacks as $plugins) {
            foreach ($plugins as $pluginfunction) {
                if (function_exists($pluginfunction)) {
                    $output .= $pluginfunction();
                }
            }
        }

        return $output;
    }

    /**
     * The standard tags (typically performance information and validation links,
     * if we are in developer debug mode) that should be output in the footer area
     * of the page. Designed to be called in theme layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_footer_html()
    {
        global $CFG, $SCRIPT;

        $output = '';
        if (during_initial_install()) {
            return $output;
        }

        $pluginswithfunction = get_plugins_with_function('standard_footer_html', 'lib.php');
        foreach ($pluginswithfunction as $plugins) {
            foreach ($plugins as $function) {
                if ($function === 'tool_dataprivacy_standard_footer_html') {
                    $output .= $this->get_dataprivacyurl();

                    continue;
                }

                if ($function === 'tool_mobile_standard_footer_html') {
                    $output .= $this->get_mobileappurl();

                    continue;
                }

                $output .= $function();
            }
        }

        $output .= $this->unique_performance_info_token;
        if ($this->page->devicetypeinuse == 'legacy') {
            // The legacy theme is in use print the notification.
            $output .= html_writer::tag('div', get_string('legacythemeinuse'), array('class' => 'legacythemeinuse'));
        }

        // Get links to switch device types (only shown for users not on a default device).
        $output .= $this->theme_switch_links();

        if (debugging(null, DEBUG_DEVELOPER) and has_capability('moodle/site:config', context_system::instance())) {
            $purgeurl = new moodle_url('/admin/purgecaches.php', array('confirm' => 1,
                'sesskey' => sesskey(), 'returnurl' => $this->page->url->out_as_local_url(false)));

            $output .= '<li><i class="fa fa-chevron-circle-right"></i> ';
            $output .= html_writer::link($purgeurl,
                get_string('purgecaches', 'admin') . "</li>");
        }

        if (!empty($CFG->debugpageinfo)) {
            $output .= '<div class="performanceinfo pageinfo">This page is: ' . $this->page->debug_summary() . '</div>';
        }

        if (debugging(null, DEBUG_DEVELOPER) and has_capability('moodle/site:config', context_system::instance())) {
            // Add link to profiling report if necessary.
            if (function_exists('profiling_is_running') && profiling_is_running()) {
                $txt = get_string('profiledscript', 'admin');
                $title = get_string('profiledscriptview', 'admin');
                $url = $CFG->wwwroot . '/admin/tool/profiling/index.php?script=' . urlencode($SCRIPT);
                $link = '<a title="' . $title . '" href="' . $url . '">' . $txt . '</a>';
                $output .= '<div class="profilingfooter">' . $link . '</div>';
            }
        }

        // $output .= '';

        return $output;
    }

    private function get_dataprivacyurl()
    {
        $output = '';

        // A returned 0 means that the setting was set and disabled, false means that there is no value for the provided setting.
        $showsummary = get_config('tool_dataprivacy', 'showdataretentionsummary');
        if ($showsummary === false) {
            // This means that no value is stored in db. We use the default value in this case.
            $showsummary = true;
        }

        if ($showsummary) {
            $url = new moodle_url('/admin/tool/dataprivacy/summary.php');
            $output = html_writer::link($url,
                "<i class='slicon-folder-alt'></i> " . get_string('dataretentionsummary', 'tool_dataprivacy'),
                ['class' => 'btn btn-default']
            );

            $output = html_writer::div($output, 'tool_dataprivacy mb-2');
        }

        return $output;
    }

    /**
     * Returns the mobile app url
     *
     * @return string
     *
     * @throws \coding_exception
     */
    private function get_mobileappurl()
    {
        global $CFG;
        $output = '';
        if (!empty($CFG->enablemobilewebservice) && $url = tool_mobile_create_app_download_url()) {
            $output .= '<li><i class="fa fa-chevron-circle-right"></i> ';
            $output .= html_writer::link($url,
                get_string('getmoodleonyourmobile', 'tool_mobile') . '</li>');
        }

        return $output;
    }
}


