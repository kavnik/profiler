<?php
namespace Vda\Profiler\ResultFormatter;

use Vda\Profiler\Measure;

interface IResultFormatter
{
    /**
     * @param Measure $measure
     * @param array $groupedMeasuresData
     * @return mixed
     */
    public function format(Measure $measure, array $groupedMeasuresData);
}