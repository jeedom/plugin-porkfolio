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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';


class porkfolio extends eqLogic {
	
	public static function cronHourly($_eqlogic_id = null) {
		if($_eqlogic_id !== null){
			$eqLogics = array(eqLogic::byId($_eqlogic_id));
		}else{
			$eqLogics = eqLogic::byType('porkfolio');
		}
        foreach ($eqLogics as $porkfolio) {
			if ($porkfolio->getIsEnable() == 1) {
				log::add('porkfolio', 'debug', 'Pull Cron pour Porkfolio');
				$porkfolioInfo = $porkfolio->getporkfolioInfo();
				$somme=(isset($porkfolioInfo['Somme'])) ? $porkfolioInfo['Somme'] : "old";
				$derniervers=(isset($porkfolioInfo['Dernier'])) ? $porkfolioInfo['Dernier'] : "old";
				$datemvt=(isset($porkfolioInfo['Date Mouvement'])) ? $porkfolioInfo['Date Mouvement'] : "old";
				$dateretournement=(isset($porkfolioInfo['Date Retournement'])) ? $porkfolioInfo['Date Retournement'] : "old";
				$datevers=(isset($porkfolioInfo['Date depot'])) ? $porkfolioInfo['Date depot'] : "old";
				$objectif=(isset($porkfolioInfo['Objectif'])) ? $porkfolioInfo['Objectif'] : "old";
				$nez=(isset($porkfolioInfo['Nez'])) ? $porkfolioInfo['Nez'] : "old";
				foreach ($porkfolio->getCmd('info') as $cmd) {
					switch ($cmd->getName()) {
						case 'Somme':
							$value=$somme;
						break;
						case 'Dernière Opération':
							$value=$derniervers; break;
						case 'Date mouvement':
							$value=$datemvt; break;
						case 'Date dépot':
							$value=$datevers; break;
						case 'Nez':
							$value=$nez; break;
						case 'Date retournement':
							$value=$dateretournement; break;
						case 'Objectif':
							$value=$objectif; break;
					}
					if ($value==0 ||$value != 'old'){
						$cmd->event($value);
						log::add('porkfolio','debug','set:'.$cmd->getName().' to '. $value);
					}
				}
                $mc = cache::byKey('porkfolioWidgetmobile' . $porkfolio->getId());
				$mc->remove();
				$mc = cache::byKey('porkfolioWidgetdashboard' . $porkfolio->getId());
				$mc->remove();
				$porkfolio->toHtml('mobile');
				$porkfolio->toHtml('dashboard');
				$porkfolio->refreshWidget();
			}
		}
	}
	
	public static function updateporkfolio() {
		log::remove('porkfolio_update');
		$clientid = config::byKey('clientid', 'porkfolio', 0);
		$clientsecret = config::byKey('clientsecret', 'porkfolio', 0);
		$username = config::byKey('username', 'porkfolio', 0);
		$password = config::byKey('password', 'porkfolio', 0);
		$cmd = '/usr/bin/python ' . dirname(__FILE__) . '/../../3rdparty/py-wink-adapt/login.py '.$clientid.' '.$clientsecret.' '.$username.' '.$password;
		$cmd .= ' >> ' . log::getPathToLog('porkfolio_update') . ' 2>&1 &';
		exec($cmd);
	}
	
	public function retraitdepotporkfolio($id,$amount) {
		$porkfolio = porkfolio::byId($id);
		$porkid=$porkfolio->getConfiguration('porkid');
		$typoperation='Depot';
		if ($amount<0){
			$typoperation='Retrait';
			$amount=$amount*(-1);
		}
		$montant=$amount*100;
		$cmd = '/usr/bin/python ' . dirname(__FILE__) . '/../../3rdparty/py-wink-adapt/set_info.py '.$porkid.' '.$typoperation.' '.$montant.' 2>&1 &';
		log::add('porkfolio','debug','Execution de la commande suivante : ' .$cmd);
		$resultid=exec($cmd);
		log::add('porkfolio','debug','Le retour de la commande est : ' .$resultid);
		$porkfolio->cronHourly();
	}
	
