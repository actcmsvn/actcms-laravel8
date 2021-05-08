<?php

namespace Actcmscss;

interface Castable
{
    public function cast($value);

    public function uncast($value);
}
