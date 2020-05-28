<?php

//método para formatar o preço em formato de real
function formatPrice(float $vlprice)
{
    return number_format($vlprice, 2, ",", ".");
}