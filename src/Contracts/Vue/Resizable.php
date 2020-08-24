<?php

namespace Lit\Contracts\Vue;

interface Resizable
{
    /**
     * Set component width.
     *
     * @param  int|float $width
     * @return $this
     */
    public function width($width);
}
