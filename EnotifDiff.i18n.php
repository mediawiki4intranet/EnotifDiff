<?php

$messages = array();

$messages['en'] = array(
    'tog-enotifsenddiffs'    => 'Include page differences in notification e-mails',
    'tog-enotifsendmultiple' => 'Send multiple notifications instead of one after each visit',
    'enotif-empty-summary'   => 'empty summary.',
    'enotif_lastvisited'     => 'See $1 for all changes since your last visit.',
    'enotif_lastdiff'        => 'See $1 to view this change.',
    'enotif_body'            =>
'<body>
<p>Dear <b>$WATCHINGUSERNAME</b>,</p>

<p>The {{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED on $PAGEEDITDATE by $PAGEEDITOR,
see <a href="$PAGETITLE_URL">$PAGETITLE_URL</a> for the current version.</p>

<p>$NEWPAGE</p>

<p>Editor\'s summary: <b>$PAGESUMMARY $PAGEMINOREDIT</b><br>
Contact the editor: <a href="$PAGEEDITOR_EMAIL">mail</a>, <a href="$PAGEEDITOR_WIKI">wiki</a>.</p>

$REALDIFF

<p style="font-size: 12px"><i>
-- <br>
<b>Your friendly {{SITENAME}} notification system</b><br>
To change your watchlist settings, visit
<a href="{{fullurl:{{ns:special}}:Watchlist/edit}}">Special:Watchlist/edit</a><br>
Feedback and further assistance: <a href="{{fullurl:{{MediaWiki:Helppage}}}}">{{MediaWiki:Helppage}}</a>
</i></p>
</body>$DIFF',
);

$messages['ru'] = array(
    'tog-enotifsenddiffs'    => 'Включать в оповещения различия (diff\'ы) страниц',
    'tog-enotifsendmultiple' => 'Отправлять новое оповещение при каждом изменении, а не одно для всех с момента посещения',
    'enotif-empty-summary'   => 'нет описания.',
    'enotif_lastvisited'     => '<a href="$1">Перейдите по ссылке</a> для просмотра всех изменений, произошедших с вашего последнего посещения.',
    'enotif_lastdiff'        => '<a href="$1">Перейдите по ссылке</a> для ознакомления с изменением.',
    'enotif_body'            =>
'<body>
<p><b>$WATCHINGUSERNAME,</b></p>
<p>
$PAGEEDITDATE страница проекта «{{SITENAME}}» $PAGETITLE была $CHANGEDORCREATED участником
$PAGEEDITOR, см. <a href="$PAGETITLE_URL">$PAGETITLE_URL</a> для просмотра текущей версии.
</p>

<p>$NEWPAGE</p>

<p>Краткое описание изменения: <b>$PAGESUMMARY $PAGEMINOREDIT</b><br>
Обратиться к изменившему: <a href="$PAGEEDITOR_EMAIL">эл.почта</a>, <a href="$PAGEEDITOR_WIKI">вики</a>.</p>

$REALDIFF

<p style="font-size: 12px"><i>
-- <br>
<b>Система оповещения {{grammar:genitive|{{SITENAME}}}}</b><br>
Чтобы изменить настройки вашего списка наблюдения, обратитесь к странице
<a href="{{fullurl:{{ns:special}}:Watchlist/edit}}">Special:Watchlist/edit</a><br>
Обратная связь и помощь: <a href="{{fullurl:{{MediaWiki:Helppage}}}}">{{MediaWiki:Helppage}}</a>
</i></p>
</body>$DIFF',
);