	public function changenezporkfolio($id,$color) {
		$porkfolio = porkfolio::byId($id);
		$porkid=$porkfolio->getConfiguration('porkid');
		$color=str_replace("#", "", $color);
		$cmd = '/usr/bin/python ' . dirname(__FILE__) . '/../../3rdparty/py-wink-adapt/set_info.py '.$porkid.' Nez '.$color.' 2>&1 &';
		log::add('porkfolio','debug','Execution de la commande suivante : ' .$cmd);
		$resultid=exec($cmd);
		log::add('porkfolio','debug','Le retour de la commande est : ' .$resultid);
		$porkfolio->cronHourly();
	}
	
	public function changeobjectifporkfolio($id,$objectif) {
		$porkfolio = porkfolio::byId($id);
		$porkid=$porkfolio->getConfiguration('porkid');
		$objectif=$objectif*100;
		$cmd = '/usr/bin/python ' . dirname(__FILE__) . '/../../3rdparty/py-wink-adapt/set_info.py '.$porkid.' Objectif '.$objectif.' 2>&1 &';
		log::add('porkfolio','debug','Execution de la commande suivante : ' .$cmd);
		$resultid=exec($cmd);
		log::add('porkfolio','debug','Le retour de la commande est : ' .$resultid);
		$porkfolio->cronHourly();
	}
	
