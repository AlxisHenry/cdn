<?php

/** 
 * @param string $element
 * @param string $type
 * @return string
 */
function htmlFormat(string $element, string $type = "ul"): string
{
	return "<$type class='list-group'>$element</$type>";
}