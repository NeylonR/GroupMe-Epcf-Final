<?php
namespace App\Data;

use App\Entity\DataCenter;
use App\Entity\HomeWorld;
use App\Entity\GamingType;
use App\Entity\Language;
use App\Entity\Job;

class FilterData
{
    /**
     * @var DataCenter[]
     */
    public array $dataCenter = [];

    /**
     * @var HomeWorld[]
     */
    public array $homeWorld = [];

    /**
     * @var GamingType[]
     */
    public array $gamingType = [];

    /**
     * @var Language
     */
    public object $language;

    /**
     * @var ?integer
     */
    public ?int $ilvl = null;

    /**
     * @var Job[]
     */
    public array $job = [];

    /**
     * @var time
     */
    public $activityStart = null;

    /**
     * @var time
     */
    public $activityEnd = null;
}