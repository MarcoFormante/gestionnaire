<?php

function showError($text){
    throw new Exception($text);
}