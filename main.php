<?php


$perso1 = new Guerrier();

$perso1->setFirstname('Emeric');

$perso1->setFirstname('Emeric')->attaquer();


class Guerrier {
    private $firstname;

	

	public function setFirstname($value) {
		$this->firstname = $value;
        return $this;
	}

    public function attaquer(){
        print('Le guerrier attaque');
    }
}