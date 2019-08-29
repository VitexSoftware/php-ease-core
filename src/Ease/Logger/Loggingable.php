<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ease\Logger;

/**
 * @codeCoverageIgnore
 * @author             Vítězslav Dvořák <info@vitexsoftware.cz>
 */
interface Loggingable
{
    public function addToLog($caller, $message, $type = 'message');
}
