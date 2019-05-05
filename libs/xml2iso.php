<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of xml2iso
 *
 * @author Torsten
 */
    class isoSimpleXML extends SimpleXMLElement {
        public function addAttribute($key,$value) {
                                
           return parent::addAttribute(utf8_encode($key),utf8_encode($value));
        }

        public function addChild($key) {
           return parent::addChild(utf8_encode($key));
        }
    }
