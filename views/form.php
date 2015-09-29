<?php
$title = !empty($extdisplay)?_("Edit Custom Context"):_("Add Custom Context");
$formaction = !empty($extdisplay)?'edit':'add';
function ccformgenopts($id,$value){
  $html = '
  <input type="radio" name="'.$id.'" id="'.$id.'yes" value="yes" '.(($value == 'yes')?"CHECKED":"").'>
  <label for="'.$id.'yes">'._("Allow").'</label>
  <input type="radio" name="'.$id.'" id="'.$id.'no" value="no" '.(($value == 'no')?"CHECKED":"").'>
  <label for="'.$id.'no">'._("Deny").'</label>
  <input type="radio" name="'.$id.'" id="'.$id.'allow" value="allowmatch" '.(($value == 'allowmatch')?"CHECKED":"").'>
  <label for="'.$id.'allow">'. _("Allow Rules").'</label>
  <input type="radio" name="'.$id.'" id="'.$id.'deny" value="denymatch" '.(($value == 'denymatch')?"CHECKED":"").'>
  <label for="'.$id.'deny">'. _("Deny Rules").'</label>
  ';
  return $html;
}
?>
<h2><?php echo $title?></h2>
<form class="fpbx-submit" name="frm_customcontexts" method="post" data-fpbx-delete="<?php echo $delURL?>" role="form">
<input type="hidden" name="action" value="<?php echo $formaction?>">
<input type="hidden" name="extdisplay" value="<?php echo $extdisplay?>">
<input type="hidden" name="view" value="form">
<input type="hidden" name="display" value="customcontexts">
<?php
if(empty($extdisplay)){
?>
<div class="section-title" data-for="ccgeneral"><h3><i class="fa fa-minus"></i><?php echo _("General")?></h3></div>
<div class="section" data-id="ccgeneral">
<!--Context-->
<div class="element-container">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">
            <label class="control-label" for="extdisplay"><?php echo _("Context") ?></label>
            <i class="fa fa-question-circle fpbx-help-icon" data-for="extdisplay"></i>
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" id="extdisplay" name="extdisplay" value="<?php echo isset($extdisplay)?$extdisplay:''?>" required>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <span id="extdisplay-help" class="help-block fpbx-help-block"><?php echo _("Name of the context as it appears in extensions_custom.conf")?></span>
    </div>
  </div>
</div>
<!--END Context-->
<!--Description-->
<div class="element-container">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">
            <label class="control-label" for="description"><?php echo _("Description") ?></label>
            <i class="fa fa-question-circle fpbx-help-icon" data-for="description"></i>
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" id="description" name="description" value="<?php echo isset($description)?$description:''?>" required>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <span id="description-help" class="help-block fpbx-help-block"><?php echo _("This will display as the name of this custom context.")?></span>
    </div>
  </div>
</div>
<!--END Description-->
</div>
<?php
}else{
?>
<div class="section-title" data-for="ccgeneral"><h3><i class="fa fa-minus"></i> <?php echo _("General")?></h3></div>
<div class="section" data-id="ccgeneral">
<!--Context-->
<div class="element-container">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">
            <label class="control-label" for="extdisplay "><?php echo _("Context") ?></label>
            <i class="fa fa-question-circle fpbx-help-icon" data-for="newcontext"></i>
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" id="newcontext" name="newcontext" value="<?php echo isset($context)?$context:''?>" required>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <span id="newcontext-help" class="help-block fpbx-help-block"><?php echo _("Name of the context as it appears in extensions_custom.conf")?></span>
    </div>
  </div>
</div>
<!--END Context-->
<!--Description-->
<div class="element-container">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">
            <label class="control-label" for="description"><?php echo _("Description") ?></label>
            <i class="fa fa-question-circle fpbx-help-icon" data-for="description"></i>
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" id="description" name="description" value="<?php echo isset($description)?$description:''?>" required>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <span id="description-help" class="help-block fpbx-help-block"><?php echo _("This will display as the name of this custom context.")?></span>
    </div>
  </div>
</div>
<!--END Description-->
<!--Dial Rules-->
<div class="element-container">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">
            <label class="control-label" for="dialpattern"><?php echo _("Dial Rules") ?></label>
            <i class="fa fa-question-circle fpbx-help-icon" data-for="dialpattern"></i>
          </div>
          <div class="col-md-9">
            <textarea rows="5" id="dialpattern" name="dialpattern" class="form-control"><?php echo $rulestext ?></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <span id="dialpattern-help" class="help-block fpbx-help-block"><?php echo _("If defined, you will have the option for each portion of the dialplan to Allow Rule, and that inclued will only be available if the number dialed matches these rules, or Deny Rule, and that include will only be available if the dialed number does NOT match these rules. You may use a pipe | to strip the preceeding digits.")?></span>
    </div>
  </div>
</div>
<!--END Dial Rules-->
</div>
<div class="section-title" data-for="ccsetall"><h3><i class="fa fa-minus"></i> <?php echo _("Set All")?></h3></div>
<div class="section" data-id="ccsetall">
  <!--Set All To-->
  <div class="element-container">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="form-group">
            <div class="col-md-3">
              <label class="control-label" for="setall"><?php echo _("Set All To") ?></label>
              <i class="fa fa-question-circle fpbx-help-icon" data-for="setall"></i>
            </div>
            <div class="col-md-9 radioset">
              <input type="radio" name="setall" id="setallyes" value="yes">
              <label for="setallyes"><?php echo _("Allow");?></label>
              <input type="radio" name="setall" id="setallno" value="no">
              <label for="setallno"><?php echo _("Deny");?></label>
              <input type="radio" name="setall" id="setallallow" value="allowmatch">
              <label for="setallallow"><?php echo _("Allow Rules");?></label>
              <input type="radio" name="setall" id="setalldeny" value="denymatch">
              <label for="setalldeny"><?php echo _("Deny Rules");?></label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <span id="setall-help" class="help-block fpbx-help-block"><?php echo _("Choose allow to allow access to all includes, choose deny to deny access.")?></span>
      </div>
    </div>
  </div>
  <!--END Set All To-->
</div>
<div class="section-title" data-for="ccdefault"><h3><i class="fa fa-minus"></i> <?php echo _("Default Internal Context")?></h3></div>
<div class="section" data-id="ccdefault">
<div class="alert alert-info">
  <?php echo _('Choose allow to allow access to the includes below, choose deny to deny access.<BR><font color="red"><strong>NOTE: Allowing an include may automatically allow another ENTIRE context!</strong></font>');?>
  <?php echo '<br/>'._('Choose a priority with which to sort this option. Lower numbers have a higher priority.')?>
</div>
  <?php
    foreach ($inclist as $val) {
      $out = '';
      if ($val[6] > 0) {
          $out .= '
            <div class="element-container">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="form-group">
                    <div class="col-md-3">
                      <label class="control-label" for="includes['.$val[2].'][allow]">'.$val[2].'</label><br/>
                      <label class="control-label" for="includes['.$val[2].'][sort]">'._("Priority").'</label>
                    </div>
                    <div class="col-md-9"><div class="radioset">';
                      $out .= ccformgenopts("includes[".$val[2]."][allow]", $val[4]);
                      $out .='
                      </div>
                      <input type="number" class="form-control" id="includes['.$val[2].'][sort]" name="includes['.$val[2].'][sort]" value="'. (isset($val[5])?$val[5]:'').'">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
            ';
      } else {
        $out .= '
          <div class="element-container">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="form-group">
                  <div class="col-md-3">
                    <label class="control-label" for="includes['.$val[2].'][allow]">'.$val[2].'</label><br/>
                    <label class="control-label" for="includes['.$val[2].'][sort]">'._("Priority").'</label>

                  </div>
                  <div class="col-md-9"><div class="radioset">';
                    $out .= ccformgenopts("includes[".$val[2]."][allow]", $val[4]);
                    $out .= '
                    </div>
                    <input type="number" class="form-control" id="includes['.$val[2].'][sort]" name="includes['.$val[2].'][sort]" value="'. (isset($val[5])?$val[5]:'').'">
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
          ';
      }
          echo $out;
        }
        ?>
</div>
<div class="section-title" data-for="ccinternaldp"><h3><i class="fa fa-minus"></i> <?php echo _("Internal Dialplan")?></h3></div>
<div class="section" data-id="ccinternaldp">
</div>
<div class="section-title" data-for="ccfodest"><h3><i class="fa fa-minus"></i> <?php echo _("Failover Destination")?></h3></div>
<div class="section" data-id="ccfodest">
</div>
<div class="section-title" data-for="ccfcfodest"><h3><i class="fa fa-minus"></i> <?php echo _("Feature Code Failover Destination")?></h3></div>
<div class="section" data-id="ccfcfodest">
</div>
<?php
}
?>
</form>
<script type="text/javascript">
  $("[name='setall']").on('change',function(){
    $('[name^="includes"][value="' + $(this).val() + '"]').prop('checked', true);

  })
</script>
