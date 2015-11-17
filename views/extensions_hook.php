<?php
global $currentcomponent;
$device_info = core_devices_get($_REQUEST['extdisplay']);
if (empty($device_info)) {
    $cc = 'from-internal';
} else {
  $tech = $device_info['tech'];
  switch ($tech) {
    case 'iax2':
    case 'iax':
    case 'sip':
    case 'dahdi':
    case 'zap':
      $_REQUEST['tech'] = $tech;
      $cc = $device_info['context'];
    break;
    default:
      $cc = 'from-internal';
  }
}
$contextssel  = customcontexts_getcontexts();
$currentcomponent->addoptlistitem('contextssel', 'from-internal', 'ALLOW ALL (Default)');
$curcontext = !empty($cc)?$cc:'from-internal';
$contextssel = is_array($contextssel)?$contextssel:array();
foreach ($contextssel as $val) {
  $currentcomponent->addoptlistitem('contextssel', $val[0], $val[1]);
}
$currentcomponent->setoptlistopts('contextssel', 'sort', false);
$js = '<script type="text/javascript">$(document).ready(function(){$("#devinfo_context").closest("div.element-container").hide();});</script>';
$currentcomponent->addguielem('Device Options', new gui_selectbox('customcontext', $currentcomponent->getoptlist('contextssel'), $curcontext, 'Custom Context', 'You have the '.customcontexts_getmodulevalue('moduledisplayname').' Module installed! You can select a custom context from this list to limit this user to portions of the dialplan you defined in the '.customcontexts_getmodulevalue('moduledisplayname').' module.',true, "javascript:if (document.frm_extensions.customcontext.value) {document.frm_extensions.devinfo_context.value = document.frm_extensions.customcontext.value} else {document.frm_extensions.devinfo_context.value = 'from-internal'}"));
$currentcomponent->addguielem('Device Options', new guielement('test-html', $js, ''));
?>
