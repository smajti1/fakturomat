<?php

/*
 * Format $number to PL money format
 */
function money_pl_format($number) {
    return number_format($number, 2, ',', '&nbsp;');
}