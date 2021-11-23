<?php

namespace Json\Json;

function renderJson(array $ast): string
{
    return json_encode($ast);
}
