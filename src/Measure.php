<?php
namespace Vda\Profiler;

class Measure 
{
    private $timeStart = null;

    private $timeStop = null;

    private $name;

    /**
     * @var Measure
     */
    private $parentMeasure;

    /**
     * @var Measure[]
     */
    private $nestedMeasures = [];

    private $isGrouped = false;


    public function __construct($name = '', Measure $parent = null, $isGrouped = false)
    {
        $this->name = $name;
        $this->parentMeasure = $parent;
        $this->isGrouped = $isGrouped;
    }

    public function isActive()
    {
        return is_null($this->timeStop) && !is_null($this->timeStart);
    }

    public function isRoot()
    {
        return is_null($this->parentMeasure);
    }


    public function getDuration()
    {
        if (!is_null($this->timeStart) && !is_null($this->timeStop)) {
            $duration = $this->timeStop - $this->timeStart;
            return $duration;
        }

        return null;
    }

    /**
     * @return Measure|null
     */
    public function getParentMeasure()
    {
        return $this->parentMeasure;
    }

    public function appendNestedMeasure(Measure $nestedMeasure)
    {
        $this->nestedMeasures[] = $nestedMeasure;
    }

    /**
     * @return Measure[]
     */
    public function getNestedMeasures()
    {
        return $this->nestedMeasures;
    }

    /**
     * @param mixed $timeStart
     */
    public function setTimeStart($timeStart)
    {
        $this->timeStart = $timeStart;
    }

    /**
     * @return mixed
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * @param mixed $timeStop
     */
    public function setTimeStop($timeStop)
    {
        $this->timeStop = $timeStop;
    }

    /**
     * @return mixed
     */
    public function getTimeStop()
    {
        return $this->timeStop;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isGrouped()
    {
        return $this->isGrouped;
    }

    /**
     * @param $message
     * @return null|Measure
     */
    public function findMeasureByMessage($message)
    {
        foreach($this->getNestedMeasures() as $nestedMeasure)
        {
            if ($nestedMeasure->getName() == $message) {
                return $nestedMeasure;
            }

            $result = $nestedMeasure->findMeasureByMessage($message);

            if (!is_null($result)) {
                return $result;
            }
        }

        return null;
    }
}
 