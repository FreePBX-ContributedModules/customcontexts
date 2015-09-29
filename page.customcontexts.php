<?php /* $Id: page.customcontexts.php $ */
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

$dispnum = 'customcontexts'; //used for switch on config.php

switch ($_REQUEST['view']) {
  case 'form':
    $vars['action'] = isset($_REQUEST['action'])?$_REQUEST['action']:'';
    $vars['extdisplay'] = isset($_REQUEST['extdisplay'])?$_REQUEST['extdisplay']:null;
    $vars['showsort'] = isset($_REQUEST['showsort'])?$_REQUEST['showsort']:null;
    $vars['query'] = 'type=setup&display=customcontexts&extdisplay='.$vars['extdisplay'];
    $vars['delURL'] = '';
    if(!empty($vars['extdisplay'])){
      $vars['delURL'] = '?'.$query.'&action=del';
      $vars['dupURL'] = '?'.$query.'&action=dup';
      $savedcontext = \customcontexts_customcontexts_get($vars['extdisplay']);
      $vars['context'] = $savedcontext[0];
      $vars['description'] = $savedcontext[1];
      $vars['rulestext'] = $savedcontext[2];
      $vars['faildest']  = $savedcontext[3];
      $vars['featurefaildest']  = $savedcontext[4];
      $vars['failpin']  = $savedcontext[5];
      $vars['featurefailpin']  = $savedcontext[6];
      $vars['inclist'] = \customcontexts_getincludes($vars['extdisplay']);
      dbug($vars['inclist']);
    }
    $content = load_view(__DIR__.'/views/form.php',$vars);
    $bootnav = load_view(__DIR__.'/views/bootnav.php');
  break;

  default:
    $content = load_view(__DIR__.'/views/grid.php');
    $bootnav = '';
  break;
}

?>

<div class="container-fluid">
	<h1><?php echo _('Custom Contexts')?></h1>
	<div class = "display full-border">
		<div class="row">
			<div class="col-sm-<?php echo empty($bootnav)?'12':'9'?>">
				<div class="fpbx-container">
					<div class="display full-border">
						<?php echo $content;?>
					</div>
				</div>
			</div>
			<div class="col-sm-3 hidden-xs bootnav <?php echo empty($bootnav)?'hidden':''?>">
				<div class="list-group">
          <?php echo $bootnav?>
				</div>
			</div>
		</div>
	</div>
</div>
