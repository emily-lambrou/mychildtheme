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
 * A login page layout for the moove theme.
 *
 * @package    theme_moove
 * @copyright  2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


$extraclasses[] = 'mychildtheme-login';
$bodyattributes = $OUTPUT->body_attributes($extraclasses);


$particlesconfig = file_get_contents($CFG->dirroot . "/theme/moove/part.json");

$quotes_string = file_get_contents($CFG->dirroot . '/theme/moove/enterpreneur-quotes.json');

if ($quotes_string === false) {
    // deal with error...
}

$json_quotes = json_decode($quotes_string, true);
if ($json_quotes === null) {
    // deal with error...
}
$starting_day = date('z') + 1;



$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'todays_quote' => $json_quotes[$starting_day] ?? $json_quotes[10],
    'address' => get_string('address', 'theme_mychildtheme'),
	'telephone' => get_string('telephone', 'theme_mychildtheme'),
	'email' => get_string('email', 'theme_mychildtheme'),
	'termsofuse' => get_string('termsofuse', 'theme_mychildtheme'),
	'termsofusemsg' => get_string('termsofusemsg', 'theme_mychildtheme'),
	'allrightsreserved' => get_string('allrightsreserved', 'theme_mychildtheme'),
	'termsofusemsg' => get_string('termsofusemsg', 'theme_mychildtheme'),
	'contact' => get_string('contact', 'theme_mychildtheme'),
	'studentpanel' => get_string('studentpanel', 'theme_mychildtheme'),
	'mainwebsite' => get_string('mainwebsite', 'theme_mychildtheme'),
	'footershortwelcomemessage' => get_string('footershortwelcomemessage', 'theme_mychildtheme',$SITE->fullname),
	'footerlogoimage' => $OUTPUT->image_url('footerlogoimage', 'theme_mychildtheme'),
    //'login-background' => $OUTPUT->image_url('login-background', 'theme_moove'),
    'default-footer-logo' => $OUTPUT->image_url('default-footer-logo', 'theme_mychildtheme'),
    'default-logo' => $OUTPUT->image_url('default-logo', 'theme_mychildtheme'),
	'links' => get_string('links', 'theme_mychildtheme'),
	'year' => date("Y"),
	'univname' => get_config("theme_mychildtheme","univname"),
	'univmainwebsite' => get_config("theme_mychildtheme","univmainwebsite"),
	'univstudentpanel' => get_config("theme_mychildtheme","univstudentpanel"),
	'univaddress' => get_config("theme_mychildtheme","univaddress"),
	'univtelephone' => get_config("theme_mychildtheme","univtelephone"),
	'univemail' => get_config("theme_mychildtheme","univemail"),
	'particlesconfig' => $particlesconfig,
];



if ($this->page->pagetype == 'login-signup') {
    $templatecontext['logourl'] = $OUTPUT->get_logo();
}
$PAGE->requires->js_call_amd('theme_mychildtheme/login', 'init');
//$PAGE->requires->js_call_amd('theme_moove/particles', 'init');

echo $OUTPUT->render_from_template('theme_mychildtheme/login', $templatecontext);
