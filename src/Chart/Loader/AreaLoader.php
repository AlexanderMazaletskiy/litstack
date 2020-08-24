<?php

namespace Lit\Chart\Loader;

use Carbon\CarbonInterface;
use Closure;
use Lit\Chart\ChartSet;

class AreaLoader extends ChartLoader
{
    use Concerns\HasGoal;
    use Concerns\HasComparison;

    /**
     * Make series.
     *
     * @param CarbonInterface $startTime
     * @param int             $iterations
     * @param Closure         $timeResolver
     * @param Closure         $valueResolver
     * @param Closure         $labelResolver
     *
     * @return array
     */
    protected function makeSeries(
        CarbonInterface $startTime,
        int $iterations,
        Closure $timeResolver,
        Closure $valueResolver,
        Closure $labelResolver
    ): array {
        $nextTimeResolver = $this->getNextTimeResolver();

        $query = $this->config->model::query();

        $set = ChartSet::make($query, $valueResolver, $timeResolver)
            ->label($labelResolver)
            ->iterations($iterations);

        $set->load($startTime);

        if ($this->config->compare) {
            $set->load($nextTimeResolver($startTime));
        }

        return [
            'results' => $this->getResults($set),
            'chart'   => $this->engine->render(
                $this->getNames(),
                $set,
            ),
        ];
    }

    public function getResults(ChartSet $set)
    {
        return collect($set->getValues())->map(function ($values) {
            return $this->config->result($values);
        });
    }

    protected function getNextTimeResolverConfig()
    {
        return [
            'today'      => fn ($time) => $time->subDay(),
            'yesterday'  => fn ($time) => $time->subWeek(),
            'last7days'  => fn ($time) => $time->subWeek(),
            'thisweek'   => fn ($time) => $time->subWeek(),
            'last30days' => fn ($time) => $time->subDays(30),
            'thismonth'  => fn ($time) => $time->subMonth(),
        ];
    }
}
