<?php

function getEmojiFlag(string $countryCode): string {
    $regionalOffset = 0x1F1A5;
    return mb_chr($regionalOffset + mb_ord($countryCode[0], 'UTF-8'), 'UTF-8')
        . mb_chr($regionalOffset + mb_ord($countryCode[1], 'UTF-8'), 'UTF-8');
	}

?>