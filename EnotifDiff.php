<?php

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * ATTENTION: This extension requires a patched includes/UserMailer.php
 *            (see MediaWiki4Intranet patch 000-html-emails)
 * ATTENTION2: This extension requires PreferencesExtension for MediaWiki < 1.16
 *
 * @author Vitaliy Filippov <vitalif@mail.ru>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if (!defined('MEDIAWIKI'))
    die();

define('MEDIAWIKI_HAVE_HTML_EMAIL', 1);
$wgExtensionFunctions[] = 'wfEnotifDiff';
$wgHooks['GetPreferences'][] = '_enotifdiff_GetPreferences';
$wgHooks['EnotifComposeCommonMailtext'][] = '_enotifdiff_compose_common_mailtext';
$wgHooks['EnotifPersonalizeMailtext'][] = '_enotifdiff_personalize_mailtext';
$wgHooks['EnotifUserCondition'][] = '_enotifdiff_user_condition';
$wgExtensionMessagesFiles['EnotifDiff'] = dirname(__FILE__) . '/EnotifDiff.i18n.php';
$wgExtensionCredits['other'][] = array (
    'name'        => 'Page differences in Enotify mail',
    'description' => 'An ability to send page diffs in enotify mail messages',
    'author'      => 'Vitaliy Filippov',
    'url'         => 'http://wiki.4intra.net/EnotifDiff',
    'version'     => '1.0.2 (2011-02-04), for MediaWiki 1.16',
);

function wfEnotifDiff()
{
    global $wgEmailContentType, $wgVersion, $IP;
    if ($wgVersion < '1.16')
    {
        if (!function_exists('wfAddPreferences'))
        {
            wfDebug('EnotifDiff requires PreferencesExtension for MediaWiki versions below 1.16. Install it from http://www.mediawiki.org/wiki/Extension:PreferencesExtension');
            die('EnotifDiff requires PreferencesExtension for MediaWiki versions below 1.16. Install it from http://www.mediawiki.org/wiki/Extension:PreferencesExtension');
        }
        wfAddPreferences(array(
            array(
                'name'    => 'enotifsenddiffs',
                'section' => 'prefs-personal',
                'type'    => PREF_TOGGLE_T,
                'default' => 0,
            ),
            array(
                'name'    => 'enotifsendmultiple',
                'section' => 'prefs-personal',
                'type'    => PREF_TOGGLE_T,
                'default' => 0,
            )
        ));
    }
    $wgEmailContentType = 'text/html';
}

function _enotifdiff_GetPreferences( $user, &$defaultPreferences )
{
    $defaultPreferences['enotifsenddiffs'] =
        array(
            'type' => 'toggle',
            'label-message' => 'tog-enotifsenddiffs',
            'section' => 'personal/email',
        );
    $defaultPreferences['enotifsendmultiple'] =
        array(
            'type' => 'toggle',
            'label-message' => 'tog-enotifsendmultiple',
            'section' => 'personal/email',
        );
    return true;
}

function _enotifdiff_compose_common_mailtext(&$mailer, &$keys)
{
    global $wgOut;
    $oldWgOut = $wgOut;
    if (class_exists('RequestContext'))
        $wgOut = new OutputPage(RequestContext::getMain());
    else
        $wgOut = new OutputPage();
    $de = new DifferenceEngine(Title::newFromText($keys['$PAGETITLE']), $keys['$OLDID'], 'next');
    $de->showDiffPage(true);
    $keys['$DIFF'] = $wgOut->getHTML();
    $keys['$DIFF'] = preg_replace('#^(.*?)<tr[^<>]*>.*?</tr\s*>#is', '\1', $keys['$DIFF'], 1);
    $keys['$DIFF'] = preg_replace('#class=[\"\']?diff-deletedline[\"\']?#is', 'style="background-color: #ffffaa"', $keys['$DIFF']);
    $keys['$DIFF'] = preg_replace('#class=[\"\']?diff-addedline[\"\']?#is', 'style="background-color: #ccffcc"', $keys['$DIFF']);
    $keys['$DIFF'] = preg_replace('#class=[\"\']?diffchange\s*diffchange-inline[\"\']?#is', 'style="color: red; font-weight: bold"', $keys['$DIFF']);
    if (trim($keys['$PAGESUMMARY']) == '-')
        $keys['$PAGESUMMARY'] = $keys['$PAGEMINOREDIT'] ? '' : wfMsg('enotif-empty-summary');
    $wgOut = $oldWgOut;
    return true;
}

function _enotifdiff_personalize_mailtext(&$mailer, &$user, &$body)
{
    $diff = '';
    if ($s = strpos($body, '</body>'))
    {
        $diff = trim(substr($body, $s+7));
        $body = substr($body, 0, $s+7);
    }
    if (!$diff)
        return true;
    if ($user->getOption('enotifsenddiffs'))
        $body = str_replace('$REALDIFF', $diff, $body);
    else
        $body = str_replace('$REALDIFF', '', $body);
    return true;
}

function _enotifdiff_user_condition(&$mailer, &$condition)
{
    $condition = str_ireplace(
        'wl_notificationtimestamp IS NULL',
        '(wl_notificationtimestamp IS NULL OR EXISTS (SELECT * FROM user_properties'.
        ' WHERE up_user=user_id AND up_property IN (\'enotifsendmultiple\', \'enotifsenddiffs\') AND up_value=1))',
        $condition);
    return true;
}