	public function preUpdate() {
		//Recherche de l'id et du name
		if ($this->getConfiguration('porknumber') == '') {
            $porknumber='1';
        } else {
			$porknumber=$this->getConfiguration('porknumber');
		}
		$cmd = '/usr/bin/python ' . dirname(__FILE__) . '/../../3rdparty/py-wink-adapt/porkfolio_get.py '.$porknumber.' 2>&1 &';
		log::add('porkfolio','debug','Execution de la commande suivante : ' .$cmd);
		$resultarray=exec($cmd);
		$result_json=json_decode($resultarray,true);
		$resultid=(isset($result_json['porkid'])) ? $result_json['porkid'] : "Inconnu";
		$resultname=(isset($result_json['porkname'])) ? $result_json['porkname'] : "Inconnu";
		$resultserial=(isset($result_json['porkserial'])) ? $result_json['porkserial'] : "Inconnu";
		$resultmac=(isset($result_json['porkmac'])) ? $result_json['porkmac'] : "Inconnu";
		log::add('porkfolio','debug','L\'id du porkfolio est : ' .$resultid);
		log::add('porkfolio','debug','Le nom du porkfolio est : ' .$resultname);
		log::add('porkfolio','debug','Le numéro de série du porkfolio est : ' .$resultserial);
		log::add('porkfolio','debug','L\'adresse mac du porkfolio est : ' .$resultmac);
		$this->setConfiguration('porkid', $resultid);
		$this->setConfiguration('porkname', $resultname);
		$this->setConfiguration('porkserial', $resultserial);
		$this->setConfiguration('porkmac', $resultmac);
		//Rajout des commandes
		$porkfolioCmd = $this->getCmd(null, 'somme');
		if (!is_object($porkfolioCmd)) {
			$porkfolioCmd = new porkfolioCmd();
		}
		$porkfolioCmd->setName(__('Somme', __FILE__));
		$porkfolioCmd->setLogicalId('somme');
		$porkfolioCmd->setEqLogic_id($this->getId());
		$porkfolioCmd->setUnite('€');
		$porkfolioCmd->setType('info');
		$porkfolioCmd->setEventOnly(1);
		$porkfolioCmd->setConfiguration('onlyChangeEvent',1);
		$porkfolioCmd->setIsHistorized(1);
		$porkfolioCmd->setSubType('numeric');
		$porkfolioCmd->save();
		
		$porkfolioCmd = $this->getCmd(null, 'derniervers');
		if (!is_object($porkfolioCmd)) {
			$porkfolioCmd = new porkfolioCmd();
		}
		$porkfolioCmd->setName(__('Dernière Opération', __FILE__));
		$porkfolioCmd->setLogicalId('derniervers');
		$porkfolioCmd->setEqLogic_id($this->getId());
		$porkfolioCmd->setUnite('€');
		$porkfolioCmd->setType('info');
		$porkfolioCmd->setConfiguration('onlyChangeEvent',1);
		$porkfolioCmd->setEventOnly(1);
		$porkfolioCmd->setSubType('numeric');
		$porkfolioCmd->save();
		
		$porkfolioCmd = $this->getCmd(null, 'objectif');
		if (!is_object($porkfolioCmd)) {
			$porkfolioCmd = new porkfolioCmd();
		}
		$porkfolioCmd->setName(__('Objectif', __FILE__));
		$porkfolioCmd->setLogicalId('objectif');
		$porkfolioCmd->setEqLogic_id($this->getId());
		$porkfolioCmd->setUnite('€');
		$porkfolioCmd->setType('info');
		$porkfolioCmd->setConfiguration('onlyChangeEvent',1);
		$porkfolioCmd->setEventOnly(1);
		$porkfolioCmd->setSubType('numeric');
		$porkfolioCmd->save();
		
		$porkfolioCmd = $this->getCmd(null, 'nez');
		if (!is_object($porkfolioCmd)) {
			$porkfolioCmd = new porkfolioCmd();
		}
		$porkfolioCmd->setName(__('Nez', __FILE__));
		$porkfolioCmd->setLogicalId('nez');
		$porkfolioCmd->setEqLogic_id($this->getId());
		$porkfolioCmd->setType('info');
		$porkfolioCmd->setEventOnly(1);
		$porkfolioCmd->setConfiguration('onlyChangeEvent',1);
		$porkfolioCmd->setSubType('string');
		$porkfolioCmd->save();
		
		$porkfolioCmd = $this->getCmd(null, 'datedepot');
		if (!is_object($porkfolioCmd)) {
			$porkfolioCmd = new porkfolioCmd();
		}
		$porkfolioCmd->setName(__('Date dépot', __FILE__));
		$porkfolioCmd->setLogicalId('datedepot');
		$porkfolioCmd->setEqLogic_id($this->getId());
		$porkfolioCmd->setType('info');
		$porkfolioCmd->setEventOnly(1);
		$porkfolioCmd->setConfiguration('onlyChangeEvent',1);
		$porkfolioCmd->setSubType('numeric');
		$porkfolioCmd->save();
		
		$porkfolioCmd = $this->getCmd(null, 'datemouvement');
		if (!is_object($porkfolioCmd)) {
			$porkfolioCmd = new porkfolioCmd();
		}
		$porkfolioCmd->setName(__('Date mouvement', __FILE__));
		$porkfolioCmd->setLogicalId('datemouvement');
		$porkfolioCmd->setEqLogic_id($this->getId());
		$porkfolioCmd->setType('info');
		$porkfolioCmd->setEventOnly(1);
		$porkfolioCmd->setConfiguration('onlyChangeEvent',1);
		$porkfolioCmd->setSubType('numeric');
		$porkfolioCmd->save();
		
		$porkfolioCmd = $this->getCmd(null, 'dateretournement');
		if (!is_object($porkfolioCmd)) {
			$porkfolioCmd = new porkfolioCmd();
		}
		$porkfolioCmd->setName(__('Date retournement', __FILE__));
		$porkfolioCmd->setLogicalId('dateretournement');
		$porkfolioCmd->setEqLogic_id($this->getId());
		$porkfolioCmd->setType('info');
		$porkfolioCmd->setEventOnly(1);
		$porkfolioCmd->setConfiguration('onlyChangeEvent',1);
		$porkfolioCmd->setSubType('numeric');
		$porkfolioCmd->save();
		
		$porkfolioCmd = $this->getCmd(null, 'refresh');
		if (!is_object($porkfolioCmd)) {
			$porkfolioCmd = new porkfolioCmd();
		}
		$porkfolioCmd->setName(__('Rafraichir', __FILE__));
		$porkfolioCmd->setLogicalId('refresh');
		$porkfolioCmd->setEqLogic_id($this->getId());
		$porkfolioCmd->setType('action');
		$porkfolioCmd->setSubType('other');
		$porkfolioCmd->save();
		
	}
	
