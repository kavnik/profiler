<?php
namespace Vda\Profiler;

use Vda\Profiler\ResultFormatter\IResultFormatter;
use Vda\Profiler\ResultFormatter\SimpleResultFormatter;

class SimpleProfiler implements IProfiler
{
    private $measure;

    private $current;

    private $groupedMeasuresData = [];

    /**
     * @var IResultFormatter
     */
    private $defaultResultFormatter;

    public function __construct()
    {
        $this->measure = new Measure();
        $this->current = $this->measure;

        $this->defaultResultFormatter = new SimpleResultFormatter();
    }

    public function start($measureName, $forceStartTime = null, $isGrouped = false)
    {
        if ($this->current->isActive() || $this->current->isRoot()) {
            $nestedMeasure = new Measure($measureName, $this->current, $isGrouped);
            $this->current->appendNestedMeasure($nestedMeasure);

            $this->current = $nestedMeasure;
        }

        if (!is_null($forceStartTime)) {
            $this->current->setTimeStart($forceStartTime);
        } else {
            $this->current->setTimeStart(microtime(true));
        }
    }

    public function startGrouped($measureGroupName)
    {
        $this->start($measureGroupName, null, true);
    }

    public function stop()
    {
        $this->current->setTimeStop(microtime(true));

        if ($this->current->isGrouped()) {
            if (!isset($this->groupedMeasuresData[$this->current->getName()])) {
                $this->groupedMeasuresData[$this->current->getName()] = [
                    'duration' => 0,
                    'calls' => 0,
                ];
            }
            $this->groupedMeasuresData[$this->current->getName()]['duration'] += $this->current->getDuration();
            $this->groupedMeasuresData[$this->current->getName()]['calls'] += 1;
        }

        $parent = $this->current->getParentMeasure();

        if (!is_null($parent)) {
            $this->current = $this->current->getParentMeasure();
        }
    }

    /**
     * @return Measure
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    public function getGroupedMeasuresData()
    {
        return $this->groupedMeasuresData;
    }

    public function getFormattedResult(IResultFormatter $formatter = null)
    {
        if (empty($formatter)) {
            $formatter = $this->defaultResultFormatter;
        }

        return $formatter->format($this->getMeasure(), $this->getGroupedMeasuresData());
    }
}
 