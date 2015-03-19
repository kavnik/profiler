<?php
namespace Vda\Profiler;

use Vda\Profiler\ResultFormatter\IResultFormatter;

interface IProfiler
{
    public function start($measureName, $forceStartTime = null);

    public function startGrouped($measureGroupName);

    public function stop();

    /**
     * @return Measure
     */
    public function getMeasure();

    /**
     * Get grouped measures data, where key is measure name
     * and value is array with 'duration', 'calls' attributes
     *
     * groupedMeasuresData
     *  [groupedName] => groupedMeasureData
     *      duration
     *      calls
     *
     * @return array
     */
    public function getGroupedMeasuresData();

    public function getFormattedResult(IResultFormatter $formatter = null);
}
