<?php

/**
 * Игрок
 *
 * @author Антон Новопашин <antonfromkrsk@gmail.com>
 */
class Player
{
    /**
     * @var integer
     */
    private $id;

    /**
     * Имя
     *
     * @var string $name
     */
    private $name;

    /**
     * Номер игрока
     *
     * @var integer $number
     */
    private $number;

    /**
     * Основа или нет
     *
     * @var bool $isBase
     */
    private $isBase = false;

    /**
     * Замена или нет
     *
     * @var bool $isSubstitute
     */
    private $isSubstitute = false;

    /**
     * Время проведенное на поле в минутах
     *
     * @var integer $timeOnTheField
     */
    private $timeOnTheField = 0;

    /**
     * Массив временных отметок когда игрок получал красную карточку
     *
     * @var array $redCards
     */
    private $redCards;

    /**
     * Время последнего выпуска на поле
     *
     */
    private $lastTimeUsed = null;

    /**
     * Количество забитых голов
     *
     * @var integer $goals
     */
    private $goals;

    /**
     * Количество голевых передач
     *
     * @var integer $goalPases
     */
    private $goalPases;

    /**
     * Массив временных отметок когда игрок получал желтые карточки
     *
     */
    private $yelowCards;

    /**
     * Нахождение игрока на поле
     *
     * @var bool $activity
     */
    private $activity = false;

    /**
     * @return boolean
     */
    public function isIsSubstitute()
    {
        return $this->isSubstitute;
    }

    /**
     * @param boolean $isSubstitute
     */
    public function setIsSubstitute($isSubstitute)
    {
        $this->isSubstitute = $isSubstitute;
    }

    /**
     * @return int
     */
    public function getTimeOnTheField()
    {
        return $this->timeOnTheField;
    }

    /**
     * @param int $timeOnTheField
     */
    public function setTimeOnTheField($timeOnTheField)
    {
        $this->timeOnTheField = $timeOnTheField;
    }

    /**
     * @return int
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * @param int $goals
     */
    public function setGoals($goals)
    {
        $this->goals = $goals;
    }

    /**
     * @return int
     */
    public function getGoalPases()
    {
        return $this->goalPases;
    }

    /**
     * @param int $goalPases
     */
    public function setGoalPases($goalPases)
    {
        $this->goalPases = $goalPases;
    }

    /**
     * @return mixed
     */
    public function getYelowCards()
    {
        return $this->yelowCards;
    }

    /**
     * @param mixed $yelowCards
     */
    public function setYelowCards($yelowCards)
    {
        $this->yelowCards = $yelowCards;
    }

    /**
     * @return array
     */
    public function getRedCards()
    {
        return $this->redCards;
    }

    /**
     * @param array $redCards
     */
    public function setRedCards($redCards)
    {
        $this->redCards = $redCards;
    }

    /**
     * @return mixed
     */
    public function getLastTimeUsed()
    {
        return $this->lastTimeUsed;
    }

    /**
     * @param mixed $lastTimeUsed
     */
    public function setLastTimeUsed($lastTimeUsed)
    {
        $this->lastTimeUsed = $lastTimeUsed;
    }

    /**
     * @return boolean
     */
    public function isIsBase()
    {
        return $this->isBase;
    }

    /**
     * @param boolean $isBase
     */
    public function setIsBase($isBase)
    {
        $this->isBase = $isBase;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return RestojobEmployers
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Player
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return boolean
     */
    public function isActivity()
    {
        return $this->activity;
    }

    /**
     * Игрок вызывается на поле
     */
    public function enterField()
    {
        $this->activity = true;
    }

    /**
     * Игрок отзывается с поля
     */
    public function leaveField()
    {
        $this->activity = false;
    }

}