<?php
/**
 * Created by PhpStorm.
 * User: thuyn
 * Date: 21-Aug-17
 * Time: 1:40 PM
 */

namespace Message\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="chatrooms")
 */
class Chatroom
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="initor")
     */
    protected $initor;

    /**
     * @ORM\Column(name="user")
     */
    protected $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getInitor()
    {
        return $this->initor;
    }

    /**
     * @param mixed $initor
     */
    public function setInitor($initor)
    {
        $this->initor = $initor;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }



    public function getData()
    {
        return [
            'initor' => $this->initor,
            'user' => $this->user
            ];
    }
}