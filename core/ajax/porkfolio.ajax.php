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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
	
	if (init('action') == 'updateporkfolio') {
		porkfolio::updateporkfolio();
		ajax::success();
	}
	
	if (init('action') == 'retraitdepot') {
		$id = init('id');
		$porkfolio = porkfolio::byId($id);
		$retrait=init('montant');
		$somme= $porkfolio->getCmd(null,'somme')->execCmd();
		if (!is_numeric($retrait)){
			ajax::error('Veuillez saisir un nombre SVP');
		} else if (($somme+$retrait)<0){
			ajax::error('Vous demandez à retirer plus que ce que vous possédez');
		} else if ($retrait>99){
			ajax::error('Vous ne pouvez pas déposer plus de 99€');
		} else if ($retrait==0){
			ajax::error('A quoi ca sert de déposer 0€');
		} else {
			porkfolio::retraitdepotporkfolio(init('id'),init('montant'));
			ajax::success();
		}
	}
	if (init('action') == 'nezcouleur') {
		porkfolio::changenezporkfolio(init('id'),init('couleur'));
		ajax::success();
	}
	
	if (init('action') == 'objectif') {
		$objectif=init('montant');
		if (!is_numeric($objectif)){
			ajax::error('Veuillez saisire un nombre SVP');
		} else if ($objectif<0){
			ajax::error('Vous ne pouvez pas fixer un objectif négatif');
		} else if ($objectif>99){
			ajax::error('Vous ne pouvez pas fixer un objectif supérieur à 99€');
		} else if ($objectif==0){
			ajax::error('Inutile de fixer un objectif à 0€, essayez un vrai objectif');
		}  else {
			porkfolio::changeobjectifporkfolio(init('id'),init('montant'));
			ajax::success();
		}
	}

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
