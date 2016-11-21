<?php

/**
 * Команда
 *
 * @author Антон Новопашин <antonfromkrsk@gmail.com>
 */
class Team
{
    /**
     * @var integer
     */
    private $id;

    /**
     * Название
     *
     * @var string $title
     */
    private $title;

    /**
     * Страна
     *
     * @var string $country
     */
    private $country;

    /**
     * Игроки
     *
     * @var array $players
     */
    private $players;

    /**
     * Забитые голы
     *
     * @var integer $goals
     */
    private $goals;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param array $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
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


}