<?php
namespace Vda\Profiler\ResultFormatter;

use Vda\Profiler\Measure;

class SimpleResultFormatter implements IResultFormatter
{
    public function format(Measure $measure, array $groupedMeasuresData)
    {
        return $this->renderNonGroupedMeasures($measure)
            . $this->renderGroupedMeasures($groupedMeasuresData);
    }

    protected function renderNonGroupedMeasures(Measure $measure, $parentMessage = '')
    {
        $result = '';

        foreach($measure->getNestedMeasures() as $nestedMeasure)
        {
            $fullMessage = $parentMessage . $nestedMeasure->getName();

            if (!$nestedMeasure->isGrouped()) {
                $result .= sprintf("%10.3f %s \r\n", $nestedMeasure->getDuration() * 1000, $fullMessage);
            }

            if ($nestedMeasure->getNestedMeasures()) {
                $result .= $this->renderNonGroupedMeasures($nestedMeasure, $fullMessage . '.' );
            }
        }

        return $result;
    }

    protected function renderGroupedMeasures($groupedMeasuresData)
    {
        $result = '';

        $result .= sprintf("\r\nGroups\r\n");
        $result .= sprintf("   totalMs      avgMs     number  groupName \r\n");
        foreach($groupedMeasuresData as $groupName => $groupData) {
            $result .= sprintf(
                "%10.3f %10.3f %10d  %s \r\n",
                $groupData['duration'] * 1000,
                $groupData['duration'] * 1000 /$groupData['calls'],
                $groupData['calls'],
                $groupName
            );
        }

        return $result;
    }
}
