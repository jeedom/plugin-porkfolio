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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

//include_file('desktop', 'panel', 'css', 'comptes'); 


if (init('id') == '') {
    throw new Exception('{{L\'id de l\'équipement ne peut etre vide : }}' . init('op_id'));
}

$id = init('id');
$porkfolio = porkfolio::byId($id);
	if (!is_object($porkfolio)) { 
			  
	 throw new Exception(__('Aucun equipement ne  correspond : Il faut (re)-enregistrer l\'équipement ', __FILE__) . init('action'));
	 }
$id = $porkfolio->getId();
$objectif= $porkfolio->getCmd(null,'objectif')->execCmd();

?>
<div class="alert alert-info">
            Choisissez votre objectif : le montant doit être positif. Votre objectif actuel est de <?php echo $objectif; ?> €.
</div>
<div id="objectifchange">
   <input id="montant" type='number'  min="0" max="99" step="0.5" name='<?php echo $id; ?>'/> €
</div>
<br />
<a class="btn btn-success objectifchange"><i class="fa fa-check-circle"></i> ok</a>

<script>

$('.objectifchange').on('click', function() {
	changeobjectif($('#montant').attr('name'),$('input[id=montant]').val());
	
})

	function changeobjectif(_id,_montant) {
		
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/porkfolio/core/ajax/porkfolio.ajax.php", // url du fichier php
			data: {
				action: "objectif",
				id: _id,
				montant: _montant
				
	
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
            	$('#div_alert').showAlert({message:  data.result,level: 'danger'});
                return;
            }
            modifyWithoutSave=false;
             window.location.reload();
        }
    });
}



</script>