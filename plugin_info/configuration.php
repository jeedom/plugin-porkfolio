<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>
<form class="form-horizontal">
<fieldset>
<div class="form-group">
    <label class="col-lg-2 control-label">{{Client ID : }}</label>
    <div class="col-lg-2">
		<input id="porkfolio_api" class="configKey form-control" data-l1key="clientid" style="margin-top:-5px" placeholder="Client ID API"/>
    </div>
	<label class="col-lg-2 control-label">{{Client Secret : }}</label>
    <div class="col-lg-2">
		<input id="porkfolio_api" class="configKey form-control" data-l1key="clientsecret" style="margin-top:-5px" placeholder="Client Secret Api"/>
    </div>
</div>
    
<div class="form-group">
    <label class="col-lg-2 control-label">{{Username : }}</label>
    <div class="col-lg-2">
		<input id="porkfolio_api" class="configKey form-control" data-l1key="username" style="margin-top:-5px" placeholder="Username"/>
    </div>
	<label class="col-lg-2 control-label">{{Mot de passe : }}</label>
    <div class="col-lg-2">
		<input id="porkfolio_api" type="password" class="configKey form-control" data-l1key="password" style="margin-top:-5px" placeholder="Mot de passe"/>
    </div>
</div>
</fieldset> 
</form>
<script>
function porkfolio_postSaveConfiguration(){
    bootbox.confirm('{{Etes-vous sûr de vouloir installer/mettre à jour le fichier de config ? }}', function (result) {
      if (result) {
		  $('#md_modal').dialog({title: "{{Création / Mise à jour du fichier de config}}"});
          $('#md_modal').load('index.php?v=d&plugin=porkfolio&modal=update.porkfolio').dialog('open');
    }
});
};
</script>