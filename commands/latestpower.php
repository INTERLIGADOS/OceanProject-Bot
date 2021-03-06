<?php

$latestpower = function ($who, $message, $type) {

    /* Pow2 Indexs
        0 = last
        1 = backs
        2 = actions
        3 = hugs
        4 = topsh (smilies)
        5 = isgrp 
        6 = pssa (powernames)
        7 = pawns
        8 = nomob (temp)
        9 = pawns2 (temp)
    */	

    $bot = actionAPI::getBot();

    
    
    $powers = xatVariables::getPowers();
    $keys = array_keys($powers);
    $latestID = end($keys);
    $power = $powers[$latestID];
    
    $latestName = $power['name'];
    $status = "UNRELEASED";
    
    if ($power['isNew']) {
        $status = $power['isLimited'] ? "LIMITED" : "UNLIMITED";
    }
    
    $implode = [
        ucfirst($power['name']) . ' (ID: '. $latestID . ')',
        'Pawns: ' . (isset($power['pawns']) ? implode(', ', $power['pawns']) : 'none'),
        'Smilies: ' . implode(', ', $power['smilies']),
        'Store price: ' . $power['storeCost'] . ' xats',
        'Status: ' . $status
    ];
    
    $bot->network->sendMessageAutoDetection($who, implode(' | ', $implode), $type);
};
