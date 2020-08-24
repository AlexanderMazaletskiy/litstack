<?php

namespace Lit\Page\Table\Casts;

use Lit\Page\Table\ColumnCast;
use Lit\Support\Facades\LitApp;
use NumberFormatter;

class MoneyColumn extends ColumnCast
{
    /**
     * The 3-letter ISO 4217 currency code indicating the currency to use.
     *
     * @var string
     */
    protected $currency;

    /**
     * NumberFormatter instance.
     *
     * @var NumberFormatter
     */
    protected $formatter;

    /**
     * Create new MoneyCast instance.
     *
     * @param  string      $currency
     * @param  string|null $locale
     * @return void
     */
    public function __construct($currency = 'EUR', $locale = null)
    {
        if (! $locale) {
            $locale = LitApp::getLocale();
        }

        $this->currency = $currency;
        $this->formatter = new NumberFormatter(
            $locale,
            NumberFormatter::CURRENCY
        );
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string                              $key
     * @param  mixed                               $value
     * @param  array                               $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        // dd($this->formatter);

        return $this->formatter->formatCurrency($value, $this->currency);
    }
}