	public function getporkfolioInfo() {
		$porkid=$this->getConfiguration('porkid');
		$cmd = '/usr/bin/python ' . dirname(__FILE__) . '/../../3rdparty/py-wink-adapt/get_info.py '.$porkid.' 2>&1 &';
		log::add('porkfolio','debug','Execution de la commande suivante : ' .$cmd);
		$resultarray=exec($cmd);
		log::add('porkfolio','debug','Le résultat est : ' .$resultarray);
		return json_decode($resultarray, true);
	}
	
	public function postUpdate() {
		$this->cronHourly();
	}
	
	public function toHtml($_version = 'dashboard') {
		if ($this->getIsEnable() != 1) {
			return '';
		}
		if (!$this->hasRight('r')) {
			return '';
		}
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1) {
			return '';
		}
		$mc = cache::byKey('porkfolioWidget' . jeedom::versionAlias($_version) . $this->getId());
		if ($mc->getValue() != '') {
			return preg_replace("/" . preg_quote(self::UIDDELIMITER) . "(.*?)" . preg_quote(self::UIDDELIMITER) . "/", self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER, $mc->getValue());
		}
		$background=$this->getBackgroundColor($_version);
		if ($this->getCmd(null,'derniervers')->execCmd()<0){
			$humeur='triste';
		} else {
			$humeur='content';
		}
		if ($this->getCmd(null,'somme')->execCmd()>=$this->getCmd(null,'objectif')->execCmd()){
			$humeur='jeedom';
			$background='#34a729';
		}
		if ($this->getCmd(null,'somme')->execCmd()==0){
			$background='#e54016';
			$humeur='very_triste';
		}
		$replace = array(
			'#name#' => $this->getName(),
			'#id#' => $this->getId(),
			'#background_color#' => $background,
			'#uid#' => 'porkfolio' . $this->getId() . self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER,
			'#eqLink#' => $this->getLinkToConfiguration(),
			'#porky_humeur#' => $humeur,
		);

		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_history#'] = '';
			if ($cmd->getIsVisible() == 1) {
				$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
				if ($cmd->getName()=='Date dépot' || $cmd->getName()=='Date retournement' || $cmd->getName()=='Date mouvement'){
					$replace['#' . $cmd->getLogicalId() . '#'] = date("d/m/y H:i:s",$cmd->execCmd());
				}else{
					$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
				}
				$replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
				if ($cmd->getIsHistorized() == 1) {
					$replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
				}
			} else {
				$replace['#' . $cmd->getLogicalId() . '#'] = '';
			}
		}

		$refresh = $this->getCmd(null, 'refresh');
		$replace['#refresh_id#'] = $refresh->getId();

		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}

		$html = template_replace($replace, getTemplate('core', $_version, 'porkfolio', 'porkfolio'));
		cache::set('porkfolioWidget' . $_version . $this->getId(), $html, 0);
		return $html;
	}
}
 
class porkfolioCmd extends cmd {
    /*     * *************************Attributs****************************** */
	


    /*     * ***********************Methode static*************************** */

    /*     * *********************Methode d'instance************************* */
	public function execute($_options =null) {
		if ($this->getType() == '') {
			return '';
		}
		$eqLogic = $this->getEqlogic();
		$eqLogic->cronHourly($eqLogic->getId());
    }

}

?>