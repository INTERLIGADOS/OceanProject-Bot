<?php

$trade = function ($who, $message, $type) {

	$bot = actionAPI::getBot();

	if ($who != 1000000000) {
		return $bot->network->sendMessageAutoDetection($who, 'Nope! :)', $type);
	}

	if (empty($message[1]) || empty($message[2])) {
		return $bot->network->sendMessageAutoDetection($who, 'Usage: !trade [powername/powerid] [quantity]', $type);
	}

	$powers      = xatVariables::getPowers();
	$powerstring = '';

	switch ($message[1]) {
		case 'everypower':
			foreach ($powers as $powerid => $power) {
				if ($powerid == 81 || $powerid == 95) {
					continue;
				}
				$powerstring .= $powerid . '=' . $message[2] . '|';
			}
			break;

		case 'allpowers':
		case 'allpower':
			foreach ($powers as $powerid => $power) {
				if ($power['isAllPower'] === true) {
					$powerstring .= $powerid . '=' . $message[2] . '|';
				}
			}
			break;

		case 'epic':
		case 'epics':
			foreach ($powers as $powerid => $power) {
				if ($power['isEpic'] === true) {
					$powerstring .= $powerid . '=' . $message[2] . '|';
				}
			}
			break;

		default:
			$exist = false;
			if (is_numeric($message[1])) {
				foreach ($powers as $powerid => $power) {
					if ($powerid == $message[1]) {
						$powerstring .= $powerid . '=' . $message[2] . '|';
						$exist = true;
					}
				}
			} else {
				foreach ($powers as $powerid => $power) {
					if ($power['name'] == $message[1]) {
						$powerstring .= $powerid . '=' . $message[2] . '|';
						$exist = true;
					}
				}
			}

			if ($exist === false) {
				return $bot->network->sendMessageAutoDetection($who, 'This power does not exist.', $type);
			}
			break;
	}

	if (!empty($powerstring)) {
		dataAPI::set('sent_trade_' . $who, '0;0;' . $powerstring);
	}

	$buildPacket = ['i' => 30008, 'u' => xatVariables::getXatid(), 'd' => $who, 't' => 'O,0,0,' . $powerstring];
	$bot->network->socket->write('x', $buildPacket);

	usleep(300000);

	$buildPacket = ['i' => 30008, 'u' => xatVariables::getXatid(), 'd' => $who, 't' => 'S,0'];
	$bot->network->socket->write('x', $buildPacket);
};