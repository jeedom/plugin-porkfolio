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


if (init('id') == '') {
    throw new Exception('{{L\'id de l\'équipement ne peut etre vide : }}' . init('op_id'));
}

$id = init('id');
$porkfolio = porkfolio::byId($id);
	if (!is_object($porkfolio)) { 
			  
	 throw new Exception(__('Aucun equipement ne  correspond : Il faut (re)-enregistrer l\'équipement ', __FILE__) . init('action'));
	 }
$id = $porkfolio->getId();
$somme= $porkfolio->getCmd(null,'somme')->execCmd();
?>
<div class="alert alert-info">
            Pour retirer de l'argent renseignez une valeur négative pour en déposer renseignez une valeur positive. Votre
			solde actuel est de <?php echo $somme; ?> €.
</div>
<div id="retraitdepot">
         <input id="montant" type='number'  min="-99" max="99" step="0.05" name='<?php echo $id; ?>'/> €
 
    
</div>
<br />
<a class="btn btn-success retraitdepot"><i class="fa fa-check-circle"></i> ok</a>

<script>

$('.retraitdepot').on('click', function() {
	changeretraitdepot($('#montant').attr('name'),$('input[id=montant]').val());
	
})

	function changeretraitdepot(_id,_amount) {
		
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/porkfolio/core/ajax/porkfolio.ajax.php", // url du fichier php
			data: {
				action: "retraitdepot",
				id: _id,
				montant: _amount
				
	
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